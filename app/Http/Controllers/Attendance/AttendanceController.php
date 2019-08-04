<?php

namespace App\Http\Controllers\Attendance;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;
use App\Model\Attendance\Attendance;
use App\Http\Controllers\Controller;
use App\Http\Resources\Attendance\{AttendanceResource, AttendanceCollection};
use App\Repositories\Contracts\Attendance\AttendanceRepository;
use App\Repositories\Contracts\Employee\EmployeeRepository;

class AttendanceController extends Controller
{
    protected $attendance;

    protected $employee;

    public function __construct(AttendanceRepository $attendance, EmployeeRepository $employee)
    {
        $this->attendance = $attendance;

        $this->employee = $employee;
    }

    public function clockIn(Request $request)
    {
        $employee = Attendance::where('employee_id', auth()->user()->id)->where('created_at', '>', Carbon::parse('-20 hours'))->first();

        $location = geoip($ip = $request->ip());

        if (!$employee)
        {
            $attendance = $this->attendance->create([
                'employee_id' => auth()->user()->id,
                'date' => now(),
                'time_in' => now(),
                'month' => Carbon::now()->month,
                'year' => Carbon::now()->year,
                'location' => $location->city,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'message' => 'You clocked In successfully!'
            ]);
        }

        return response()->json([
            'message' => 'You already clocked In today'
        ]);
    }

    public function clockout(Request $request, $employee_code)
    {
        $employee = $this->employee->findWhereFirst('employee_code', $employee_code);

        $attendance = Attendance::where('employee_id', $employee->id)->where('time_out', null)->first();

        if ($attendance)
        {
            $attendance->employee_id = auth()->user()->id;
            $attendance->time_out = now();
    
            $attendance->save();

            return response()->json([
                'message' => 'You clocked out successfully!'
            ]);
        }

        return response()->json([
            'message' => 'You already clocked out today'
        ]);
    }

    public function adminViewEmployeeAttendance($employee_code)
    {
        try {
            $employeeAttendance = $this->employee->findWhereFirst('employee_code', $employee_code);

            return response()->json([
                'message' => new AttendanceCollection($employeeAttendance->attendance)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function adminViewAllAttendance()
    {
        try {

            $this->authorize('view', Attendance::class);

            $attendance = $this->attendance->all();

            return response()->json([
            'message' => new AttendanceCollection($attendance)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function employeeReadOwnAttendance($employee_code)
    {
        try {
            $employeeAttendance = $this->employee->findWhereFirst('employee_code', $employee_code);

            return response()->json([
                'message' => new AttendanceCollection($employeeAttendance->attendance)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getAttendanceByMonth(Request $request)
    {
        try {
            $month = $request->query('month');

            $attendance = $this->attendance->findWhere('month', $month);

            return response()->json([
                'message' => new AttendanceCollection($attendance)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getAttendanceByYear(Request $request)
    {
        try {
            $year = $request->query('year');

            $attendance = $this->attendance->findWhere('year', $year);

            return response()->json([
                'message' => new AttendanceCollection($attendance)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
