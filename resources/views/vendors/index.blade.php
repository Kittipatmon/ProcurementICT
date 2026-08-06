@extends('layouts.app')

@section('title', 'ทำเนียบผู้ขาย')
@section('page_title', 'รายชื่อผู้ขายและผู้รับจ้าง (Vendors)')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Vendors List (Hospital-style dense grid) -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="mb-2 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-blue-900 border-l-4 border-blue-600 pl-3">ทำเนียบผู้ขาย</h2>
                    <p class="text-sm text-slate-500 mt-1 pl-4">ข้อมูลบริษัทผู้ขายอุปกรณ์และผู้รับจ้างบริการทางด้าน ICT</p>
                </div>
            </div>

            <div class="bg-white border border-slate-300 shadow-md rounded-lg overflow-hidden">
                <div class="bg-blue-800 px-4 py-3 flex items-center text-white border-b-4 border-blue-900">
                    <h3 class="text-sm font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        รายชื่อบริษัทผู้ขายอุปกรณ์/บริการ
                    </h3>
                </div>
                
                @if($vendors->isEmpty())
                    <div class="p-8 text-center bg-slate-50">
                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <p class="text-slate-500 text-sm font-semibold">ไม่มีข้อมูลผู้ให้บริการในระบบ</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-blue-50 text-blue-900 border-b-2 border-blue-200 uppercase tracking-wide">
                                    <th class="border-r border-blue-200 py-2.5 px-3 font-bold">ชื่อบริษัท / ผู้ติดต่อ</th>
                                    <th class="border-r border-blue-200 py-2.5 px-3 font-bold">เบอร์โทรศัพท์ / อีเมล</th>
                                    <th class="border-r border-blue-200 py-2.5 px-2 text-center font-bold">คะแนน</th>
                                    <th class="border-r border-blue-200 py-2.5 px-2 text-center font-bold">สถานะ</th>
                                    <th class="py-2.5 px-3 text-center font-bold">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($vendors as $vendor)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <!-- ชื่อบริษัท / ผู้ติดต่อ -->
                                        <td class="border border-slate-200 py-2.5 px-3 font-semibold text-slate-900">
                                            <span class="block text-xs">{{ $vendor->vendor_name }}</span>
                                            <span class="block text-[10px] text-slate-500 font-normal mt-0.5">ผู้ประสานงาน: {{ $vendor->contact_name ?? 'ไม่ระบุ' }}</span>
                                            @if($vendor->tax_id)
                                                <span class="block text-[9px] text-slate-400 font-mono mt-0.5">TAX ID: {{ $vendor->tax_id }}</span>
                                            @endif
                                        </td>
                                        
                                        <!-- เบอร์โทรศัพท์ / อีเมล -->
                                        <td class="border border-slate-200 py-2.5 px-3 text-slate-600 font-mono text-[11px]">
                                            <span class="block">โทร: {{ $vendor->phone ?? 'ไม่ระบุ' }}</span>
                                            <span class="block text-[10px]">เมล: {{ $vendor->email ?? 'ไม่ระบุ' }}</span>
                                        </td>
                                        
                                        <!-- คะแนน -->
                                        <td class="border border-slate-200 py-2.5 px-2 text-center font-semibold text-slate-800">
                                            {{ number_format($vendor->rating ?? 0.0, 1) }}
                                        </td>
                                        
                                        <!-- สถานะ -->
                                        <td class="border border-slate-200 py-2.5 px-2 text-center whitespace-nowrap">
                                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded uppercase border {{ $vendor->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                                                {{ $vendor->status }}
                                            </span>
                                        </td>
                                        
                                        <!-- การจัดการ -->
                                        <td class="border border-slate-200 py-2.5 px-3 text-center whitespace-nowrap">
                                            @if(in_array(Auth::user()->procurement_role, ['admin', 'procurement']))
                                                <div class="flex items-center justify-center gap-2">
                                                    <!-- Edit Button -->
                                                    <button type="button" title="แก้ไข" onclick="openEditVendor({{ $vendor->id }})" class="w-7 h-7 rounded bg-white border border-slate-200 shadow-sm flex items-center justify-center text-blue-600 hover:bg-blue-50 hover:border-blue-300 transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                    <!-- Delete Button -->
                                                    <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="inline" onsubmit="return confirm('คุณต้องการลบผู้ขาย «{{ $vendor->vendor_name }}» ใช่หรือไม่?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="ลบ" class="w-7 h-7 rounded bg-white border border-slate-200 shadow-sm flex items-center justify-center text-rose-500 hover:bg-rose-50 hover:border-rose-300 transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">IT Supplier</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal for this vendor -->
                                    @if(in_array(Auth::user()->procurement_role, ['admin', 'procurement']))
                                    <div id="edit-vendor-modal-{{ $vendor->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm" onclick="if(event.target===this) closeEditVendor({{ $vendor->id }})">
                                        <div class="bg-white rounded-lg border border-slate-200 w-full max-w-lg mx-4 p-6 space-y-4 animate-fadeIn">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                <h3 class="text-sm font-bold text-slate-900">แก้ไขข้อมูลผู้ขาย</h3>
                                                <button onclick="closeEditVendor({{ $vendor->id }})" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
                                            </div>
                                            <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ชื่อบริษัทผู้จัดจำหน่าย</label>
                                                    <input type="text" name="vendor_name" value="{{ $vendor->vendor_name }}" required class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ชื่อผู้ประสานงานหลัก</label>
                                                    <input type="text" name="contact_name" value="{{ $vendor->contact_name }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">เบอร์โทรศัพท์</label>
                                                        <input type="text" name="phone" value="{{ $vendor->phone }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">อีเมล</label>
                                                        <input type="email" name="email" value="{{ $vendor->email }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-3 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">เลขที่ผู้เสียภาษี</label>
                                                        <input type="text" name="tax_id" value="{{ $vendor->tax_id }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">คะแนนประเมิน (0-5)</label>
                                                        <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ $vendor->rating }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">สถานะ</label>
                                                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                                            <option value="active" {{ $vendor->status === 'active' ? 'selected' : '' }}>Active (เปิดใช้งาน)</option>
                                                            <option value="inactive" {{ $vendor->status === 'inactive' ? 'selected' : '' }}>Inactive (ระงับ)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ที่ตั้งสำนักงาน</label>
                                                    <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ $vendor->address }}</textarea>
                                                </div>
                                                <div class="flex gap-3 pt-2">
                                                    <button type="button" onclick="closeEditVendor({{ $vendor->id }})" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded text-xs transition-colors">ยกเลิก</button>
                                                    <button type="submit" class="flex-1 py-2 bg-blue-700 hover:bg-blue-600 text-white font-bold rounded text-xs transition-colors shadow-sm">บันทึกการแก้ไข</button>
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

        <!-- Right 1 Col: Create Vendor Form -->
        <div>
            @if(in_array(Auth::user()->procurement_role, ['admin', 'procurement']))
                <div class="bg-white border border-slate-200 p-6 rounded-lg space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">เพิ่มข้อมูลผู้ขายใหม่</h3>
                    
                    <form action="{{ route('vendors.store') }}" method="POST" class="space-y-4 pt-2">
                        @csrf
                        
                        <div>
                            <label for="vendor_name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ชื่อบริษัทผู้จัดจำหน่าย</label>
                            <input type="text" name="vendor_name" id="vendor_name" required placeholder="เช่น บริษัท เอ็มไอที โซลูชั่น จำกัด" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="contact_name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ชื่อผู้ประสานงานหลัก</label>
                            <input type="text" name="contact_name" id="contact_name" placeholder="คุณสมชาย ใจดี" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" id="phone" placeholder="02-123-4567" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">อีเมล</label>
                                <input type="email" name="email" id="email" placeholder="sales@mit.co.th" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tax_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">เลขที่ผู้เสียภาษี</label>
                                <input type="text" name="tax_id" id="tax_id" placeholder="01055xxxxxxxx" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="rating" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">คะแนนประเมิน (0-5)</label>
                                <input type="number" step="0.1" min="0" max="5" name="rating" id="rating" placeholder="5.0" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ที่ตั้งสำนักงาน</label>
                            <textarea name="address" id="address" rows="3" placeholder="เลขที่ ถนน แขวง เขต กรุงเทพฯ..." class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-600 text-white font-bold rounded text-xs transition-colors shadow-sm">
                            บันทึกข้อมูลผู้ขาย
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white border border-slate-200 p-6 rounded-lg">
                    <p class="text-xs text-slate-400 text-center">สิทธิ์การใช้งานของคุณไม่สามารถบันทึกผู้จัดจำหน่ายได้ (เฉพาะฝ่ายจัดซื้อหรือผู้ดูแลระบบ)</p>
                </div>
            @endif
        </div>

    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fadeIn { animation: fadeIn 0.15s ease-out; }
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
