<?php

namespace App\Http\Controllers\Department;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\Criteria\IsParent;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Http\Resources\Department\DepartmentResource;
use App\Repositories\Contracts\Department\DepartmentRepository;
use App\Http\Resources\Department\EmployeeDepartmentResource;
use App\Repositories\Contracts\Employee\EmployeeRepository;

class DepartmentController extends Controller
{
    protected $department;

    protected $employee;

    public function __construct(DepartmentRepository $department, EmployeeRepository $employee)
    {
        $this->department = $department;

        $this->employee = $employee;

        $this->middleware('permission:create_departments')->only('store');
        $this->middleware('permission:update_departments')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department = $this->department->withCriteria([
            new IsParent($this->department),
            new EagerLoad(['children'])
        ])->all();

        return response()->json([
            'data' => DepartmentResource::collection($department)
        ]);
    }

    /**
     * Fetch Employees by department
     * 
     * @return \Illuminate\Http\Response
     */
    public function employeeDepartment($slug)
    {
        $department = $this->department->withCriteria([
            new EagerLoad(['employee'])
        ])->findWhereFirst('slug', $slug);

        return response()->json([
            'data' => new EmployeeDepartmentResource($department)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $department = $this->department->create([
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);

        $department->save();

        return response()->json([
            'message' => 'Department created successfully!',
            'data' => new DepartmentResource($department)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $department = $this->department->withCriteria([
            new IsParent($this->department),
            new EagerLoad(['children'])
        ])->findWhere('slug', $slug);

        return response()->json([
            'data' => DepartmentResource::collection($department)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $department = $this->department->findWhereFirst('slug', $slug);

        $department->name = $request->name;
        $department->parent_id = $request->parent_id;

        $department->save();

        return response()->json([
            'message' => 'Department updated successfully!',
            'data' => new DepartmentResource($department)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $department = $this->department->findWhereDelete('slug', $slug);

        return response()->json([
            'message' => 'Department deleted successfully!'
        ], 200);
    }
}
