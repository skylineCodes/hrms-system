<?php

namespace App\Http\Controllers\Leave;

use Mail;
use App\Model\Admin\Admin;
use Illuminate\Http\Request;
use App\Mail\EmployeeLeaveRequest;
use App\Mail\LeaveRequestSent;
use App\Mail\LeaveRequestApproved;
use App\Mail\LeaveRequestRejected;
use App\Mail\LeaveRequestPending;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Resources\Leave\LeaveResource;
use App\Http\Requests\Leave\LeaveFormRequest;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Contracts\Leave\LeaveRepository;
use App\Repositories\Contracts\Admin\AdminRepository;
use App\Repositories\Contracts\Employee\EmployeeRepository;

class LeaveController extends Controller
{
    protected $admin;

    protected $leave;

    protected $employeeLeave;

    public function __construct(LeaveRepository $leave, AdminRepository $admin, EmployeeRepository $employee)
    {
        $this->leave = $leave;

        $this->admin = $admin;

        $this->employeeLeave = $employee;

        $this->middleware('permission:create_leaves')->only('store');
        $this->middleware('permission:read_leaves')->only('index', 'show', 'fetchArchive');
        $this->middleware('permission:update_leaves')->only('approve', 'reject', 'pending');
        $this->middleware('permission:delete_leaves')->only('archiveLeave');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaves = $this->leave->withCriteria([
            new EagerLoad(['leaveType', 'employee'])
        ])->all();

        return response()->json([
            'data' => LeaveResource::collection($leaves)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeaveFormRequest $request)
    {
        $leave = $this->leave->create([
            'leave_type_id' => $request->leave_type_id,
            'started_at'    => $request->started_at .= ' 00:00:00',
            'ended_at'      => $request->ended_at .= ' 00:00:00',
            'description'   => $request->description,
            'no_of_days'    => $request->no_of_days,
            'applied_on'    => now(),
            'employee_id'   => auth()->user()->id
        ]);

        // Send Email to the HR!
        $admin = Admin::role('hr')->first();

        Mail::to($admin->email)->send(new EmployeeLeaveRequest($leave));

        // Send employee request sent email
        Mail::to($leave->employee->work_email)->send(new LeaveRequestSent($leave));

        return response()->json([
            'message' => 'Your leave request has been forwarded to the HR!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leave = $this->leave->find($id);

        return response()->json([
            'data' => new LeaveResource($leave)
        ], 200);
    }

    /**
     * Employee Leave Request
     * 
     * @param string $employee_code
     * @return \Illuminate\Http\Response
     */
    public function employeeLeaveRequest($employee_code)
    {
        $employeeLeave = $this->employeeLeave->findWhereFirst('employee_code', $employee_code);

        return response()->json([
            'data' => LeaveResource::collection($employeeLeave->leaves)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $leave = $this->leave->find($id);

        $leave->approved_by = auth()->user()->id;
        $leave->approved_at = now();
        $leave->status = "1";

        $leave->save();

        // Send Email to employee
        Mail::to($leave->employee->work_email)
            ->send(new LeaveRequestApproved($leave));

        return response()->json([
            'data' => new LeaveResource($leave)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        $leave = $this->leave->find($id);

        $leave->approved_by = auth()->user()->id;
        $leave->approved_at = now();
        $leave->status = "2";

        $leave->save();

        // Send Email to employee
        Mail::to($leave->employee->work_email)
            ->send(new LeaveRequestRejected($leave));

        return response()->json([
            'data' => new LeaveResource($leave)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pending(Request $request, $id)
    {
        $leave = $this->leave->find($id);

        $leave->approved_by = auth()->user()->id;
        $leave->approved_at = now();
        $leave->status = "0";

        $leave->save();

        // Send Email to employee
        Mail::to($leave->employee->work_email)
            ->send(new LeaveRequestPending($leave));

        return response()->json([
            'data' => new LeaveResource($leave)
        ], 200);
    }

    /**
     * Employee cancel leave request
     * 
     * @param int $employee_code
     * @return \Illuminate\Http\Response
     */
    public function cancelLeave(Request $request, $employee_code, $id)
    {
        $employee = $this->employeeLeave->findWhereFirst('employee_code', $employee_code);
        
        $leave = $this->leave->find($id);

        if($employee->id === $leave->employee_id)
        {
            $this->leave->permanentDelete('employee_id', $leave->employee_id);

            return response()->json([
                'message' => 'Leave request cancelled successfully!'
            ], 200);
        } 
        
        return response()->json([
            'message' => 'You do not have permission to access this resource!'
        ], 403);
    }

    /**
     * Archive the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archiveLeave($id)
    {
        $this->leave->delete($id);

        return response()->json([
            'message' => 'Leave archived successfully!'
        ], 200);
    }

    /**
     * Fetch archived resource from storage
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchArchive()
    {
        $leave = $this->leave->trashed();

        return response()->json([
            'message' => 'Archive Leaves fetched successfully!',
            'data' => LeaveResource::collection($leave)
        ], 200);
    }

    /**
     * Restore archived resource from storage
     * 
     * @return \Illuminate\Http\Response
     */
    public function restoreArchive($id)
    {
        $this->leave->fetchTrashed('id', $id);

        return response()->json([
            'message' => 'Leave request restored successfully!'
        ], 200);
    }

    /**
     * Permanent delete resource from storage
     * 
     * @return \Illuminate\Http\Response
     */
    public function permanentDelete($id)
    {
        $this->leave->permanentDelete('id', $id);

        return response()->json([
            'message' => 'Leave resource deleted permanently!'
        ], 200);
    }
}
