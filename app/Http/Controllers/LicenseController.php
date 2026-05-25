<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicenseAssignment;
use App\Models\Employee;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::with(['vendor', 'assignments.employee'])->latest()->get();
        $vendors = Vendor::where('status', 'active')->get();
        $employees = Employee::orderBy('firstname')->get();
        return view('licenses.index', compact('licenses', 'vendors', 'employees'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->procurement_role, ['admin', 'ict'])) {
            return back()->with('error', 'ไม่มีสิทธิ์ในการเข้าจัดการส่วนนี้');
        }

        $validated = $request->validate([
            'software_name' => 'required|string|max:255',
            'license_key' => 'nullable|string',
            'license_type' => 'nullable|string|max:100',
            'total_license' => 'required|integer|min:1',
            'purchase_date' => 'nullable|date',
            'expire_date' => 'nullable|date',
            'annual_cost' => 'nullable|numeric|min:0',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        License::create($validated);

        return back()->with('success', 'บันทึกสัญญาอนุญาต (Software License) สำเร็จ');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->procurement_role, ['admin', 'ict'])) {
            return back()->with('error', 'ไม่มีสิทธิ์ในการจัดการสัญญาลิขสิทธิ์');
        }

        $license = License::findOrFail($id);

        $validated = $request->validate([
            'software_name' => 'required|string|max:255',
            'license_key' => 'nullable|string',
            'license_type' => 'nullable|string|max:100',
            'total_license' => 'required|integer|min:1',
            'purchase_date' => 'nullable|date',
            'expire_date' => 'nullable|date',
            'annual_cost' => 'nullable|numeric|min:0',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        $license->update($validated);

        return back()->with('success', 'อัปเดตสัญญาลิขสิทธิ์สำเร็จ');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!in_array($user->procurement_role, ['admin', 'ict'])) {
            return back()->with('error', 'ไม่มีสิทธิ์ในการจัดการสัญญาลิขสิทธิ์');
        }

        $license = License::findOrFail($id);
        $license->delete();

        return back()->with('success', 'ลบสัญญาลิขสิทธิ์สำเร็จ');
    }

    public function assign(Request $request, $id)
    {
        $license = License::findOrFail($id);
        
        if ($license->used_license >= $license->total_license) {
            return back()->with('error', 'จำนวนสิทธิ์การใช้งานของลิขสิทธิ์ซอฟต์แวร์นี้เต็มแล้ว');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:employees,id',
        ]);

        // check if already assigned active
        $exists = LicenseAssignment::where('license_id', $license->id)
            ->where('user_id', $validated['user_id'])
            ->where('status', 'active')
            ->exists();

        if ($exists) {
            return back()->with('error', 'พนักงานท่านนี้ได้รับสิทธิ์ซอฟต์แวร์นี้ไปแล้ว');
        }

        LicenseAssignment::create([
            'license_id' => $license->id,
            'user_id' => $validated['user_id'],
            'assigned_date' => now(),
            'status' => 'active',
        ]);

        // Increment count
        $license->increment('used_license');

        return back()->with('success', 'มอบสิทธิ์การใช้ซอฟต์แวร์ให้พนักงานสำเร็จ');
    }

    public function revoke($assignmentId)
    {
        $assignment = LicenseAssignment::findOrFail($assignmentId);
        if ($assignment->status === 'returned') {
            return back()->with('error', 'สิทธิ์นี้ถูกเพิกถอนไปแล้ว');
        }

        $assignment->update([
            'status' => 'returned',
            'returned_date' => now(),
        ]);

        $license = License::findOrFail($assignment->license_id);
        if ($license->used_license > 0) {
            $license->decrement('used_license');
        }

        return back()->with('success', 'เพิกถอนสิทธิ์การใช้ซอฟต์แวร์เรียบร้อย');
    }
}
