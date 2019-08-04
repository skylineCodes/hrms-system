<?php

namespace App\Http\Controllers\Leave;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Leave\LeaveTypeResource;
use App\Repositories\Contracts\Leave\LeaveTypeRepository;
use App\Http\Requests\Leave\LeaveTypeFormRequest;

class LeaveTypeController extends Controller
{
    protected $leaveType;

    public function __construct(LeaveTypeRepository $leaveType)
    {
        $this->leaveType = $leaveType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LeaveTypeResource::collection($this->leaveType->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeaveTypeFormRequest $request)
    {
        $leaveType = $this->leaveType->create([
            'leave_type' => ucfirst($request->leave_type),
            'leaves_per_day' => $request->leaves_per_day
        ]);

        return response()->json([
            'message' => 'Leave Type created successfully!',
            'data' => new LeaveTypeResource($leaveType)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $leaveType = $this->leaveType->find($id);

        $leaveType->leave_type = ucfirst($request->get('leave_type', $leaveType->leave_type));
        $leaveType->leaves_per_day = $request->get('leaves_per_day', $leaveType->leaves_per_day);

        $leaveType->save();

        return response()->json([
            'message' => 'Resource updated successfully!',
            'data' => new LeaveTypeResource($leaveType)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leaveType = $this->leaveType->delete($id);

        return response()->json([
            'message' => 'Resource deleted successfully!'
        ], 200);
    }
}
