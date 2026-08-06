@extends('layouts.app')

@section('title', 'จัดการหมวดหมู่')
@section('page_title', 'ระบบจัดการหมวดหมู่ (Category Management)')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-blue-900 border-l-4 border-blue-600 pl-3">แฟ้มหมวดหมู่ระบบ</h2>
        <p class="text-sm text-slate-500 mt-1 pl-4">จัดการและดูแลหมวดหมู่สำหรับการจัดซื้อ ICT</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
    <!-- Left Col: Categories Form (Hospital Style - Left Sidebar for data entry) -->
    <div class="xl:col-span-1">
        @if(Auth::user()->procurement_role === 'admin')
            <div class="bg-white border-t-4 border-t-blue-600 shadow-md rounded-b-lg overflow-hidden">
                <div class="bg-slate-50 px-4 py-3 border-b border-slate-200">
                    <h3 class="text-sm font-bold text-blue-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        บันทึกหมวดหมู่ใหม่
                    </h3>
                </div>
                
                <form action="{{ route('categories.store') }}" method="POST" class="p-4 space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">ชื่อหมวดหมู่ <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required placeholder="ระบุชื่อหมวดหมู่" class="w-full bg-white border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-sm text-slate-800 shadow-sm transition-shadow">
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 mb-1">รายละเอียด</label>
                        <textarea name="description" id="description" rows="3" placeholder="ระบุรายละเอียดเพิ่มเติม" class="w-full bg-white border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-sm text-slate-800 shadow-sm transition-shadow"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow-md flex justify-center items-center gap-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-bold">จำกัดสิทธิ์</p>
                        <p class="text-xs text-yellow-600 mt-1">เฉพาะผู้ดูแลระบบ (Admin) เท่านั้นที่สามารถเพิ่มหมวดหมู่ได้</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Col: Categories Data Table (Hospital Style) -->
    <div class="xl:col-span-3">
        <div class="bg-white border border-slate-300 shadow-md rounded-lg overflow-hidden">
            <div class="bg-blue-800 px-4 py-3 flex justify-between items-center text-white border-b-4 border-blue-900">
                <h3 class="text-sm font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    ตารางข้อมูลหมวดหมู่
                </h3>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-900 text-blue-100 text-xs font-bold border border-blue-700 shadow-inner">
                    ทั้งหมด {{ $categories->count() }} รายการ
                </span>
            </div>

            @if($categories->isEmpty())
                <div class="p-8 text-center bg-slate-50">
                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-slate-500 text-sm font-semibold">ยังไม่มีประวัติการบันทึกข้อมูล</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-blue-50 border-b border-blue-200 text-blue-900 text-xs uppercase tracking-wide">
                                <th class="py-3 px-4 font-bold text-center border-r border-blue-200 w-16">ลำดับ</th>
                                <th class="py-3 px-4 font-bold border-r border-blue-200">ชื่อหมวดหมู่ (รหัส)</th>
                                <th class="py-3 px-4 font-bold border-r border-blue-200 w-64">รายละเอียด</th>
                                <th class="py-3 px-4 font-bold text-center border-r border-blue-200 w-28">สถิติคำขอ</th>
                                <th class="py-3 px-4 font-bold text-center border-r border-blue-200 w-28">สถานะ</th>
                                <th class="py-3 px-4 font-bold text-center w-32">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($categories as $index => $category)
                                <tr class="border-b border-slate-200 hover:bg-blue-50/50 transition-colors {{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/30' }}">
                                    <td class="py-3 px-4 text-center font-bold text-slate-500 border-r border-slate-200">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 border-r border-slate-200">
                                        <div class="font-bold text-slate-800">{{ $category->name }}</div>
                                        <div class="text-[11px] font-mono text-blue-600 mt-0.5">{{ $category->slug }}</div>
                                    </td>
                                    <td class="py-3 px-4 border-r border-slate-200">
                                        @if($category->description)
                                            <p class="text-xs text-slate-600">{{ $category->description }}</p>
                                        @else
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center border-r border-slate-200">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold {{ $category->procurement_requests_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $category->procurement_requests_count }} รายการ
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center border-r border-slate-200">
                                        @if($category->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> ปกติ
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> ระงับ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if(Auth::user()->procurement_role === 'admin')
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" onclick="openEditCategory({{ $category->id }})" class="p-1.5 text-blue-600 hover:bg-blue-100 border border-transparent hover:border-blue-200 rounded transition-colors shadow-sm" title="แก้ไขข้อมูล">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบข้อมูลหมวดหมู่? ข้อมูลที่เกี่ยวข้องอาจได้รับผลกระทบ')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-100 border border-transparent hover:border-red-200 rounded transition-colors shadow-sm" title="ลบข้อมูล">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 font-medium">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                @if(Auth::user()->procurement_role === 'admin')
                                <div id="edit-category-modal-{{ $category->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm" onclick="if(event.target===this) closeEditCategory({{ $category->id }})">
                                    <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-fadeIn border-t-4 border-t-blue-600">
                                        <div class="px-5 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                                            <h3 class="text-base font-bold text-blue-900 flex items-center gap-2">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                แก้ไขแฟ้มข้อมูล
                                            </h3>
                                            <button onclick="closeEditCategory({{ $category->id }})" class="text-slate-400 hover:text-slate-700 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="p-5 space-y-5">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">ชื่อหมวดหมู่ <span class="text-red-500">*</span></label>
                                                <input type="text" name="name" value="{{ $category->name }}" required class="w-full bg-white border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-sm text-slate-800 shadow-sm transition-shadow">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">รายละเอียด</label>
                                                <textarea name="description" rows="3" class="w-full bg-white border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2 text-sm text-slate-800 shadow-sm transition-shadow">{{ $category->description }}</textarea>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded border border-slate-200">
                                                <label class="flex items-center gap-3 cursor-pointer">
                                                    <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="w-5 h-5 rounded text-blue-600 border-slate-300 focus:ring-blue-500">
                                                    <div>
                                                        <span class="block text-sm font-bold text-slate-800">สถานะเปิดใช้งาน (Active)</span>
                                                        <span class="block text-xs text-slate-500">หากปิดใช้งาน หมวดหมู่นี้จะไม่แสดงในแบบฟอร์มขอจัดซื้อใหม่</span>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="flex gap-3 pt-3 border-t border-slate-100">
                                                <button type="button" onclick="closeEditCategory({{ $category->id }})" class="flex-1 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded shadow-sm border border-slate-300 transition-colors">ยกเลิก</button>
                                                <button type="submit" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow-md flex justify-center items-center gap-2 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                                    บันทึกการแก้ไข
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn { animation: fadeIn 0.2s ease-out; }
</style>

<script>
    function openEditCategory(id) {
        const modal = document.getElementById('edit-category-modal-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }
    function closeEditCategory(id) {
        const modal = document.getElementById('edit-category-modal-' + id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>

@endsection
