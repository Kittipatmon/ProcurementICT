@extends('layouts.app')

@section('title', 'จัดการหมวดหมู่')
@section('page_title', 'จัดการหมวดหมู่จัดซื้อ ICT (Categories)')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Categories List -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-bold text-slate-800">รายการหมวดหมู่ทั้งหมด</h3>
                    <span class="px-2.5 py-1 text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full">
                        {{ $categories->count() }} หมวดหมู่
                    </span>
                </div>
                
                @if($categories->isEmpty())
                    <div class="py-16 text-center">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <p class="text-sm font-semibold text-slate-400">ไม่มีข้อมูลหมวดหมู่ในระบบ</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($categories as $category)
                            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-indigo-500/20 hover:bg-white transition-all flex flex-col justify-between space-y-4 shadow-sm">
                                <div class="space-y-2">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-4 h-4 rounded-full shrink-0 shadow-sm border border-white/50" style="background-color: {{ $category->color }}"></div>
                                            <h4 class="font-bold text-slate-700 text-sm line-clamp-2">{{ $category->name }}</h4>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase leading-none shrink-0
                                            {{ $category->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500">{{ $category->description ?? 'ไม่มีคำอธิบาย' }}</p>
                                    <div class="flex items-center gap-3 pt-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Slug:</span>
                                        <code class="text-[10px] font-mono bg-white border border-slate-200 px-2 py-0.5 rounded text-indigo-600">{{ $category->slug }}</code>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs text-slate-500">
                                        <span class="font-bold text-slate-700">{{ $category->procurement_requests_count }}</span> คำขอจัดซื้อ
                                    </span>
                                    <div class="flex items-center gap-2">
                                        @if(Auth::user()->procurement_role === 'admin')
                                            <!-- Edit Button -->
                                            <button type="button" onclick="openEditCategory({{ $category->id }})" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-100 text-[10px] font-bold transition-all" title="แก้ไข">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                                แก้ไข
                                            </button>
                                            <!-- Delete Button -->
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('คุณต้องการลบหมวดหมู่ «{{ $category->name }}» ใช่หรือไม่?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-100 text-[10px] font-bold transition-all" title="ลบ">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                    ลบ
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[10px] text-slate-400 font-bold uppercase">Category</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal for this category -->
                            @if(Auth::user()->procurement_role === 'admin')
                            <div id="edit-category-modal-{{ $category->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm" onclick="if(event.target===this) closeEditCategory({{ $category->id }})">
                                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 p-8 space-y-5 animate-fadeIn">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <h3 class="text-sm font-bold text-slate-800">แก้ไขหมวดหมู่</h3>
                                        <button onclick="closeEditCategory({{ $category->id }})" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
                                    </div>
                                    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ชื่อหมวดหมู่</label>
                                            <input type="text" name="name" value="{{ $category->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">คำอธิบาย</label>
                                            <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">{{ $category->description }}</textarea>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">สี (HEX Color)</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="color" name="color" value="{{ $category->color }}" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer p-0.5">
                                                    <span class="text-xs text-slate-500 font-mono">{{ $category->color }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">สถานะ</label>
                                                <label class="flex items-center gap-2 cursor-pointer mt-2">
                                                    <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                                    <span class="text-xs text-slate-600 font-semibold">เปิดใช้งาน (Active)</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="flex gap-3 pt-2">
                                            <button type="button" onclick="closeEditCategory({{ $category->id }})" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl text-xs transition-all">ยกเลิก</button>
                                            <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-xs transition-all shadow-md">บันทึกการแก้ไข</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right 1 Col: Create Category Form -->
        <div>
            @if(Auth::user()->procurement_role === 'admin')
                <div class="bg-white border border-slate-200 p-8 rounded-3xl space-y-4 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">เพิ่มหมวดหมู่ใหม่</h3>
                    
                    <form action="{{ route('categories.store') }}" method="POST" class="space-y-4 pt-2">
                        @csrf
                        
                        <div>
                            <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ชื่อหมวดหมู่</label>
                            <input type="text" name="name" id="name" required placeholder="เช่น Printer (เครื่องพิมพ์)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="description" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">คำอธิบาย</label>
                            <textarea name="description" id="description" rows="3" placeholder="อธิบายประเภทสินค้า/บริการในหมวดนี้..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div>
                            <label for="color" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">สี (HEX Color)</label>
                            <div class="flex items-center gap-3">
                                <input type="color" name="color" id="color" value="#6366f1" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer p-0.5">
                                <span class="text-xs text-slate-500">เลือกสีสำหรับแท็กหมวดหมู่</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-xs transition-all shadow-md">
                            บันทึกหมวดหมู่ใหม่
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
                    <p class="text-xs text-slate-400 text-center">สิทธิ์การใช้งานของคุณไม่สามารถจัดการหมวดหมู่ได้ (เฉพาะผู้ดูแลระบบ)</p>
                </div>
            @endif
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
