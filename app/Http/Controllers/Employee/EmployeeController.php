<?php

namespace App\Http\Controllers\Employee;

use Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\EmployeeAccountReactivated;
use App\Mail\EmployeeAccountDeactivated;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Employee\EmployeeProfileResource;
use App\Repositories\Contracts\Employee\EmployeeRepository;
use App\Http\Resources\Employee\DeactivatedEmployeeResource;

class EmployeeController extends Controller
{
    protected $employee;

    public function __construct(EmployeeRepository $employee)
    {
        $this->employee = $employee;

        $this->middleware('permission:update_employees')->only('update', 'deactivate');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = $this->employee->withCriteria([
            new EagerLoad(['department'])
        ])->all();

        return response()->json([
            'data' => EmployeeProfileResource::collection($employees)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($employee_code)
    {
        $employee = $this->employee->findWhereFirst('employee_code', $employee_code);

        return response()->json([
            'data' => new EmployeeProfileResource($employee)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $employee_code)
    {
        
        $employee = $this->employee->findWhereFirst('employee_code', $employee_code);

        $employee->nationality = $request->get('nationality', $employee->nationality);
        $employee->job_title = $request->get('job_title', $employee->job_title);
        $employee->province = $request->get('province', $employee->province);
        $employee->department_id = $request->get('department_id', $employee->department_id);

        $employee->save();

        return response()->json([
            'message' => 'Resource updated successfully!',
            'data' => new EmployeeResource($employee)
        ], 200);
    }

    /**
     * Employee update specified resource in storage
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $employee_code
     * @return \Illuminate\Http\Resource
     */
    public function employeeUpdate(EmployeeUpdateRequest $request, $employee_code)
    {
        $employee = $this->employee->findWhereFirst('employee_code', $employee_code);

        $this->authorize('employeeUpdate', $employee);

        $employee->username = $request->get('username', $employee->username);
        $employee->firstname = $request->get('firstname', $employee->firstname);
        $employee->lastname = $request->get('lastname', $employee->lastname);
        $employee->email = $request->get('email', $employee->email);
        $employee->gender = $request->get('gender', $employee->gender);
        $employee->address = $request->get('address', $employee->address);
        $employee->city = $request->get('city', $employee->city);
        $employee->country = $request->get('country', $employee->country);
        $employee->work_phone = $request->get('work_phone', $employee->work_phone);
        $employee->work_email = $request->get('work_email', $employee->work_email);
        $employee->profile->mobile_phone = $request->get('mobile_phone', $employee->profile->mobile_phone);
        $employee->profile->birthday = $request->get('birthday', $employee->profile->birthday);
        $employee->profile->marital_status = $request->get('marital_status', $employee->profile->marital_status);
        $employee->profile->postal_code = $request->get('postal_code', $employee->profile->postal_code);
        $employee->profile->home_phone = $request->get('home_phone', $employee->profile->home_phone);
        $employee->profile->emergency_contact = $request->get('emergency_contact', $employee->profile->emergency_contact);
        $employee->profile->private_email = $request->get('private_email', $employee->profile->private_email);

        if ($request->hasFile('driver_license')) {
            $file = $request->file('driver_license');

           $filename = 'driver-license-' . time() . '.' . $file->getClientOriginalExtension();

           $employee->profile->driver_license = $file->storeAs('driver_license', $filename);
       }

       if ($request->hasFile('profile_photo')) {
           $file = $request->file('profile_photo');

           $filename = 'profile-photo-' . time() . '.' . $file->getClientOriginalExtension();

           $employee->profile->profile_photo = $file->storeAs('profile_photo', $filename);
       }

       $employee->save();

       $employee->profile->save();

       return response()->json([
           'message' => 'Resource updated successfully!',
           'data' => new EmployeeProfileResource($employee)
       ], 200);
    }

    /**
     * Deactivate an Employee
     * 
     * @param int $employee_code
     * @return \Illuminate\Http\Response
     */
    public function deactivate($employee_code)
    {
        $employee = $this->employee->findWhereFirst('employee_code', $employee_code);

        $employee->employment_status = 'inactive';

        $employee->save();

        Mail::to($employee->email)
            ->send(new EmployeeAccountDeactivated($employee));

        $employee->delete();

        return response()->json([
            'message' => 'Employee account deactivated successfully!'
        ]);
    }

    /**
     * Reacvtivate Employee
     * 
     * @param int $employee_code
     * @return \Illuminate\Http\Response
     */
    public function reactivate($employee_code)
    {
        $employee = $this->employee->fetchTrashed('employee_code', $employee_code);

        $employee = $this->employee->findWhereFirst('employee_code', $employee_code);

        $employee->employment_status = 'active';

        $employee->save();

        Mail::to($employee->email)
            ->send(new EmployeeAccountReactivated($employee));

        return response()->json([
            'message' => 'Employee account reactivated successfully!'
        ]);
    }

    /**
     * Read all deactivated employees
     * 
     * @return \Illuminate\Http\Response
     */
    public function deactivatedEmployees()
    {
        $employee = $this->employee->trashed();

        return response()->json([
            'data' => DeactivatedEmployeeResource::collection($employee)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($employee_code)
    {
        $this->employee->permanentDelete('employee_code', $employee_code);

        return response()->json([
            'message' => 'Employee data deleted successfully!'
        ], 200);
    }
}
