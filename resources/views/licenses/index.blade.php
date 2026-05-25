@extends('layouts.app')

@section('title', 'ลิขสิทธิ์ซอฟต์แวร์')
@section('page_title', 'สัญญาลิขสิทธิ์ซอฟต์แวร์และคลาวด์ (Software Licenses)')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Licenses List & Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-6">บันทึกสัญญาลิขสิทธิ์ซอฟต์แวร์ขององค์กร</h3>
                
                @if($licenses->isEmpty())
                    <p class="text-xs text-slate-400 py-10 text-center">ไม่มีข้อมูลสัญญาซอฟต์แวร์บันทึกในระบบ</p>
                @else
                    <div class="space-y-6">
                        @foreach($licenses as $license)
                            @php
                                $isNearExpiry = $license->expire_date && $license->expire_date->isBefore(now()->addDays(30));
                            @endphp
                            <div class="p-6 rounded-2xl bg-slate-50 border transition-all space-y-4 shadow-sm
                                @if($isNearExpiry) border-amber-500/25 bg-amber-500/[0.02] @else border-slate-200 hover:border-slate-300 hover:bg-white @endif">
                                
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                                            {{ $license->software_name }}
                                            @if($isNearExpiry)
                                                <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-100 leading-none">กำลังหมดอายุ</span>
                                            @endif
                                        </h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">ประเภท: {{ $license->license_type ?? 'ไม่ระบุ' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full border
                                            @if($license->status === 'active') bg-emerald-50 text-emerald-600 border-emerald-100
                                            @else bg-rose-50 text-rose-600 border-rose-100 @endif">
                                            {{ $license->status }}
                                        </span>
                                        @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']))
                                            <!-- Edit Button -->
                                            <button type="button" onclick="openEditLicense({{ $license->id }})" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-100 text-[10px] font-bold transition-all" title="แก้ไข">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                                แก้ไข
                                            </button>
                                            <!-- Delete Button -->
                                            <form action="{{ route('licenses.destroy', $license->id) }}" method="POST" class="inline" onsubmit="return confirm('คุณต้องการลบสัญญาลิขสิทธิ์ «{{ $license->software_name }}» ใช่หรือไม่?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-100 text-[10px] font-bold transition-all" title="ลบ">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                    ลบ
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                                    <div>
                                        <span class="text-slate-400">สิทธิ์ทั้งหมด:</span>
                                        <p class="font-bold text-slate-700 mt-1">{{ $license->total_license }} สิทธิ์</p>
                                    </div>
                                    <div>
                                        <span class="text-slate-400">ใช้งานแล้ว:</span>
                                        <p class="font-bold text-indigo-600 mt-1">{{ $license->used_license }} สิทธิ์</p>
                                    </div>
                                    <div>
                                        <span class="text-slate-400">วันหมดอายุ:</span>
                                        <p class="font-bold text-slate-700 mt-1">
                                            {{ $license->expire_date ? $license->expire_date->format('Y-m-d') : 'ถาวร' }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-slate-400">มูลค่าต่อปี:</span>
                                        <p class="font-bold text-indigo-600 mt-1">
                                            ฿{{ number_format($license->annual_cost ?? 0, 2) }}
                                        </p>
                                    </div>
                                </div>

                                @if($license->license_key)
                                    <div class="p-3 bg-white border border-slate-200 rounded-xl">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">รหัสลิขสิทธิ์ (License Key / Code)</span>
                                        <p class="text-xs font-mono text-slate-600 select-all mt-1 truncate">{{ $license->license_key }}</p>
                                    </div>
                                @endif

                                <!-- Active User Assignments list -->
                                <div class="space-y-2.5 pt-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">พนักงานที่ถือครองสิทธิ์ (Assignments)</span>
                                        
                                        @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']) && $license->used_license < $license->total_license)
                                            <!-- Simple Assignment popover opener -->
                                            <button type="button" onclick="document.getElementById('assign-form-{{ $license->id }}').classList.toggle('hidden')" class="text-[10px] font-bold text-indigo-600 hover:underline">
                                                + มอบสิทธิ์พนักงาน
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Assignment Form -->
                                    <div id="assign-form-{{ $license->id }}" class="hidden p-4 rounded-xl bg-white border border-slate-200">
                                        <form action="{{ route('licenses.assign', $license->id) }}" method="POST" class="flex gap-2 items-end">
                                            @csrf
                                            <div class="flex-1">
                                                <label class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider mb-1">เลือกพนักงาน</label>
                                                <select name="user_id" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-xs text-slate-700 focus:outline-none">
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->department->name ?? '' }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg text-xs">
                                                บันทึก
                                            </button>
                                        </form>
                                    </div>

                                    @if($license->assignments->where('status', 'active')->isEmpty())
                                        <p class="text-[10px] text-slate-500">ยังไม่มีการแจกจ่ายมอบสิทธิ์ใช้งานซอฟต์แวร์นี้</p>
                                    @else
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($license->assignments->where('status', 'active') as $asn)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-[10px] text-slate-700 font-semibold shadow-sm">
                                                    {{ $asn->employee->name }}
                                                    @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']))
                                                        <form action="{{ route('licenses.revoke', $asn->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-red-500 hover:text-red-400 font-bold ml-1">×</button>
                                                        </form>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <!-- Edit Modal for this license -->
                            @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']))
                            <div id="edit-license-modal-{{ $license->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm" onclick="if(event.target===this) closeEditLicense({{ $license->id }})">
                                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 p-8 space-y-5 animate-fadeIn max-h-[90vh] overflow-y-auto">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <h3 class="text-sm font-bold text-slate-800">แก้ไขสัญญาลิขสิทธิ์ซอฟต์แวร์</h3>
                                        <button onclick="closeEditLicense({{ $license->id }})" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
                                    </div>
                                    <form action="{{ route('licenses.update', $license->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ชื่อซอฟต์แวร์/ระบบคลาวด์</label>
                                            <input type="text" name="software_name" value="{{ $license->software_name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ประเภทสัญญา</label>
                                                <input type="text" name="license_type" value="{{ $license->license_type }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">จำนวนสิทธิ์รวม</label>
                                                <input type="number" name="total_license" value="{{ $license->total_license }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">วันที่เริ่มทำสัญญา</label>
                                                <input type="date" name="purchase_date" value="{{ $license->purchase_date ? $license->purchase_date->format('Y-m-d') : '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">วันสัญญาหมดอายุ</label>
                                                <input type="date" name="expire_date" value="{{ $license->expire_date ? $license->expire_date->format('Y-m-d') : '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">มูลค่าสัญญาต่อปี</label>
                                                <input type="number" step="0.01" name="annual_cost" value="{{ $license->annual_cost }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">เลือกคู่ค้าผู้จัดหา</label>
                                                <select name="vendor_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                                    <option value="">เลือกผู้ขาย...</option>
                                                    @foreach($vendors as $v)
                                                        <option value="{{ $v->id }}" @if($license->vendor_id == $v->id) selected @endif>{{ $v->vendor_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">รหัสลิขสิทธิ์ (License Key / Activation Code)</label>
                                            <textarea name="license_key" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">{{ $license->license_key }}</textarea>
                                        </div>
                                        <div class="flex gap-3 pt-2">
                                            <button type="button" onclick="closeEditLicense({{ $license->id }})" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl text-xs transition-all">ยกเลิก</button>
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

        <!-- Right 1 Col: Create License Form -->
        <div>
            @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']))
                <div class="bg-white border border-slate-200 p-8 rounded-3xl space-y-4 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">จดบันทึกสัญญาลิขสิทธิ์ซอฟต์แวร์</h3>
                    
                    <form action="{{ route('licenses.store') }}" method="POST" class="space-y-4 pt-2">
                        @csrf
                        
                        <div>
                            <label for="software_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ชื่อซอฟต์แวร์/ระบบคลาวด์</label>
                            <input type="text" name="software_name" id="software_name" required placeholder="เช่น Microsoft 365 Enterprise E3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="license_type" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ประเภทสัญญา</label>
                                <input type="text" name="license_type" id="license_type" placeholder="Subscription / Per-User" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="total_license" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">จำนวนสิทธิ์รวม</label>
                                <input type="number" name="total_license" id="total_license" required value="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="purchase_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">วันที่เริ่มทำสัญญา</label>
                                <input type="date" name="purchase_date" id="purchase_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="expire_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">วันสัญญาหมดอายุ</label>
                                <input type="date" name="expire_date" id="expire_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="annual_cost" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">มูลค่าสัญญาต่อปี</label>
                                <input type="number" step="0.01" name="annual_cost" id="annual_cost" placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="vendor_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">เลือกคู่ค้าผู้จัดหา</label>
                                <select name="vendor_id" id="vendor_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                    <option value="">เลือกผู้ขาย...</option>
                                    @foreach($vendors as $v)
                                        <option value="{{ $v->id }}">{{ $v->vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="license_key" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">รหัสลิขสิทธิ์ (License Key / Activation Code)</label>
                            <textarea name="license_key" id="license_key" rows="3" placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXX" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-xs transition-all shadow-md">
                            บันทึกสัญญาซอฟต์แวร์
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
                    <p class="text-xs text-slate-405 text-center">สิทธิ์การใช้งานของคุณไม่สามารถระบุข้อมูลสัญญาซอฟต์แวร์ได้ (เฉพาะไอทีหรือผู้ดูแลระบบ)</p>
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
        function openEditLicense(id) {
            const modal = document.getElementById('edit-license-modal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        function closeEditLicense(id) {
            const modal = document.getElementById('edit-license-modal-' + id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>

@endsection
