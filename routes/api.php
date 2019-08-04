<?php
/**
 * Admin Authentication - Middleware
 */
Route::post('/admin/register', 'Admin\AdminAuthController@register')->middleware('auth:adminapi');
Route::post('/admin/login/code', 'Admin\AdminAuthController@codeLogin');
Route::post('/admin/login/email', 'Admin\AdminAuthController@emailLogin');
Route::post('/admin/logout', 'Admin\AdminAuthController@logout')->middleware('auth:adminapi');
Route::patch('/admin/password/{employee_code}', 'Admin\AdminAuthController@password');

/**
 * Admin Profile - Middleware
 */
Route::patch('/admin/email/{employee_code}', 'Admin\ProfileController@emailUpdate')->middleware('auth:adminapi');
Route::patch('/admin/profile/{employee_code}', 'Admin\ProfileController@update')->middleware('auth:adminapi');
Route::get('/admin/email/{employee_code}', 'Admin\ProfileController@emailConfirm');

/**
 * Acl - Middleware
 */
Route::post('/role/store', 'Acl\RoleController@store')->middleware('auth:adminapi');
Route::post('/user/{id}/assign', 'Acl\RoleController@assignRoleToUser')->middleware('auth:adminapi');
Route::get('/roles', 'Acl\RoleController@index')->middleware('auth:adminapi');
Route::get('/role/{id}', 'Acl\RoleController@show')->middleware('auth:adminapi');
Route::patch('/role/{id}', 'Acl\RoleController@update')->middleware('auth:adminapi');
Route::delete('role/{id}', 'Acl\RoleController@destroy')->middleware('auth:adminapi');
Route::get('/user/role/{id}', 'Acl\RoleController@userRole')->middleware('auth:adminapi');

/**
 * Department - Middleware
 */
Route::post('/department/store', 'Department\DepartmentController@store')->middleware('auth:adminapi');
Route::put('/department/{slug}', 'Department\DepartmentController@update')->middleware('auth:adminapi');
Route::get('/department', 'Department\DepartmentController@index')->middleware('auth:adminapi');
Route::get('/department/{slug}', 'Department\DepartmentController@show')->middleware('auth:adminapi');
Route::delete('/department/{slug}', 'Department\DepartmentController@destroy')->middleware('auth:adminapi');
Route::get('/employee/department/{slug}', 'Department\DepartmentController@employeeDepartment')->middleware('auth:adminapi');

/**
 * Employee Authentication - Middleware
 */
Route::post('/employee/store', 'Employee\EmployeeAuthController@store')->middleware('auth:adminapi');
Route::patch('/employee/password/{employee_code}', 'Employee\EmployeeAuthController@password');
Route::post('/employee/login/code', 'Employee\EmployeeAuthController@codeLogin');
Route::post('/employee/login/email', 'Employee\EmployeeAuthController@emailLogin');

/**
 * Forgot-Password Routes (Employee) - Middleware
 */
Route::post('/employee/password/create', 'Employee\EmployeeAuthController@create');
Route::post('/employee/password/reset', 'Employee\EmployeeAuthController@reset');

/**
 * Employee Profile - Middleware
 */
Route::patch('/hr/employee/{employee_code}', 'Employee\EmployeeController@update')->middleware('auth:adminapi');
Route::patch('/employee/{employee_code}', 'Employee\EmployeeController@employeeUpdate')->middleware('auth:employeeapi');
Route::post('/reactivate/{employee_code}', 'Employee\EmployeeController@reactivate')->middleware('auth:adminapi');
Route::post('/deactivate/{employee_code}', 'Employee\EmployeeController@deactivate')->middleware('auth:adminapi');
Route::get('/employee/{employee_code}', 'Employee\EmployeeController@show');
Route::get('/employees', 'Employee\EmployeeController@index')->middleware('auth:adminapi');
Route::get('/employees/deactivated', 'Employee\EmployeeController@deactivatedEmployees')->middleware('auth:adminapi');
Route::delete('/employee/{employee_code}', 'Employee\EmployeeController@destroy')->middleware('auth:adminapi');

/**
 * Leave Type - Middleware
 */
