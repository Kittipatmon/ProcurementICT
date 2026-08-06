@extends('layouts.app')

@section('title', 'ลิขสิทธิ์ซอฟต์แวร์')
@section('page_title', 'สัญญาลิขสิทธิ์ซอฟต์แวร์และคลาวด์ (Software Licenses)')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Licenses List (Hospital-style dense grid) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-lg p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4">บันทึกสัญญาลิขสิทธิ์ซอฟต์แวร์ขององค์กร</h3>
                
                @if($licenses->isEmpty())
                    <div class="py-8 text-center border border-dashed border-slate-200 rounded-lg">
                        <p class="text-xs text-slate-400">ไม่มีข้อมูลสัญญาซอฟต์แวร์บันทึกในระบบ</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse border border-slate-200 text-xs">
                            <thead class="bg-slate-100 text-slate-700">
                                <tr>
                                    <th class="border border-slate-200 py-2.5 px-3 font-bold">ชื่อซอฟต์แวร์ / คู่ค้า</th>
                                    <th class="border border-slate-200 py-2.5 px-2 text-center font-bold">สิทธิ์ใช้งาน</th>
                                    <th class="border border-slate-200 py-2.5 px-2 text-center font-bold">วันหมดอายุ</th>
                                    <th class="border border-slate-200 py-2.5 px-3 text-right font-bold">มูลค่าสัญญา/ปี</th>
                                    <th class="border border-slate-200 py-2.5 px-2 text-center font-bold">สถานะ</th>
                                    <th class="border border-slate-200 py-2.5 px-3 text-center font-bold">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($licenses as $license)
                                    @php
                                        $isNearExpiry = $license->expire_date && $license->expire_date->isBefore(now()->addDays(30));
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors @if($isNearExpiry) bg-amber-50/20 @endif">
                                        <!-- ชื่อซอฟต์แวร์ / คู่ค้า -->
                                        <td class="border border-slate-200 py-2.5 px-3 font-semibold text-slate-900">
                                            <span class="flex items-center gap-1.5">
                                                {{ $license->software_name }}
                                                @if($isNearExpiry)
                                                    <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-black uppercase bg-amber-50 border border-amber-200 text-amber-700 leading-none">ใกล้หมดอายุ</span>
                                                @endif
                                            </span>
                                            <span class="block text-[10px] text-slate-500 font-normal mt-0.5">ประเภท: {{ $license->license_type ?? 'ไม่ระบุ' }}</span>
                                            @if($license->license_key)
                                                <span class="block text-[9px] text-slate-400 font-mono mt-1 select-all truncate max-w-[200px]" title="{{ $license->license_key }}">Key: {{ $license->license_key }}</span>
                                            @endif
                                        </td>
                                        
                                        <!-- สิทธิ์ใช้งาน -->
                                        <td class="border border-slate-200 py-2.5 px-2 text-center font-semibold text-slate-800">
                                            <span class="text-blue-700">{{ $license->used_license }}</span> / {{ $license->total_license }}
                                        </td>
                                        
                                        <!-- วันหมดอายุ -->
                                        <td class="border border-slate-200 py-2.5 px-2 text-center font-medium text-slate-700 whitespace-nowrap">
                                            {{ $license->expire_date ? $license->expire_date->format('d/m/') . ($license->expire_date->format('Y') + 543) : 'ถาวร' }}
                                        </td>
                                        
                                        <!-- มูลค่าสัญญา/ปี -->
                                        <td class="border border-slate-200 py-2.5 px-3 text-right font-mono font-bold text-slate-700">
                                            ฿{{ number_format($license->annual_cost ?? 0, 2) }}
                                        </td>
                                        
                                        <!-- สถานะ -->
                                        <td class="border border-slate-200 py-2.5 px-2 text-center whitespace-nowrap">
                                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded border {{ $license->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200' }}">
                                                {{ $license->status }}
                                            </span>
                                        </td>
                                        
                                        <!-- การจัดการ -->
                                        <td class="border border-slate-200 py-2.5 px-3 text-center whitespace-nowrap">
                                            @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']))
                                                <button type="button" onclick="openEditLicense({{ $license->id }})" class="inline-flex items-center px-2 py-1 rounded bg-blue-50 border border-blue-200 text-blue-800 hover:bg-blue-100 text-[10px] font-bold transition-colors">
                                                    แก้ไข
                                                </button>
                                                <form action="{{ route('licenses.destroy', $license->id) }}" method="POST" class="inline" onsubmit="return confirm('คุณต้องการลบสัญญาลิขสิทธิ์ «{{ $license->software_name }}» ใช่หรือไม่?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-2 py-1 rounded bg-rose-50 border border-rose-200 text-rose-800 hover:bg-rose-100 text-[10px] font-bold transition-colors ml-1">
                                                        ลบ
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">IT Readonly</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- User Assignments Sub-Row -->
                                    <tr class="bg-slate-50/30">
                                        <td colspan="6" class="border border-slate-200 py-2 px-3">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">การถือครองสิทธิ์ (Assignments):</span>
                                                    @if($license->assignments->where('status', 'active')->isEmpty())
                                                        <span class="text-[10px] text-slate-400">ยังไม่มีการมอบสิทธิ์</span>
                                                    @else
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($license->assignments->where('status', 'active') as $asn)
                                                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[10px] text-slate-700 font-semibold shadow-sm">
                                                                    {{ $asn->employee->name }}
                                                                    @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']))
                                                                        <form action="{{ route('licenses.revoke', $asn->id) }}" method="POST" class="inline">
                                                                            @csrf
                                                                            <button type="submit" class="text-red-500 hover:text-red-400 font-bold ml-1 text-[11px]">&times;</button>
                                                                        </form>
                                                                    @endif
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']) && $license->used_license < $license->total_license)
                                                    <button type="button" onclick="document.getElementById('assign-form-{{ $license->id }}').classList.toggle('hidden')" class="text-[10px] font-bold text-blue-700 hover:underline shrink-0">
                                                        + มอบสิทธิ์พนักงาน
                                                    </button>
                                                @endif
                                            </div>

                                            <!-- Assignment Input Box -->
                                            <div id="assign-form-{{ $license->id }}" class="hidden mt-2 p-3 bg-white border border-slate-200 rounded">
                                                <form action="{{ route('licenses.assign', $license->id) }}" method="POST" class="flex gap-2 items-end max-w-sm">
                                                    @csrf
                                                    <div class="flex-1">
                                                        <label class="block text-[8px] font-bold text-slate-500 uppercase tracking-wider mb-1">เลือกพนักงาน</label>
                                                        <select name="user_id" required class="w-full bg-slate-50 border border-slate-200 rounded px-2 py-1 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                                            @foreach($employees as $emp)
                                                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->department->name ?? 'ไม่มีแผนก' }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="px-3 py-1 bg-blue-700 hover:bg-blue-600 text-white font-bold rounded text-xs transition-colors">
                                                        มอบสิทธิ์
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']))
                                    <div id="edit-license-modal-{{ $license->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm" onclick="if(event.target===this) closeEditLicense({{ $license->id }})">
                                        <div class="bg-white rounded-lg border border-slate-200 w-full max-w-lg mx-4 p-6 space-y-4 animate-fadeIn max-h-[90vh] overflow-y-auto">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                <h3 class="text-sm font-bold text-slate-900">แก้ไขสัญญาลิขสิทธิ์ซอฟต์แวร์</h3>
                                                <button onclick="closeEditLicense({{ $license->id }})" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
                                            </div>
                                            <form action="{{ route('licenses.update', $license->id) }}" method="POST" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ชื่อซอฟต์แวร์/ระบบคลาวด์</label>
                                                    <input type="text" name="software_name" value="{{ $license->software_name }}" required class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ประเภทสัญญา</label>
                                                        <input type="text" name="license_type" value="{{ $license->license_type }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">จำนวนสิทธิ์รวม</label>
                                                        <input type="number" name="total_license" value="{{ $license->total_license }}" required class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">วันที่เริ่มทำสัญญา</label>
                                                        <input type="date" name="purchase_date" value="{{ $license->purchase_date ? $license->purchase_date->format('Y-m-d') : '' }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">วันสัญญาหมดอายุ</label>
                                                        <input type="date" name="expire_date" value="{{ $license->expire_date ? $license->expire_date->format('Y-m-d') : '' }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">มูลค่าสัญญาต่อปี</label>
                                                        <input type="number" step="0.01" name="annual_cost" value="{{ $license->annual_cost }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">เลือกคู่ค้าผู้จัดหา</label>
                                                        <select name="vendor_id" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                                            <option value="">เลือกผู้ขาย...</option>
                                                            @foreach($vendors as $v)
                                                                <option value="{{ $v->id }}" @if($license->vendor_id == $v->id) selected @endif>{{ $v->vendor_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">รหัสลิขสิทธิ์ (License Key)</label>
                                                    <textarea name="license_key" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">{{ $license->license_key }}</textarea>
                                                </div>
                                                <div class="flex gap-3 pt-2">
                                                    <button type="button" onclick="closeEditLicense({{ $license->id }})" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded text-xs transition-colors">ยกเลิก</button>
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

        <!-- Right 1 Col: Create License Form -->
        <div>
            @if(in_array(Auth::user()->procurement_role, ['admin', 'ict']))
                <div class="bg-white border border-slate-200 p-6 rounded-lg space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">จดบันทึกสัญญาลิขสิทธิ์ซอฟต์แวร์</h3>
                    
                    <form action="{{ route('licenses.store') }}" method="POST" class="space-y-4 pt-2">
                        @csrf
                        
                        <div>
                            <label for="software_name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ชื่อซอฟต์แวร์/ระบบคลาวด์</label>
                            <input type="text" name="software_name" id="software_name" required placeholder="เช่น Microsoft 365 Enterprise E3" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="license_type" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ประเภทสัญญา</label>
                                <input type="text" name="license_type" id="license_type" placeholder="Subscription / Per-User" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="total_license" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">จำนวนสิทธิ์รวม</label>
                                <input type="number" name="total_license" id="total_license" required value="1" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="purchase_date" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">วันที่เริ่มทำสัญญา</label>
                                <input type="date" name="purchase_date" id="purchase_date" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="expire_date" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">วันสัญญาหมดอายุ</label>
                                <input type="date" name="expire_date" id="expire_date" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="annual_cost" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">มูลค่าสัญญาต่อปี</label>
                                <input type="number" step="0.01" name="annual_cost" id="annual_cost" placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="vendor_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">เลือกคู่ค้าผู้จัดหา</label>
                                <select name="vendor_id" id="vendor_id" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                    <option value="">เลือกผู้ขาย...</option>
                                    @foreach($vendors as $v)
                                        <option value="{{ $v->id }}">{{ $v->vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="license_key" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">รหัสลิขสิทธิ์ (License Key)</label>
                            <textarea name="license_key" id="license_key" rows="3" placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXX" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-600 text-white font-bold rounded text-xs transition-colors shadow-sm">
                            บันทึกสัญญาซอฟต์แวร์
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white border border-slate-200 p-6 rounded-lg">
                    <p class="text-xs text-slate-400 text-center">สิทธิ์การใช้งานของคุณไม่สามารถระบุข้อมูลสัญญาซอฟต์แวร์ได้ (เฉพาะไอทีหรือผู้ดูแลระบบ)</p>
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
