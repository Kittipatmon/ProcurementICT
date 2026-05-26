<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (in_array($user->procurement_role, ['admin', 'executive', 'cao'])) {
            $budgets = Budget::with('department')->where('fiscal_year', 2026)->get();
        } else {
            $budgets = Budget::with('department')
                ->where('department_id', $user->dept_id)
                ->where('fiscal_year', 2026)
                ->get();
        }

        $departments = Department::all();
        return view('budgets.index', compact('budgets', 'departments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->procurement_role !== 'admin' && $user->procurement_role !== 'cao') {
            return back()->with('error', 'เฉพาะผู้ดูแลระบบหรือผู้อนุมัติงบประมาณเท่านั้นที่ปรับแก้ได้');
        }

        $validated = $request->validate([
            'department_id' => 'required|exists:mysql_user.departments,id',
            'fiscal_year' => 'required|integer',
            'allocated_budget' => 'required|numeric|min:0',
            'name' => 'nullable|string|max:255',
        ]);

        if ($request->input('is_edit') === '1' && $request->input('budget_id')) {
            $budget = Budget::findOrFail($request->input('budget_id'));
            $budget->department_id = $validated['department_id'];
            $budget->fiscal_year = $validated['fiscal_year'];
            $budget->name = $validated['name'];
            $budget->allocated_budget = $validated['allocated_budget'];
        } else {
            $budget = new Budget();
            $budget->department_id = $validated['department_id'];
            $budget->fiscal_year = $validated['fiscal_year'];
            $budget->name = $validated['name'];
            $budget->allocated_budget = $validated['allocated_budget'];
            $budget->used_budget = 0;
        }

        // We will calculate remaining_budget below. For now, let's just save.
        $budget->save();

        // recalculate remaining
        $budget->remaining_budget = $budget->allocated_budget - $budget->used_budget;
        $budget->save();

        return back()->with('success', 'ปรับปรุงงบประมาณเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->procurement_role !== 'admin' && $user->procurement_role !== 'cao') {
            return back()->with('error', 'เฉพาะผู้ดูแลระบบหรือผู้อนุมัติงบประมาณเท่านั้นที่ลบได้');
        }

        $budget = Budget::findOrFail($id);
        
        if ($budget->used_budget > 0) {
            return back()->with('error', 'ไม่สามารถลบงบประมาณนี้ได้ เนื่องจากมีการใช้ไปแล้วบางส่วน');
        }

        $budget->delete();
        return back()->with('success', 'ลบข้อมูลจัดสรรงบประมาณเรียบร้อยแล้ว');
    }
}
