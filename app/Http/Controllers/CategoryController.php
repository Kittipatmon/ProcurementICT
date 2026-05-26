<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('procurementRequests')
            ->orderBy('name', 'asc')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->procurement_role !== 'admin') {
            return back()->with('error', 'เฉพาะผู้ดูแลระบบเท่านั้นที่สามารถจัดการหมวดหมู่ได้');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        // Auto-generate slug from name
        $slug = Str::slug($validated['name']);
        // If slug already exists, append a number
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'color' => $validated['color'] ?? '#6366f1',
            'is_active' => true,
        ]);

        return back()->with('success', 'บันทึกหมวดหมู่ใหม่สำเร็จ');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->procurement_role !== 'admin') {
            return back()->with('error', 'เฉพาะผู้ดูแลระบบเท่านั้นที่สามารถจัดการหมวดหมู่ได้');
        }

        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'color' => $validated['color'] ?? $category->color,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'อัปเดตหมวดหมู่สำเร็จ');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->procurement_role !== 'admin') {
            return back()->with('error', 'เฉพาะผู้ดูแลระบบเท่านั้นที่สามารถจัดการหมวดหมู่ได้');
        }

        $category = Category::findOrFail($id);

        // Check if category is being used
        $usageCount = $category->procurementRequests()->count();
        if ($usageCount > 0) {
            return back()->with('error', "ไม่สามารถลบหมวดหมู่นี้ได้ เนื่องจากมีคำขอจัดซื้อ {$usageCount} รายการที่ใช้หมวดหมู่นี้อยู่");
        }

        $category->delete();

        return back()->with('success', 'ลบหมวดหมู่สำเร็จ');
    }
}
