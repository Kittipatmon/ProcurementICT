@extends('layouts.app')

@section('title', 'ผู้รับผิดชอบและบทบาทหน้าที่')
@section('page_title', 'บทบาทหน้าที่และความรับผิดชอบ (Roles & Responsibilities)')

@section('content')

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-blue-900 border-l-4 border-blue-600 pl-3">โครงสร้างสิทธิ์การใช้งาน</h2>
            <p class="text-sm text-slate-500 mt-1 pl-4">คำชี้แจงบทบาทหน้าที่และความรับผิดชอบในกระบวนการจัดซื้อ ICT</p>
        </div>
    </div>

    <div class="space-y-6">
        
        <!-- Info Card -->
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-lg p-5 shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-blue-900 mb-1">คำแนะนำสิทธิ์การเข้าถึงและการรับผิดชอบ</h3>
                    <p class="text-xs text-blue-800">ตารางอธิบายบทบาทสิทธิ์การเข้าถึง และการรับผิดชอบจัดการเอกสารในแต่ละขั้นตอนของระบบจัดซื้อจัดจ้าง</p>
                </div>
            </div>
        </div>

        <!-- Responsibilities Table (Hospital/Clinical Style Grid) -->
        <div class="bg-white border border-slate-300 shadow-md rounded-lg overflow-hidden">
            <div class="bg-blue-800 px-4 py-3 flex items-center text-white border-b-4 border-blue-900">
                <h3 class="text-sm font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    ตารางข้อมูลผู้รับผิดชอบ
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-blue-50 text-blue-900 border-b-2 border-blue-200 uppercase tracking-wide">
                            <th class="border-r border-blue-200 py-3 px-4 w-40 font-bold text-center">บทบาทสิทธิ์ผู้ใช้งาน</th>
                            <th class="border-r border-blue-200 py-3 px-4 w-48 font-bold">ขั้นตอนที่ต้องรับผิดชอบ</th>
                            <th class="border-r border-blue-200 py-3 px-4 font-bold">รายละเอียดงานที่รับผิดชอบ</th>
                            <th class="border-r border-blue-200 py-3 px-4 w-48 font-bold text-center">การเข้าถึงในระบบ</th>
                            <th class="py-3 px-4 w-48 font-bold text-center">ผู้รับผิดชอบ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 font-mono text-slate-800">
                        
                        <!-- User / Requester -->
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-slate-200 py-3 px-4 font-sans font-bold text-slate-900 bg-slate-50/50">
                                <span>ผู้ขอจัดซื้อ</span>
                                <span class="block text-[10px] text-slate-500 font-mono font-normal mt-0.5">User / Requester</span>
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-blue-800 font-semibold bg-white">
                                1. ขอใบเสนอราคา<br>
                                2. จัดทำคำขอจัดซื้อ
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-slate-600 bg-white">
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>สำรวจความต้องการและจัดหาเอกสารใบเสนอราคา (Quotation) จากบริษัทผู้ขาย</li>
                                    <li>กรอกข้อมูลรายละเอียดอุปกรณ์ ซอฟต์แวร์ และงบประมาณเสนอเข้าระบบ</li>
                                    <li>ตรวจสอบความคืบหน้าของสถานะรายการจัดซื้อของแผนกตนเอง</li>
                                </ul>
                            </td>
                            <td class="border-r border-slate-200 py-3 px-4 bg-white text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border bg-slate-50 text-slate-600 border-slate-200 whitespace-nowrap">เข้าถึงเฉพาะรายการของตนเอง</span>
                            </td>
                            <td class="border-b border-slate-200 py-3 px-4 bg-white">
                                <select data-role="user" placeholder="-- เลือกผู้รับผิดชอบ --" class="searchable-select w-full text-xs border border-slate-300 rounded p-1.5 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" hidden>-- เลือกผู้รับผิดชอบ --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ str_contains($employee->procurement_role, 'user') ? 'selected' : '' }}>{{ $employee->firstname }} {{ $employee->lastname }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <!-- Department Manager -->
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-slate-200 py-3 px-4 font-sans font-bold text-slate-900 bg-slate-50/50">
                                <span>ผู้จัดการแผนก</span>
                                <span class="block text-[10px] text-slate-500 font-mono font-normal mt-0.5">Manager</span>
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-blue-800 font-semibold bg-white">
                                3. อนุมัติขั้นต้นแผนก
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-slate-600 bg-white">
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>ตรวจสอบความถูกต้อง ความจำเป็น และความเหมาะสมของคำขอของพนักงานภายในแผนกตนเอง</li>
                                    <li>พิจารณาอนุมัติหรือปฏิเสธคำขอจัดซื้อเพื่อส่งต่อไปยังฝ่าย ICT</li>
                                </ul>
                            </td>
                            <td class="border-r border-slate-200 py-3 px-4 bg-white text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border bg-blue-50 text-blue-800 border-blue-200 whitespace-nowrap">เข้าถึงข้อมูลภายในแผนกตนเอง</span>
                            </td>
                            <td class="border-b border-slate-200 py-3 px-4 bg-white">
                                <select data-role="manager" placeholder="-- เลือกผู้รับผิดชอบ --" class="searchable-select w-full text-xs border border-slate-300 rounded p-1.5 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" hidden>-- เลือกผู้รับผิดชอบ --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ str_contains($employee->procurement_role, 'manager') ? 'selected' : '' }}>{{ $employee->firstname }} {{ $employee->lastname }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <!-- Manager ICT -->
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-slate-200 py-3 px-4 font-sans font-bold text-slate-900 bg-slate-50/50">
                                <span>ผู้จัดการฝ่าย ICT</span>
                                <span class="block text-[10px] text-slate-500 font-mono font-normal mt-0.5">Manager ICT</span>
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-blue-800 font-semibold bg-white">
                                4. ตรวจสอบทางเทคนิค ICT<br>
                                7. ตรวจสอบและอนุมัติ PR/PO
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-slate-600 bg-white">
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>ตรวจสอบมาตรฐานทางเทคนิคของอุปกรณ์ ซอฟต์แวร์ และโครงสร้างระบบเครือข่าย</li>
                                    <li>พิจารณาอนุมัติใบเสนอราคาในระดับฝ่าย ICT ก่อนเสนอขออนุมัติงบประมาณ</li>
                                    <li>อนุมัติความถูกต้องของใบสั่งซื้อ PR/PO เพื่อดำเนินการในขั้นตอนบริหารธุรกิจ</li>
                                </ul>
                            </td>
                            <td class="border-r border-slate-200 py-3 px-4 bg-white text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border bg-indigo-50 text-indigo-800 border-indigo-200 whitespace-nowrap">เข้าถึงและอนุมัติระดับ ICT</span>
                            </td>
                            <td class="border-b border-slate-200 py-3 px-4 bg-white">
                                <select data-role="ict" placeholder="-- เลือกผู้รับผิดชอบ --" class="searchable-select w-full text-xs border border-slate-300 rounded p-1.5 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" hidden>-- เลือกผู้รับผิดชอบ --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ str_contains($employee->procurement_role, 'ict') ? 'selected' : '' }}>{{ $employee->firstname }} {{ $employee->lastname }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <!-- CAO / Executive -->
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-slate-200 py-3 px-4 font-sans font-bold text-slate-900 bg-slate-50/50">
                                <span>ผู้อำนวยการบริหาร</span>
                                <span class="block text-[10px] text-slate-500 font-mono font-normal mt-0.5">CAO / Executive</span>
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-blue-800 font-semibold bg-white">
                                5. อนุมัติงบประมาณ CAO<br>
                                8. อนุมัติเปิด PR/PO
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-slate-600 bg-white">
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>พิจารณาอนุมัติวงเงินงบประมาณตามแผนงานและนโยบายการจัดซื้อขององค์กร</li>
                                    <li>อนุมัติโครงการและอนุมัติการออกสั่งซื้อ (PR/PO) ในขั้นตอนสุดท้ายสำหรับฝ่ายบริหาร</li>
                                </ul>
                            </td>
                            <td class="border-r border-slate-200 py-3 px-4 bg-white text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border bg-purple-50 text-purple-800 border-purple-200 whitespace-nowrap">เข้าถึงและอนุมัติฝ่ายบริหารทั้งหมด</span>
                            </td>
                            <td class="border-b border-slate-200 py-3 px-4 bg-white">
                                <select data-role="cao" placeholder="-- เลือกผู้รับผิดชอบ --" class="searchable-select w-full text-xs border border-slate-300 rounded p-1.5 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" hidden>-- เลือกผู้รับผิดชอบ --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ str_contains($employee->procurement_role, 'cao') ? 'selected' : '' }}>{{ $employee->firstname }} {{ $employee->lastname }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <!-- Procurement Officer -->
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-slate-200 py-3 px-4 font-sans font-bold text-slate-900 bg-slate-50/50">
                                <span>เจ้าหน้าที่จัดซื้อ</span>
                                <span class="block text-[10px] text-slate-500 font-mono font-normal mt-0.5">Procurement Officer</span>
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-blue-800 font-semibold bg-white">
                                6. จัดทำเอกสาร PR/PO<br>
                                9. ส่งเอกสารให้บริษัทผู้ขาย (SUP)
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-slate-600 bg-white">
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>ดำเนินการสร้างใบขอซื้อ (PR) และใบสั่งซื้อ (PO) ในระบบตามรายการที่ผ่านการอนุมัติ</li>
                                    <li>ประสานงานกับ Supplier (SUP) เพื่อออกใบสั่งซื้อ และติดตามกระบวนการส่งสินค้า</li>
                                    <li>อัปเดตเอกสารข้อมูลการสั่งซื้อและสถานะส่งมอบสินค้าลงสู่ระบบ</li>
                                </ul>
                            </td>
                            <td class="border-r border-slate-200 py-3 px-4 bg-white text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border bg-amber-50 text-amber-850 border-amber-200 whitespace-nowrap">สิทธิ์แก้ไขจัดการเอกสารจัดซื้อ</span>
                            </td>
                            <td class="border-b border-slate-200 py-3 px-4 bg-white">
                                <select data-role="procurement" placeholder="-- เลือกผู้รับผิดชอบ --" class="searchable-select w-full text-xs border border-slate-300 rounded p-1.5 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" hidden>-- เลือกผู้รับผิดชอบ --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ str_contains($employee->procurement_role, 'procurement') ? 'selected' : '' }}>{{ $employee->firstname }} {{ $employee->lastname }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <!-- Accounting & Finance -->
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-slate-200 py-3 px-4 font-sans font-bold text-slate-900 bg-slate-50/50">
                                <span>ฝ่ายบัญชีและการเงิน</span>
                                <span class="block text-[10px] text-slate-500 font-mono font-normal mt-0.5">Finance & Accounting</span>
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-blue-800 font-semibold bg-white">
                                10. ส่งใบกำกับภาษี / เบิกจ่าย
                            </td>
                            <td class="border border-slate-200 py-3 px-4 font-sans text-slate-600 bg-white">
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>ตรวจสอบใบกำกับภาษี (Tax Invoice) และใบเสร็จรับเงินที่ได้รับจากผู้ขาย</li>
                                    <li>ดำเนินกระบวนการตั้งหนี้และเบิกจ่ายเงินให้กับคู่ค้าทางธุรกิจ</li>
                                    <li>ปิดคำขอจัดซื้อเมื่อกระบวนการชำระเงินเสร็จสิ้นสมบูรณ์</li>
                                </ul>
                            </td>
                            <td class="border-r border-slate-200 py-3 px-4 bg-white text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border bg-emerald-50 text-emerald-800 border-emerald-200 whitespace-nowrap">ตรวจสอบและอัปเดตการเบิกจ่าย</span>
                            </td>
                            <td class="border-b border-slate-200 py-3 px-4 bg-white">
                                <select data-role="accounting" placeholder="-- เลือกผู้รับผิดชอบ --" class="searchable-select w-full text-xs border border-slate-300 rounded p-1.5 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" hidden>-- เลือกผู้รับผิดชอบ --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ str_contains($employee->procurement_role, 'accounting') ? 'selected' : '' }}>{{ $employee->firstname }} {{ $employee->lastname }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Tom Select for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.searchable-select').forEach(function(el) {
                let role = el.getAttribute('data-role');
                new TomSelect(el, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    onChange: function(value) {
                        if (value) {
                            fetch('{{ route('responsibilities.update') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    employee_id: value,
                                    role: role
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    globalToast.fire({ icon: 'success', title: 'บันทึกผู้รับผิดชอบเรียบร้อยแล้ว' });
                                } else {
                                    globalToast.fire({ icon: 'error', title: 'เกิดข้อผิดพลาดในการบันทึก' });
                                }
                            })
                            .catch(error => {
                                globalToast.fire({ icon: 'error', title: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้' });
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
