<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class OrgChartController extends Controller
{
    /**
     * Interactive Organizational Hierarchy Tree View
     */
    public function index()
    {
        $adminHR = User::where('role', 'admin_hr')->first();
        $departments = Department::with(['users' => function ($q) {
            $q->orderBy('salary', 'desc');
        }])->get();

        return view('orgchart.index', compact('adminHR', 'departments'));
    }
}
