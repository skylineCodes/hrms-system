<?php

namespace App\Http\Controllers\Salary;

use Exception;
use App\Model\Salary\Salary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Salary\SalaryResource;
use App\Http\Requests\Salary\SalaryFormRequest;
use App\Repositories\Contracts\Salary\SalaryRepository;
use App\Repositories\Contracts\Employee\EmployeeRepository;

class SalaryController extends Controller
{
    public $salary;

    public $employee;

    public function __construct(SalaryRepository $salary, EmployeeRepository $employee)
    {
        $this->salary = $salary;

        $this->employee = $employee;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', Salary::class);

            $salary = $this->salary->all();

            return response()->json([
                'message' => SalaryResource::collection($salary)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalaryFormRequest $request)
    {
        try {

            $this->authorize('create', Salary::class);

            $employee = Salary::where('employee_id', $request->employee_id)->first();

            if (empty($employee)) {
                $salary = $this->salary->create([
                    'employee_id'   => $request->employee_id,
                    'salary_amount' => $request->salary_amount
                ]);

                return response()->json([
                    'message' => 'Salary created successfully!',
                    'data' => new SalaryResource($salary)
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Employee salary already saved, please choose the update route!'
                ]);
            }
        } catch(Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($employee_code)
    {
        try {

            $this->authorize('view', Salary::class);

            $employee = $this->employee->findWhereFirst('employee_code', $employee_code);

            $salary = $this->salary->findWhereFirst('employee_id', $employee->id);

            return response()->json([
            'data' => new SalaryResource($salary)
        ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
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
        try {
            
            $this->authorize('update', Salary::class);

            $employee = $this->employee->findWhereFirst('employee_code', $employee_code);

            $employee->salary->salary_amount = $request->salary_amount;

            $employee->salary->save();

            return response()->json([
            'message' => new SalaryResource($employee->salary)
        ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($employee_code)
    {        
        try {

            $this->authorize('forceDelete', Salary::class);

            $employee = $this->employee->findWhereFirst('employee_code', $employee_code);

            $salary = $this->salary->permanentDelete('employee_id', $employee->id);

            return response()->json([
                'message' => 'Employee salary deleted successfully!'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
