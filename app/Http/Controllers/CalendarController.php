<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $departmentId = $request->input('department_id');

        $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $departments = Department::orderBy('name')->get();

        $query = LeaveRequest::with(['user.department'])
            ->where('status', 'approved')
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('start_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                  ->orWhereBetween('end_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                  ->orWhere(function ($sub) use ($startOfMonth, $endOfMonth) {
                      $sub->where('start_date', '<=', $startOfMonth->toDateString())
                          ->where('end_date', '>=', $endOfMonth->toDateString());
                  });
            });

        if ($departmentId) {
            $query->whereHas('user', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $approvedLeaves = $query->get();

        return view('calendar.index', compact('approvedLeaves', 'departments', 'month', 'departmentId', 'startOfMonth'));
    }
}
