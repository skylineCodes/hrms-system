<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Notifications\AdminEmailChanged;
use App\Http\Requests\AdminProfileRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProfileResource;
use App\Repositories\Contracts\Admin\{
    AdminRepository,
    ProfileRepository
};

class ProfileController extends Controller
{
    protected $admin;

    protected $profile;

    public function __construct(ProfileRepository $profile, AdminRepository $admin)
    {
        $this->admin = $admin;

        $this->profile = $profile;
    }

    public function update(AdminProfileRequest $request, $employee_code)
    {
        $admin = $this->admin->findWhereFirst('employee_code', $employee_code);

        $admin->username = strtolower($request->get('username', strtolower($admin->username)));
        $admin->profile->firstname = $request->get('firstname', $admin->profile->firstname);
        $admin->profile->lastname = $request->get('lastname', $admin->profile->lastname);
        $admin->profile->middlename = $request->get('middlename', $admin->profile->middlename);
        $admin->profile->phone = $request->get('phone', $admin->profile->phone);
        $admin->profile->sex = strtolower($request->get('sex', $admin->profile->sex));
        $admin->profile->nationality = $request->get('nationality', $admin->profile->nationality);
        $admin->profile->city = $request->get('city', $admin->profile->city);
        $admin->profile->address = $request->get('address', $admin->profile->address);
        $admin->profile->description = $request->get('description', $admin->profile->description);
        $admin->profile->dob = $request->get('dob', $admin->profile->dob);

        $admin->save();
        $admin->profile->save();

        return new ProfileResource($admin);
    }

    public function emailUpdate(Request $request, $employee_code)
    {
        $admin = $this->admin->findWhereFirst('employee_code', $employee_code);

        if ($request->has('email') && $admin->email != $request->email)
        {
            $admin->email = $request->get('email', $admin->email);
            $admin->email_verified_at = null;
        }

        if (!$admin->isDirty())
        {
            return response()->json(['error' => 'You need to specify a different value to update', 'code' => 422], 422);
        }

        $admin->save();

        $admin->notify(new AdminEmailChanged($admin));
        
        return response()->json([
            'message' => 'Confirmation message has been sent to mail!'
        ], 200);
    }

    public function emailConfirm($employee_code)
    {
        $admin = $this->admin->findWhereFirst('employee_code', $employee_code);

        if (!$admin)
        {
            return response()->json([
                'message' => 'This employee code is not found.'
            ], 404);
        }

        $admin->email_verified_at = now();
        $admin->save();

        return response()->json([
            'message' => "Account verified successfully!"
        ], 200);
    }
}
