<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSalary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function salary()
    {
        $pageTitle = 'Manage Salary System';
        $salaries = UserSalary::latest()->paginate(getPaginate());
        return view('admin.salary.index', compact('pageTitle', 'salaries'));
    }

    public function index()
    {
        $pageTitle = 'Manage Salary System';
        $salaries = UserSalary::latest()->paginate(getPaginate());
        return view('admin.salary.index', compact('pageTitle', 'salaries'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'min_turnover' => 'required|numeric|gte:0',
            'max_turnover' => 'required|numeric|gt:min_turnover',
            'amount' => 'required|numeric|gt:0',
        ]);

        $salary = new UserSalary();
        $salary->name = $request->name;
        $salary->min_turnover = $request->min_turnover;
        $salary->max_turnover = $request->max_turnover;
        $salary->amount = $request->amount;
        $salary->save();

        $notify[] = ['success', 'Salary level added successfully'];
        return back()->withNotify($notify);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'min_turnover' => 'required|numeric|gte:0',
            'max_turnover' => 'required|numeric|gt:min_turnover',
            'amount' => 'required|numeric|gt:0',
        ]);

        $salary = new UserSalary();
        $salary->name = $request->name;
        $salary->min_turnover = $request->min_turnover;
        $salary->max_turnover = $request->max_turnover;
        $salary->amount = $request->amount;
        $salary->save();

        $notify[] = ['success', 'Salary level added successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'min_turnover' => 'required|numeric|gte:0',
            'max_turnover' => 'required|numeric|gt:min_turnover',
            'amount' => 'required|numeric|gt:0',
        ]);

        $salary = UserSalary::findOrFail($id);
        $salary->name = $request->name;
        $salary->min_turnover = $request->min_turnover;
        $salary->max_turnover = $request->max_turnover;
        $salary->amount = $request->amount;
        $salary->save();

        $notify[] = ['success', 'Salary level updated successfully'];
        return back()->withNotify($notify);
    }

    public function remove($id)
    {
        $salary = UserSalary::findOrFail($id);
        $salary->delete();
        
        $notify[] = ['success', 'Salary level deleted successfully'];
        return back()->withNotify($notify);
    }
}
