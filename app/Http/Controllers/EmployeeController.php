<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Facades\Datatables;

class EmployeeController extends Controller
{
    public function index()
    {
        $departments = Employee::select('department')
            ->distinct()
            ->orderBy('department')
            ->lists('department');

        return view('employees.index', compact('departments'));
    }

    public function data(Request $request)
    {
        $employees = Employee::select([
            'id',
            'name',
            'email',
            'department',
            'salary',
            'join_date',
            'status',
        ]);

        return Datatables::of($employees)
            ->filter(function ($query) use ($request) {
                if ($request->get('department')) {
                    $query->where('department', $request->get('department'));
                }
            })
            ->editColumn('salary', function ($employee) {
                return 'Rs. ' . number_format($employee->salary, 2);
            })
            ->editColumn('join_date', function ($employee) {
                return $employee->join_date ? $employee->join_date->format('d M Y') : '';
            })
            ->editColumn('status', function ($employee) {
                $class = $employee->status === 'active' ? 'bg-success' : 'bg-secondary';

                return '<span class="badge ' . $class . '">' . ucfirst($employee->status) . '</span>';
            })
            ->addColumn('action', function ($employee) {
                return '<div class="btn-group btn-group-sm" role="group">' .
                    '<button type="button" class="btn btn-outline-primary edit-employee" data-id="' . $employee->id . '">Edit</button>' .
                    '<button type="button" class="btn btn-outline-danger delete-employee" data-id="' . $employee->id . '">Delete</button>' .
                    '</div>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employee = Employee::create($request->only([
            'name',
            'email',
            'department',
            'salary',
            'join_date',
            'status',
        ]));

        return response()->json([
            'message' => 'Employee added successfully.',
            'employee' => $employee,
        ]);
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json([
            'id' => $employee->id,
            'name' => $employee->name,
            'email' => $employee->email,
            'department' => $employee->department,
            'salary' => $employee->salary,
            'join_date' => $employee->join_date ? $employee->join_date->format('Y-m-d') : '',
            'status' => $employee->status,
        ]);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $validator = $this->validator($request, $employee->id);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employee->update($request->only([
            'name',
            'email',
            'department',
            'salary',
            'join_date',
            'status',
        ]));

        return response()->json([
            'message' => 'Employee updated successfully.',
            'employee' => $employee,
        ]);
    }

    public function destroy($id)
    {
        Employee::findOrFail($id)->delete();

        return response()->json(['message' => 'Employee deleted successfully.']);
    }

    public function stats()
    {
        return response()->json([
            'total' => Employee::count(),
            'active' => Employee::where('status', 'active')->count(),
            'inactive' => Employee::where('status', 'inactive')->count(),
            'departments' => Employee::select('department')->distinct()->count('department'),
        ]);
    }

    private function validator(Request $request, $employeeId = null)
    {
        $emailRule = 'required|email|unique:employees,email';

        if ($employeeId) {
            $emailRule .= ',' . $employeeId;
        }

        return Validator::make($request->all(), [
            'name' => 'required|max:120',
            'email' => $emailRule,
            'department' => 'required|max:80',
            'salary' => 'required|numeric|min:0',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);
    }
}
