@extends('layouts.app')

@section('title', 'ทำเนียบผู้ขาย')
@section('page_title', 'รายชื่อผู้ขายและผู้รับจ้าง (Vendors)')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Vendors List -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-6">รายชื่อบริษัทผู้ขายอุปกรณ์/บริการ</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($vendors as $vendor)
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-indigo-500/20 hover:bg-white transition-all flex flex-col justify-between space-y-4 shadow-sm">
                            <div class="space-y-2">
                                <div class="flex items-start justify-between">
                                    <h4 class="font-bold text-slate-700 text-sm line-clamp-2">{{ $vendor->vendor_name }}</h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase leading-none shrink-0 {{ $vendor->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }} border">
                                        {{ $vendor->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500">ผู้ประสานงาน: {{ $vendor->contact_name ?? 'ไม่ระบุ' }}</p>
                                <p class="text-xs text-slate-500">เบอร์ติดต่อ: {{ $vendor->phone ?? 'ไม่ระบุ' }}</p>
                                <p class="text-xs text-slate-500">อีเมล: {{ $vendor->email ?? 'ไม่ระบุ' }}</p>
                                @if($vendor->tax_id)
                                    <p class="text-[10px] text-slate-400 font-bold">เลขผู้เสียภาษี: {{ $vendor->tax_id }}</p>
                                @endif
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-400 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-xs font-bold text-slate-700">{{ number_format($vendor->rating ?? 0.0, 1) }} / 5.0</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(in_array(Auth::user()->procurement_role, ['admin', 'procurement']))
                                        <!-- Edit Button -->
                                        <button type="button" onclick="openEditVendor({{ $vendor->id }})" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-100 text-[10px] font-bold transition-all" title="แก้ไข">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                            แก้ไข
                                        </button>
                                        <!-- Delete Button -->
                                        <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="inline" onsubmit="return confirm('คุณต้องการลบผู้ขาย «{{ $vendor->vendor_name }}» ใช่หรือไม่?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-100 text-[10px] font-bold transition-all" title="ลบ">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                ลบ
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">IT Supplier</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal for this vendor -->
                        @if(in_array(Auth::user()->procurement_role, ['admin', 'procurement']))
                        <div id="edit-vendor-modal-{{ $vendor->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm" onclick="if(event.target===this) closeEditVendor({{ $vendor->id }})">
                            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 p-8 space-y-5 animate-fadeIn">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <h3 class="text-sm font-bold text-slate-800">แก้ไขข้อมูลผู้ขาย</h3>
                                    <button onclick="closeEditVendor({{ $vendor->id }})" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
                                </div>
                                <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ชื่อบริษัทผู้จัดจำหน่าย</label>
                                        <input type="text" name="vendor_name" value="{{ $vendor->vendor_name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ชื่อผู้ประสานงานหลัก</label>
                                        <input type="text" name="contact_name" value="{{ $vendor->contact_name }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">เบอร์โทรศัพท์</label>
                                            <input type="text" name="phone" value="{{ $vendor->phone }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">อีเมล</label>
                                            <input type="email" name="email" value="{{ $vendor->email }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">เลขที่ผู้เสียภาษี</label>
                                            <input type="text" name="tax_id" value="{{ $vendor->tax_id }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">คะแนนประเมิน (0-5)</label>
                                            <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ $vendor->rating }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">สถานะ (Status)</label>
                                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                                <option value="active" {{ $vendor->status === 'active' ? 'selected' : '' }}>Active (เปิดใช้งาน)</option>
                                                <option value="inactive" {{ $vendor->status === 'inactive' ? 'selected' : '' }}>Inactive (ระงับ)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ที่ตั้งสำนักงาน</label>
                                        <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">{{ $vendor->address }}</textarea>
                                    </div>
                                    <div class="flex gap-3 pt-2">
                                        <button type="button" onclick="closeEditVendor({{ $vendor->id }})" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl text-xs transition-all">ยกเลิก</button>
                                        <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-xs transition-all shadow-md">บันทึกการแก้ไข</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Create Vendor Form -->
        <div>
            @if(in_array(Auth::user()->procurement_role, ['admin', 'procurement']))
                <div class="bg-white border border-slate-200 p-8 rounded-3xl space-y-4 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">เพิ่มข้อมูลผู้ขายใหม่</h3>
                    
                    <form action="{{ route('vendors.store') }}" method="POST" class="space-y-4 pt-2">
                        @csrf
                        
                        <div>
                            <label for="vendor_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ชื่อบริษัทผู้จัดจำหน่าย</label>
                            <input type="text" name="vendor_name" id="vendor_name" required placeholder="เช่น บริษัท เอ็มไอที โซลูชั่น จำกัด" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="contact_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ชื่อผู้ประสานงานหลัก</label>
                            <input type="text" name="contact_name" id="contact_name" placeholder="คุณสมชาย ใจดี" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" id="phone" placeholder="02-123-4567" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">อีเมล</label>
                                <input type="email" name="email" id="email" placeholder="sales@mit.co.th" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tax_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">เลขที่ผู้เสียภาษี</label>
                                <input type="text" name="tax_id" id="tax_id" placeholder="01055xxxxxxxx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="rating" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">คะแนนประเมิน (0-5)</label>
                                <input type="number" step="0.1" min="0" max="5" name="rating" id="rating" placeholder="5.0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ที่ตั้งสำนักงาน</label>
                            <textarea name="address" id="address" rows="3" placeholder="เลขที่ ถนน แขวง เขต กรุงเทพฯ..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-xs transition-all shadow-md">
                            บันทึกข้อมูลผู้ขาย
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
                    <p class="text-xs text-slate-400 text-center">สิทธิ์การใช้งานของคุณไม่สามารถบันทึกผู้จัดจำหน่ายได้ (เฉพาะฝ่ายจัดซื้อหรือผู้ดูแลระบบ)</p>
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
        function openEditVendor(id) {
            const modal = document.getElementById('edit-vendor-modal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        function closeEditVendor(id) {
            const modal = document.getElementById('edit-vendor-modal-' + id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>

@endsection
