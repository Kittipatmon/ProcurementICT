@extends('layouts.app')

@section('title', 'ติดตามความคืบหน้าการจัดซื้อ')
@section('page_title', 'ติดตามความคืบหน้าการจัดซื้อและต่ออายุ (Procurement Tracking)')

@section('content')

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-blue-900 border-l-4 border-blue-600 pl-3">แฟ้มติดตามความคืบหน้า</h2>
            <p class="text-sm text-slate-500 mt-1 pl-4">ตารางแสดงความคืบหน้าการสั่งซื้อ ต่ออายุการใช้งานอุปกรณ์ และโปรแกรมต่างๆ ทั้งหมด</p>
        </div>
    </div>

    <!-- Procurement & Renewal Tracking Timeline Table (Hospital/Clinical Style Grid) -->
    <div class="bg-white border border-slate-300 shadow-md rounded-lg overflow-hidden mb-8">
        <div class="bg-blue-800 px-4 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white border-b-4 border-blue-900">
            <h3 class="text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                ตารางบันทึกการติดตาม
            </h3>
            
            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-3 self-start md:self-auto">
                <!-- Status Filter -->
                <div class="flex items-center gap-2 bg-blue-900 border border-blue-700 rounded px-3 py-1.5 shadow-inner">
                    <label for="status-filter" class="text-xs font-bold text-blue-200 whitespace-nowrap">สถานะ:</label>
                    <select id="status-filter" onchange="filterTable()" class="bg-white border border-blue-300 rounded px-2 py-1 text-xs text-slate-800 font-bold focus:outline-none focus:border-blue-500 cursor-pointer">
                        <option value="all">-- ทุกสถานะ --</option>
                        <option value="in_progress">กำลังดำเนินการ</option>
                        <option value="completed">เสร็จสิ้น</option>
                        <option value="rejected">ยกเลิก</option>
                    </select>
                </div>
                
                <!-- User/Role Filter -->
                <div class="flex items-center gap-2 bg-blue-900 border border-blue-700 rounded px-3 py-1.5 shadow-inner">
                    <label for="role-filter" class="text-xs font-bold text-blue-200 whitespace-nowrap">แสดงขั้นตอนของ:</label>
                    <select id="role-filter" onchange="filterTable()" class="bg-white border border-blue-300 rounded px-2 py-1 text-xs text-slate-800 font-bold focus:outline-none focus:border-blue-500 cursor-pointer">
                        <option value="all">-- ทุกผู้รับผิดชอบ --</option>
                        <option value="user">ผู้ขอจัดซื้อ (User)</option>
                        <option value="manager">ผู้จัดการแผนก (Manager)</option>
                        <option value="ict">ผู้จัดการฝ่าย ICT (Manager ICT)</option>
                        <option value="cao">ผู้อำนวยการบริหาร (CAO)</option>
                        <option value="procurement">เจ้าหน้าที่จัดซื้อ (Procurement)</option>
                        <option value="accounting">ฝ่ายบัญชีและการเงิน (Accounting)</option>
                    </select>
                </div>
            </div>
        </div>

        @if($allRequests->isEmpty())
            <div class="p-8 text-center bg-slate-50">
                <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-slate-500 text-sm font-semibold">ไม่มีข้อมูลรายการจัดซื้อในขณะนี้</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1200px] text-xs">
                    <thead>
                        <tr class="bg-blue-50 text-blue-900 border-b-2 border-blue-200">
                            <th rowspan="2" class="border-r border-blue-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap">ลำดับ</th>
                            <th rowspan="2" class="border-r border-blue-200 py-2.5 px-3 text-left align-middle font-bold min-w-[180px]">รายการ / เลขที่คำขอ</th>
                            <th rowspan="2" class="border-r border-blue-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap">ขอใบ<br>เสนอราคา</th>
                            <th colspan="3" class="border-r border-blue-200 py-2 px-2 text-center align-middle font-bold">ขออนุมัติการดำเนินการ</th>
                            <th colspan="3" class="border-r border-blue-200 py-2 px-2 text-center align-middle font-bold">เปิด PR / PO</th>
                            <th colspan="2" class="border-r border-blue-200 py-2 px-2 text-center align-middle font-bold">ส่งเอกสารให้บัญชี</th>
                            <th rowspan="2" class="border-r border-blue-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap">ผลการดำเนินงาน</th>
                            <th colspan="2" class="border-r border-blue-200 py-2 px-2 text-center align-middle font-bold">จำนวนเงิน</th>
                            <th rowspan="2" class="border-r border-blue-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap">วันที่เริ่ม<br>ดำเนินการ</th>
                            <th rowspan="2" class="border-r border-blue-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap">เลขที่ PO /<br>ดำเนินการจริง</th>
                            <th rowspan="2" class="border-r border-blue-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap">วันที่หมดอายุ</th>
                            <th rowspan="2" class="border-r border-blue-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap">ต่ออายุครั้งถัดไป</th>
                            <th rowspan="2" class="py-2.5 px-3 text-left align-middle font-bold min-w-[150px]">หมายเหตุ</th>
                        </tr>
                        <tr class="bg-blue-100/50 text-blue-800 text-[10px] border-b border-blue-200">
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">จัดทำ</th>
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">Manager ICT</th>
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">CAO อนุมัติ</th>
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">จัดทำ</th>
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">Manager ICT</th>
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">CAO อนุมัติ</th>
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">ส่งเอกสาร SUP</th>
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">ส่งใบกำกับภาษี</th>
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">งบประมาณ (ตั้ง)</th>
                            <th class="border-r border-t border-blue-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">ใช้จริง</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 font-mono">
                        @foreach(['in_progress' => 'กำลังดำเนินการ (In Progress)', 'completed' => 'เสร็จสิ้น (Completed)', 'rejected' => 'ยกเลิก (Rejected)'] as $groupKey => $groupTitle)
                            @php
                                $textClass = 'text-blue-800';
                                $indicatorClass = 'bg-blue-600';
                                $frameClass = 'border-blue-200 bg-gradient-to-r from-blue-100 to-blue-50/50 border-l-blue-600';
                                
                                if ($groupKey === 'completed') {
                                    $textClass = 'text-emerald-800';
                                    $indicatorClass = 'bg-emerald-600';
                                    $frameClass = 'border-emerald-200 bg-gradient-to-r from-emerald-100 to-emerald-50/50 border-l-emerald-600';
                                } elseif ($groupKey === 'rejected') {
                                    $textClass = 'text-rose-800';
                                    $indicatorClass = 'bg-rose-600';
                                    $frameClass = 'border-rose-200 bg-gradient-to-r from-rose-100 to-rose-50/50 border-l-rose-600';
                                }
                            @endphp
                            <tr class="group-header bg-slate-50/30" data-group="{{ $groupKey }}">
                                <td colspan="19" class="p-0 border-y border-slate-200">
                                    <div class="my-3 relative -left-px">
                                        <div class="inline-flex items-center gap-3 pl-5 pr-8 py-2.5 rounded-r-full shadow-[3px_3px_10px_-2px_rgba(0,0,0,0.1)] border-y border-r border-l-4 {{ $frameClass }} transform transition-transform duration-300 hover:translate-x-1.5 cursor-default">
                                            <span class="w-2.5 h-2.5 rounded-full {{ $indicatorClass }} shadow-sm"></span>
                                            <span class="font-bold {{ $textClass }} tracking-wide">{{ $groupTitle }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            @php
                                if ($groupKey === 'in_progress') {
                                    $filteredRequests = $allRequests->filter(fn($req) => !in_array($req->status, ['completed', 'rejected']));
                                } elseif ($groupKey === 'completed') {
                                    $filteredRequests = $allRequests->filter(fn($req) => $req->status === 'completed');
                                } else {
                                    $filteredRequests = $allRequests->filter(fn($req) => $req->status === 'rejected');
                                }
                            @endphp

                            @if($filteredRequests->isEmpty())
                                <tr class="empty-row" data-group="{{ $groupKey }}">
                                    <td colspan="19" class="py-6 text-center text-slate-400 font-sans text-xs italic">ไม่มีรายการในกลุ่มนี้</td>
                                </tr>
                            @else
                                <tr class="empty-row" data-group="{{ $groupKey }}" style="display: none;">
                                    <td colspan="19" class="py-6 text-center text-slate-400 font-sans text-xs italic">ไม่มีรายการที่ตรงกับเงื่อนไข</td>
                                </tr>
                                @foreach($filteredRequests as $index => $req)
                                    @php
                                        $statuses = [
                                            'draft' => 11.11,
                                            'submitted' => 22.22,
                                            'approved_manager' => 33.33,
                                            'approved_ict' => 44.44,
                                            'approved_cao' => 55.56,
                                            'pr_created' => 66.67,
                                            'po_created' => 77.78,
                                            'delivered' => 88.89,
                                            'completed' => 100.00,
                                            'rejected' => 0.00
                                        ];
                                        $progress = $statuses[$req->status] ?? 11.11;
                                        
                                        $step1 = true;
                                        $step2 = in_array($req->status, ['submitted', 'approved_manager', 'approved_ict', 'approved_cao', 'pr_created', 'po_created', 'delivered', 'completed']);
                                        $step3 = in_array($req->status, ['approved_manager', 'approved_ict', 'approved_cao', 'pr_created', 'po_created', 'delivered', 'completed']);
                                        $step4 = in_array($req->status, ['approved_ict', 'approved_cao', 'pr_created', 'po_created', 'delivered', 'completed']);
                                        $step5 = in_array($req->status, ['pr_created', 'pr_approved_ict', 'pr_approved_cao', 'po_created', 'delivered', 'completed']);
                                        $step_pr_ict = in_array($req->status, ['pr_approved_ict', 'pr_approved_cao', 'po_created', 'delivered', 'completed']);
                                        $step_pr_cao = in_array($req->status, ['pr_approved_cao', 'po_created', 'delivered', 'completed']);
                                        $step6 = in_array($req->status, ['po_created', 'delivered', 'completed']);
                                        $step8 = in_array($req->status, ['delivered', 'completed']);

                                        // Map Current Owner
                                        $currentOwner = 'none';
                                        $ownerText = 'เสร็จสิ้น';
                                        $ownerColor = 'text-slate-500 bg-slate-100 border-slate-200';
                                        if ($req->status === 'draft') {
                                            $currentOwner = 'user';
                                            $ownerText = 'รอผู้ขอจัดซื้อ';
                                            $ownerColor = 'text-blue-700 bg-blue-50 border-blue-200';
                                        } elseif ($req->status === 'submitted') {
                                            $currentOwner = 'manager';
                                            $ownerText = 'รอ Manager แผนก';
                                            $ownerColor = 'text-amber-700 bg-amber-50 border-amber-255';
                                        } elseif (in_array($req->status, ['approved_manager', 'pr_created'])) {
                                            $currentOwner = 'ict';
                                            $ownerText = 'รอ Manager ICT';
                                            $ownerColor = 'text-indigo-755 bg-indigo-50 border-indigo-200';
                                        } elseif (in_array($req->status, ['approved_ict', 'pr_approved_ict'])) {
                                            $currentOwner = 'cao';
                                            $ownerText = 'รอ CAO';
                                            $ownerColor = 'text-purple-700 bg-purple-50 border-purple-200';
                                        } elseif (in_array($req->status, ['approved_cao', 'pr_approved_cao', 'po_created'])) {
                                            $currentOwner = 'procurement';
                                            $ownerText = 'รอจัดซื้อดำเนินงาน';
                                            $ownerColor = 'text-amber-850 bg-amber-50 border-amber-300';
                                        } elseif ($req->status === 'delivered') {
                                            $currentOwner = 'accounting';
                                            $ownerText = 'รอฝ่ายบัญชี';
                                            $ownerColor = 'text-emerald-700 bg-emerald-50 border-emerald-200';
                                        }
                                    @endphp
                                    <tr class="data-row hover:bg-slate-50/80 transition-colors" data-current-owner="{{ $currentOwner }}" data-group="{{ $groupKey }}">
                                        <!-- ลำดับ -->
                                        <td class="border border-slate-200 py-2 px-2 text-center text-slate-800 font-semibold bg-slate-50/50">{{ $loop->iteration }}</td>
                                        
                                        <!-- รายการ / เลขที่คำขอ -->
                                        <td class="border border-slate-200 py-2 px-3 font-semibold text-slate-900 font-sans bg-white">
                                            <a href="{{ route('procurements.show', $req->id) }}" class="hover:text-blue-700 transition-colors text-xs block">
                                                {{ $req->title }}
                                            </a>
                                            <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                                                <span class="text-[10px] text-slate-500 font-mono">{{ $req->request_no }}</span>
                                                <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold border {{ $ownerColor }} whitespace-nowrap">{{ $ownerText }}</span>
                                            </div>
                                        </td>
                                        
                                        <!-- ขอใบเสนอราคา -->
                                        <td class="border border-slate-200 py-2 px-1 text-center bg-slate-50/30">
                                            <input type="checkbox" 
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                                {{ $step1 ? 'checked' : '' }} 
                                                onclick="confirmStepUpdate({{ $req->id }}, 'step1', 'ขอใบเสนอราคา', this)">
                                        </td>

                                        <!-- จัดทำ -->
                                        <td class="border border-slate-200 py-2 px-1 text-center bg-white">
                                            <input type="checkbox" 
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                                {{ $step2 ? 'checked' : '' }} 
                                                onclick="confirmStepUpdate({{ $req->id }}, 'step2', 'ขออนุมัติการดำเนินการ: จัดทำ', this)">
                                        </td>

                                        <!-- Manager/ICT -->
                                        <td class="border border-slate-200 py-2 px-1 text-center bg-white">
                                            <input type="checkbox" 
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                                {{ $step3 ? 'checked' : '' }} 
                                                onclick="confirmStepUpdate({{ $req->id }}, 'step3', 'ขออนุมัติการดำเนินการ: Manager ICT', this)">
                                        </td>

                                        <!-- CAO -->
                                        <td class="border border-slate-200 py-2 px-1 text-center bg-white">
                                            <input type="checkbox" 
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                                {{ $step4 ? 'checked' : '' }} 
                                                onclick="confirmStepUpdate({{ $req->id }}, 'step4', 'ขออนุมัติการดำเนินการ: CAO อนุมัติ', this)">
                                        </td>

                                        <!-- จัดทำ PR/PO -->
                                        <td class="border border-slate-200 py-2 px-1 text-center bg-white">
                                            <input type="checkbox" 
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                                {{ $step5 ? 'checked' : '' }} 
                                                onclick="confirmStepUpdate({{ $req->id }}, 'step5', 'เปิด PR/PO: จัดทำ', this)">
                                        </td>

                                        <!-- Manager ICT อนุมัติ PR/PO -->
                                        <td class="border border-slate-200 py-2 px-1 text-center bg-white">
                                            <input type="checkbox" 
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                                {{ $step_pr_ict ? 'checked' : '' }} 
                                                onclick="confirmStepUpdate({{ $req->id }}, 'step_pr_ict', 'เปิด PR/PO: Manager ICT อนุมัติ', this)">
                                        </td>

                                        <!-- CAO อนุมัติ PR/PO -->
                                        <td class="border border-slate-200 py-2 px-1 text-center bg-white">
                                            <input type="checkbox" 
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                                {{ $step_pr_cao ? 'checked' : '' }} 
                                                onclick="confirmStepUpdate({{ $req->id }}, 'step_pr_cao', 'เปิด PR/PO: CAO อนุมัติ', this)">
                                        </td>

                                        <!-- ส่งเอกสารให้ SUP -->
                                        <td class="border border-slate-200 py-2 px-1 text-center bg-white">
                                            <input type="checkbox" 
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                                {{ $step6 ? 'checked' : '' }} 
                                                onclick="confirmStepUpdate({{ $req->id }}, 'step6', 'ส่งเอกสารให้ SUP', this)">
                                        </td>

                                        <!-- ส่งใบกำกับภาษี -->
                                        <td class="border border-slate-200 py-2 px-1 text-center bg-white">
                                            <input type="checkbox" 
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                                {{ $step8 ? 'checked' : '' }} 
                                                onclick="confirmStepUpdate({{ $req->id }}, 'step8', 'ส่งใบกำกับภาษี', this)">
                                        </td>

                                        <!-- ผลการดำเนินงาน (Progress Bar) -->
                                        <td class="border border-slate-200 py-2 px-2 text-center bg-slate-50/50">
                                            <div class="flex items-center gap-1 justify-center flex-col font-sans">
                                                <span class="font-bold text-[10px] text-slate-700">{{ number_format($progress, 0) }}%</span>
                                                <div class="w-12 h-1.5 bg-slate-200 rounded overflow-hidden">
                                                    <div class="h-full @if($progress == 100) bg-emerald-600 @elseif($req->status === 'rejected') bg-rose-600 @else bg-blue-600 @endif" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- งบประมาณ (ตั้ง) -->
                                        <td class="border border-slate-200 py-2 px-2 text-right font-bold text-slate-700 whitespace-nowrap bg-slate-50/30">
                                            ฿{{ number_format($req->estimated_budget, 2) }}
                                        </td>

                                        <!-- ใช้จริง -->
                                        <td class="border border-slate-200 py-2 px-2 text-right font-bold text-emerald-700 whitespace-nowrap bg-slate-50/30">
                                            ฿{{ number_format($req->approved_budget ?? 0, 2) }}
                                        </td>

                                        <!-- วันที่เริ่มดำเนินการ -->
                                        <td class="border border-slate-200 py-2 px-2 text-center text-slate-600 whitespace-nowrap text-[10px] bg-white">
                                            {{ $req->created_at->format('d/m/') . ($req->created_at->format('Y') + 543) }}
                                        </td>

                                        <!-- เลขที่ PO / ดำเนินการจริง -->
                                        <td class="border border-slate-200 py-2 px-2 text-center text-slate-600 whitespace-nowrap text-[10px] bg-white">
                                            @if($req->purchaseOrders->isNotEmpty())
                                                {{ $req->purchaseOrders->first()->po_no }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <!-- วันสิ้นสุดสัญญา/หมดอายุ -->
                                        <td class="border border-slate-200 py-2 px-2 text-center text-slate-800 font-semibold whitespace-nowrap text-[10px] bg-slate-50/10">
                                            {{ $req->expected_date ? $req->expected_date->format('d/m/') . ($req->expected_date->format('Y') + 543) : '-' }}
                                        </td>

                                        <!-- ต่ออายุครั้งถัดไป -->
                                        <td class="border border-slate-200 py-2 px-2 text-center text-amber-700 font-semibold whitespace-nowrap text-[10px] bg-amber-50/20">
                                            {{ $req->next_renewal_date ? $req->next_renewal_date->format('d/m/') . ($req->next_renewal_date->format('Y') + 543) : '-' }}
                                        </td>

                                        <!-- หมายเหตุ -->
                                        <td class="border border-slate-200 py-2 px-3 text-slate-600 text-[10px] font-sans break-words bg-white">
                                            {{ $req->description ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const savedRole = localStorage.getItem('procurement_tracking_role');
            const savedStatus = localStorage.getItem('procurement_tracking_status');
            
            let shouldFilter = false;
            
            if (savedRole) {
                const roleEl = document.getElementById('role-filter');
                if (roleEl) {
                    roleEl.value = savedRole;
                    shouldFilter = true;
                }
            }
            if (savedStatus) {
                const statusEl = document.getElementById('status-filter');
                if (statusEl) {
                    statusEl.value = savedStatus;
                    shouldFilter = true;
                }
            }
            
            if (shouldFilter) {
                filterTable();
            }
        });

        function filterTable() {
            const role = document.getElementById('role-filter').value;
            const status = document.getElementById('status-filter').value;
            
            // Save to localStorage so it persists across reloads
            localStorage.setItem('procurement_tracking_role', role);
            localStorage.setItem('procurement_tracking_status', status);
            
            // First, filter data rows
            const rows = document.querySelectorAll('tbody tr.data-row');
            rows.forEach(row => {
                const owner = row.getAttribute('data-current-owner');
                const group = row.getAttribute('data-group');
                
                const matchRole = (role === 'all' || owner === role);
                const matchStatus = (status === 'all' || group === status);
                
                if (matchRole && matchStatus) {
                    row.style.display = '';
                    row.classList.remove('filtered-out');
                } else {
                    row.style.display = 'none';
                    row.classList.add('filtered-out');
                }
            });
            
            // Then, update headers and empty rows
            const groups = ['in_progress', 'completed', 'rejected'];
            groups.forEach(g => {
                const header = document.querySelector(`tr.group-header[data-group="${g}"]`);
                const emptyRow = document.querySelector(`tr.empty-row[data-group="${g}"]`);
                
                const matchStatus = (status === 'all' || g === status);
                if (header) {
                    header.style.display = matchStatus ? '' : 'none';
                }
                
                if (matchStatus) {
                    const groupRows = document.querySelectorAll(`tr.data-row[data-group="${g}"]`);
                    const visibleRows = Array.from(groupRows).filter(r => !r.classList.contains('filtered-out'));
                    
                    if (emptyRow) {
                        if (groupRows.length === 0) {
                            emptyRow.style.display = '';
                            emptyRow.querySelector('td').innerText = 'ไม่มีรายการในกลุ่มนี้';
                        } else {
                            emptyRow.style.display = visibleRows.length === 0 ? '' : 'none';
                            if (visibleRows.length === 0) {
                                emptyRow.querySelector('td').innerText = 'ไม่มีรายการที่ตรงกับเงื่อนไขที่ค้นหา';
                            }
                        }
                    }
                } else {
                    if (emptyRow) emptyRow.style.display = 'none';
                }
            });
        }

        function confirmStepUpdate(id, step, stepName, checkbox) {
            const isChecked = checkbox.checked;
            
            // Revert state temporarily so it only updates after confirmation
            checkbox.checked = !isChecked;

            const promptText = isChecked 
                ? `คุณต้องการปรับปรุงขั้นตอนการดำเนินงานของคำขอนี้เป็น "${stepName}" ใช่หรือไม่?`
                : `คุณต้องการยกเลิก/ย้อนกลับขั้นตอน "${stepName}" ใช่หรือไม่?`;

            Swal.fire({
                title: 'ยืนยันการเปลี่ยนสถานะ',
                text: promptText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1d4ed8',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ใช่, ดำเนินการ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'กำลังบันทึกข้อมูล...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Perform fetch API call
                    fetch(`/procurements/${id}/update-step`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            step: step,
                            checked: isChecked
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ',
                                text: 'ปรับปรุงสถานะการดำเนินงานเรียบร้อยแล้ว',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload page to reflect changes
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating step:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถเปลี่ยนสถานะได้ กรุณาลองใหม่อีกครั้ง'
                        });
                    });
                }
            });
        }
    </script>

@endsection