Route::post('/leave/type', 'Leave\LeaveTypeController@store')->middleware('auth:adminapi');
Route::get('/leave/type', 'Leave\LeaveTypeController@index');
Route::patch('/leave/type/{id}', 'Leave\LeaveTypeController@update')->middleware('auth:adminapi');
Route::delete('leave/type/{id}', 'Leave\LeaveTypeController@destroy')->middleware('auth:adminapi');

/**
 * Leave - Middleware
 */
Route::post('/leave', 'Leave\LeaveController@store')->middleware('auth:employeeapi');
Route::get('/leave/{id}/approve', 'Leave\LeaveController@approve')->middleware('auth:adminapi');
Route::get('/leave/{id}/reject', 'Leave\LeaveController@reject')->middleware('auth:adminapi');
Route::get('/leave/{id}/pending', 'Leave\LeaveController@pending')->middleware('auth:adminapi');
Route::get('/leaves', 'Leave\LeaveController@index')->middleware('auth:adminapi');
Route::get('/leave/{id}', 'Leave\LeaveController@show')->middleware('auth:adminapi');
Route::get('/employee/{employee_code}/leave', 'Leave\LeaveController@employeeLeaveRequest')->middleware('auth:employeeapi');
Route::delete('/employee/{employee_code}/leave/{id}', 'Leave\LeaveController@cancelLeave')->middleware('auth:employeeapi');
Route::delete('/leave/archive/{id}', 'Leave\LeaveController@archiveLeave')->middleware('auth:adminapi');
Route::get('/leaves/archive', 'Leave\LeaveController@fetchArchive')->middleware('auth:adminapi');
Route::get('/leave/restore/{id}', 'Leave\LeaveController@restoreArchive')->middleware('auth:adminapi');
Route::delete('/leave/permanent/{id}', 'Leave\LeaveController@permanentDelete')->middleware('auth:adminapi');

/**
 * Leave Policy - Middleware
 */
Route::get('/policy', 'Leave\LeavePolicyController@index');
Route::get('/policy/{id}', 'Leave\LeavePolicyController@show');
Route::post('/leave/policy', 'Leave\LeavePolicyController@store')->middleware('auth:adminapi');
Route::patch('/leave/policy/{id}', 'Leave\LeavePolicyController@update')->middleware('auth:adminapi');
Route::delete('/leave/policy/{id}', 'Leave\LeavePolicyController@destroy')->middleware('auth:adminapi');

/**
 * Attendance - Middleware
 */
Route::get('/employee/{employee_code}', 'Attendance\AttendanceController@adminViewEmployeeAttendance')->middleware('auth:adminapi');
Route::get('/employee_attendance/{employee_code}', 'Attendance\AttendanceController@employeeReadOwnAttendance')->middleware('auth:employeeapi');
Route::get('/attendance', 'Attendance\AttendanceController@adminViewAllAttendance')->middleware('auth:adminapi');
Route::post('/employee/clock_in', 'Attendance\AttendanceController@clockIn')->middleware('auth:employeeapi');
Route::patch('/employee/{employee_code}/clock_out', 'Attendance\AttendanceController@clockout')->middleware('auth:employeeapi');
Route::get('/attendance/month', 'Attendance\AttendanceController@getAttendanceByMonth')->middleware('auth:adminapi');
Route::get('/attendance/year', 'Attendance\AttendanceController@getAttendanceByYear')->middleware('auth:adminapi');


/**
 * Salary - Middleware
 */
Route::post('/salary', 'Salary\SalaryController@store')->middleware('auth:adminapi', 'auth:employeeapi');
Route::get('/salary', 'Salary\SalaryController@index')->middleware('auth:adminapi');
Route::get('/salary/{employee_code}', 'Salary\SalaryController@show')->middleware('auth:adminapi');
Route::patch('/salary/{employee_code}', 'Salary\SalaryController@update')->middleware('auth:adminapi');
Route::delete('/salary/{employee_code}', 'Salary\SalaryController@destroy')->middleware('auth:adminapi');

/**
 * Payment - Middleware
 */

Route::fallback(function() {
    return response()->json([
        'message' => 'Requested Resource Not Found. If error persists, contact info@recruitjob.co.uk'
    ], 404);
})->name('fallback');
