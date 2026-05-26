<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::orderBy('vendor_name', 'asc')->get();
        return view('vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->procurement_role, ['admin', 'procurement'])) {
            return back()->with('error', 'ไม่มีสิทธิ์ในการจัดการผู้ขาย');
        }

        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'tax_id' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        Vendor::create($validated);

        return back()->with('success', 'บันทึกข้อมูลผู้ขายรายใหม่สำเร็จ');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->procurement_role, ['admin', 'procurement'])) {
            return back()->with('error', 'ไม่มีสิทธิ์ในการจัดการผู้ขาย');
        }

        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'tax_id' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status' => 'nullable|in:active,inactive',
        ]);

        $vendor->update($validated);

        return back()->with('success', 'อัปเดตข้อมูลผู้ขายสำเร็จ');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!in_array($user->procurement_role, ['admin', 'procurement'])) {
            return back()->with('error', 'ไม่มีสิทธิ์ในการจัดการผู้ขาย');
        }

        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return back()->with('success', 'ลบข้อมูลผู้ขายสำเร็จ');
    }
}
