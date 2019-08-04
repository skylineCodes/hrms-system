<?php

namespace App\Http\Controllers\Employee;

use Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\EmployeeRegistered;
use App\Mail\EmployeePasswordReset;
use App\Http\Controllers\Controller;
use App\Notifications\EmployeePasswordResetSuccess;
use App\Http\Requests\Employee\EmployeePostRequest;
use App\Http\Resources\Employee\EmployeeAuthResource;
use App\Repositories\Contracts\Employee\ProfileRepository;
use App\Repositories\Contracts\Employee\EmployeeRepository;
use App\Repositories\Contracts\Department\DepartmentRepository;
use App\Repositories\Contracts\Password\PasswordResetRepository;

class EmployeeAuthController extends Controller
{
    protected $employee;
    
    protected $profile;

    protected $passwordReset;

    public function __construct(EmployeeRepository $employee, ProfileRepository $profile, PasswordResetRepository $passwordReset)
    {
        $this->employee = $employee;

        $this->profile = $profile;

        $this->passwordReset = $passwordReset;

        $this->middleware('permission:create_employees')->only('store');
        $this->middleware('permission:update_employees')->only('update', 'deactivate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeePostRequest $request)
    {
        $employee = $this->employee->create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'region' => strtoupper($request->region),
            'gender' => ucfirst($request->gender),
            'nationality' => ucfirst($request->nationality),
            'job_title' => ucfirst($request->job_title),
            'address' => $request->address,
            'city' => ucfirst($request->city),
            'country' => ucfirst($request->country),
            'province' => ucfirst($request->province),
            'work_phone' => $request->work_phone,
            'work_email' => $request->work_email,
            'department_id' => $request->department_id
        ]);

        $employee->assignRole(strtolower($request->role));

        $employeeProfile = $this->profile->create([
            'employee_id' => $employee->id
        ]);

        Mail::to($employee->email)
            ->send(new EmployeeRegistered($employee));

        return response()->json([
            'message' => 'Employee details successfully created and forwarded to email!'
        ]);
    }

    /**
     * Employee Update Password Credentials
     */
    public function password(Request $request, $code)
    {
        $employee = $this->employee->findWhereFirst('employee_code', $code);

        $employee->password = bcrypt($request->password);

        $employee->save();

        return response()->json([
            'message' => 'Password set successfully!'
        ], 200);
    }

    /**
     * Create token password reset
     * 
     * @param [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'work_email' => 'required|string|email',
        ]);

        $employee = $this->employee->findWhereFirst('work_email', $request->work_email);

        if (!$employee)
        return response()->json([
            'message' => 'We can\'t find Employee with that e-mail address.'
        ], 404);

        $passwordReset = $this->passwordReset->updateOrCreate(
            ['email' => $employee->work_email],
            [
                'email' => $employee->work_email,
                'token' => $this->generatePIN()
            ]
        );

        if ($employee && $passwordReset)
        Mail::to($employee->work_email)
            ->send(new EmployeePasswordReset($passwordReset));

        return response()->json([
            'message' => 'Password reset code sent to your email!'
        ]);
    }

    /**
     * Reset Password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = $this->passwordReset->passwordReset([
            ['token', $request->token],
            ['email', $request->email]
        ]);

        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset code is invalid.'
            ], 404);
        
        $employee = $this->employee->findWhereFirst('work_email', $passwordReset->email);

        if (!$employee)
            return response()->json([
                'message' => 'We can\'t find employee with that e-mail address.'
            ], 404);

        $employee->password = bcrypt($request->password);
        $employee->save();

        $passwordReset->delete();

        $employee->notify(new EmployeePasswordResetSuccess($employee));

        return response()->json([
            'message' => 'Password reset was successful'
        ]);
    }

    /**
     * Employee Login via Code
     */
    public function codeLogin(Request $request)
    {
        $verify = $this->employee->findWhereFirst('employee_code', request(['employee_code']));

        if (!$verify)
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        if ($verify->email_verified_at === null)
        {
            $verify->email_verified_at = now();
            $verify->save();
        }

        $token = $verify->createToken('Almond-HRMS-Employee');

        $tokenResult = $token->token;
        $tokenResult->save();

        return response()->json([
            'data' => new EmployeeAuthResource($verify),
            'access_token' => $token->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->expires_at
            )->toDateTimeString()
        ], 200);
    }

    /**
     * Employee Login via Email
     */
    public function emailLogin(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::guard('employee')->attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $employee = auth()->guard('employee')->user();

        $token = $employee->createToken('Almond-HRMS');

        $tokenResult = $token->token;
        $tokenResult->save();

        return response()->json([
            'data' => new EmployeeAuthResource($employee),
            'access_token' => $token->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->expires_at
            )->toDateTimeString()
        ], 200);
    }

    /**
     * Generate password reset Code
     */
    public function generatePIN($digits = 6){
        $i = 0;
        $pin = "";
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin = mt_rand(100000,999999);
            $i++;
        }
        return $pin;
    }
}
