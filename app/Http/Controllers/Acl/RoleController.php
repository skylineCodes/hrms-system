<?php

namespace App\Http\Controllers\Acl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Acl\RoleResource;
use App\Http\Requests\Acl\RoleStoreRequest;
use App\Http\Resources\Admin\AdminAuthResource;
use App\Repositories\Contracts\Acl\RoleRepository;
use App\Repositories\Contracts\Admin\AdminRepository;
use App\Repositories\Eloquent\Criteria\{
    EagerLoad
};

class RoleController extends Controller
{
    protected $role;

    protected $admin;

    public function __construct(RoleRepository $role, AdminRepository $admin)
    {
        $this->role = $role;

        $this->admin = $admin;

        $this->middleware('permission:create_roles')->only('store', 'assignRoleToUser');
        $this->middleware('permission:update_roles')->only('update');
        $this->middleware('permission:delete_roles')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = $this->role->withCriteria([
            new EagerLoad(['permissions'])
        ])->paginate();

        return RoleResource::collection($role);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        $role = $this->role->create([
            'name' => strtolower($request->name),
            'guard_name' => 'adminapi'
        ]);

        // $role->syncPermissions([$request->permission1, $request->permission2, $request->permission3, $request->permission4]);

        return response()->json([
            'message' => 'Role created and with specified permissions successfully!',
            'data' => new RoleResource($role)
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
        $role = $this->role->withCriteria([
            new EagerLoad(['permissions', 'permissions.roles'])
        ])->find($id);

        return response()->json([
            'data' => new RoleResource($role)
        ], 200);
    }

    /**
     * Fetch User Role
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function userRole($id)
    {
        $admin = $this->admin->find($id);

        $admin->getRoleNames();

        return response()->json([
            'data' => new AdminAuthResource($admin)
        ]);
    }

    /**
     * Assign Role to User
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function assignRoleToUser(Request $request, $id)
    {
        $admin = $this->admin->find($id);

        if($admin->getRoleNames()->first() === strtolower($request->role))
        {
            return response()->json([
                'message' => 'User already possesses specified role!'
            ]);
        }

        if ($admin->getRoleNames()->first() === null) {
            $admin->assignRole(strtolower($request->role));
        } else {
            $admin->removeRole($admin->getRoleNames()->first());
            $admin->assignRole(strtolower($request->role));
        }

        return response()->json([
            'message' => 'Role assigned to user successfully!',
            'data' => new AdminAuthResource($admin)
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
        $role = $this->role->find($id);

        $role->givePermissionTo([$request->permission1, $request->permission2, $request->permission3, $request->permission4]);

        $role->save();

        return response()->json([
            'message' => 'Role updated successfully!',
            'data' => new RoleResource($role)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = $this->role->delete($id);

        return response()->json([
            'message' => 'Role deleted successfully!'
        ], 200);
    }
}
