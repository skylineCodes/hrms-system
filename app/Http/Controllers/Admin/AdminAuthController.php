<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Mail;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Mail\AdminRegistered;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;
use App\Http\Resources\Admin\AdminAuthResource;
use App\Http\Requests\Auth\AdminAuthStoreRequest;
use App\Repositories\Contracts\Admin\{
    AdminRepository,
    ProfileRepository
};

class AdminAuthController extends Controller
{
    protected $admin;

    protected $adminProfile;

    public function __construct(AdminRepository $admin, ProfileRepository $adminProfile)
    {
        $this->admin = $admin;
        $this->adminProfile = $adminProfile;

        $this->middleware('permission:create_employees', ['only' => ['register']]);
    }

    public function register(Request $request)
    {
        $admin = $this->admin->create([
            'email' => $request->email,
            'remember_token' => str_random(60),
            'region' => strtoupper($request->region)
        ]);

        $admin->assignRole(strtolower($request->role));

        $adminProfile = $this->adminProfile->create([
            'admin_id' => $admin->id
        ]);

        Mail::to($admin->email)
            ->send(new AdminRegistered($admin));

        return response()->json([
            'message' => 'Your login credentials has been forwarded to your email!'
        ]);
    }

    public function codeLogin(Request $request)
    {
        $verify = $this->admin->findWhereFirst('employee_code', request(['employee_code']));

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

        $token = $verify->createToken('Almond-HRMS');

        $tokenResult = $token->token;
        $tokenResult->save();

        return response()->json([
            'data' => new AdminAuthResource($verify),
            'access_token' => $token->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->expires_at
            )->toDateTimeString()
        ], 200);
    }

    public function emailLogin(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::guard('admin')->attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $admin = auth()->guard('admin')->user();

        $token = $admin->createToken('Almond-HRMS');

        $tokenResult = $token->token;
        $tokenResult->save();

        return response()->json([
            'data' => new AdminAuthResource($admin),
            'access_token' => $token->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->expires_at
            )->toDateTimeString()
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function password(Request $request, $code)
    {
        $admin = $this->admin->findWhereFirst('employee_code', $code);

        $admin->password = bcrypt($request->password);

        $admin->save();

        return response()->json([
            'message' => 'Password set successfully!'
        ], 200);
    }
}
