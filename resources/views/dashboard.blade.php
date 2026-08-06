@extends('layouts.app')

@section('title', 'แผงควบคุมระบบ')
@section('page_title', 'แผงควบคุมหลัก (Dashboard)')

@section('content')

    <div class="max-w-8xl mx-auto w-full px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-6 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-slate-800" style="letter-spacing:-0.02em">ภาพรวมระบบจัดซื้อ</h2>
                <p class="text-sm text-slate-500 mt-0.5">สรุปภาพรวม งานค้างอยู่ที่ไหนมากสุด และสถิติภาพรวมของปี</p>
            </div>
        </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

        <!-- Total Requests -->
        <div class="group bg-white rounded-xl border border-slate-200 px-6 py-5 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 mb-3 tracking-wide">คำขอทั้งหมด</p>
                    <p class="text-3xl font-bold tabular-nums text-slate-800" style="letter-spacing:-0.03em">{{ $stats['total'] }}</p>
                    <p class="text-xs mt-2 text-slate-400">รายการในระบบทั้งหมด</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-100 transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="group bg-white rounded-xl border border-slate-200 px-6 py-5 shadow-sm hover:shadow-md hover:border-amber-200 transition-all duration-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 mb-3 tracking-wide">อยู่ระหว่างดำเนินการ</p>
                    <p class="text-3xl font-bold tabular-nums text-amber-600" style="letter-spacing:-0.03em">{{ $stats['pending'] }}</p>
                    @if($stats['pending'] > 0)
                        <p class="text-xs mt-2 text-slate-400 tabular-nums">
                            เฉลี่ย <span class="font-semibold text-slate-600">{{ $stats['avg_pending_days'] }}</span> วัน
                            &middot; นานสุด <span class="font-semibold text-slate-600">{{ $stats['oldest_pending_days'] }}</span> วัน
                        </p>
                    @else
                        <p class="text-xs mt-2 text-slate-400">รอการตรวจสอบ / จัดซื้อ</p>
                    @endif
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-100 transition-colors">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="group bg-white rounded-xl border border-slate-200 px-6 py-5 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 mb-3 tracking-wide">อนุมัติจัดซื้อแล้ว</p>
                    <p class="text-3xl font-bold tabular-nums text-emerald-600" style="letter-spacing:-0.03em">{{ $stats['completed'] }}</p>
                    <p class="text-xs mt-2 text-slate-400">รายการที่อนุมัติแล้ว</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12.75l2.25 2.25 4.5-4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottleneck Analysis (Full Width) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-5 flex flex-col overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 19V6l5.7 6.9L11 19zm0 0H5.5a2.5 2.5 0 010-5H10m1 5v-6" />
                </svg>
                <h3 class="text-sm font-semibold text-slate-700">วิเคราะห์ความล่าช้า</h3>
            </div>
            <span class="text-[11px] font-medium text-slate-400 bg-white border border-slate-200 rounded-full px-2.5 py-1">Bottleneck Analysis</span>
        </div>
        <div class="relative flex-grow min-h-[260px] w-full flex items-center justify-center p-5">
            <canvas id="bottleneckChart"></canvas>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
        <!-- Status Overview -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 3.055A9 9 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    <h3 class="text-sm font-semibold text-slate-700">ภาพรวมสถานะ</h3>
                </div>
                <span class="text-[11px] font-medium text-slate-400 bg-white border border-slate-200 rounded-full px-2.5 py-1">Status Overview</span>
            </div>
            <div class="relative flex-grow min-h-[220px] w-full flex items-center justify-center p-5">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Budget Utilization -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .672-3 1.5S10.343 11 12 11s3 .672 3 1.5-1.343 1.5-3 1.5m0-6V6m0 1.5v8m0 1.5v-1.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-sm font-semibold text-slate-700">การใช้งบประมาณ</h3>
                </div>
                <span class="text-[11px] font-medium text-slate-400 bg-white border border-slate-200 rounded-full px-2.5 py-1">Budget Utilization</span>
            </div>
            <div class="relative flex-grow min-h-[220px] w-full flex flex-col items-center justify-center p-5">
                <canvas id="budgetChart"></canvas>
                <div class="absolute mt-6 text-center pointer-events-none">
                    <p class="text-[11px] text-slate-500 font-medium">ใช้ไปแล้ว</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $budget && ($budget->remaining_budget + $stats['budget_spent']) > 0 ? number_format(($stats['budget_spent'] / ($budget->remaining_budget + $stats['budget_spent'])) * 100, 1) : 0 }}%</p>
                </div>
            </div>
        </div>
    </div>



    @if($licenseAlertsCount > 0)
        <!-- License Expiry Alert -->
        <div class="mb-5 bg-white border border-red-100 rounded-xl shadow-sm p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center text-red-600 mt-0.5">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">ลิขสิทธิ์ซอฟต์แวร์ใกล้หมดอายุ</p>
                    <p class="text-xs text-slate-500 mt-0.5">ตรวจพบ <span class="font-semibold text-red-600">{{ $licenseAlertsCount }}</span> รายการจะหมดอายุภายใน 30 วัน กรุณาตรวจสอบและดำเนินการทันที</p>
                </div>
            </div>
            <a href="{{ route('licenses.index') }}" class="flex-shrink-0 px-3.5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors duration-150 whitespace-nowrap shadow-sm">ตรวจสอบรายการ</a>
        </div>
    @endif

    <!-- Procurement & Renewal Tracking Timeline Table (Hospital/Clinical Style Grid) -->
    {{-- <div class="bg-white border border-slate-200 shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-2xl overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-900 to-indigo-800 px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white">
            <div>
                <h3 class="text-base font-bold flex items-center gap-2.5">
                    <div class="p-1.5 bg-white/10 rounded-lg backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                    </div>
                    ตารางบันทึกการติดตามความคืบหน้า
                </h3>
            </div>
            
            <!-- User/Role Filter Dropdown -->
            <div class="flex items-center gap-3 self-start md:self-auto bg-white/10 border border-white/20 rounded-xl px-4 py-2 backdrop-blur-sm">
                <label for="role-filter" class="text-xs font-bold text-blue-100 whitespace-nowrap">แสดงขั้นตอนของ:</label>
                <select id="role-filter" onchange="filterByRole(this.value)" class="bg-white/90 border-0 rounded-lg px-3 py-1.5 text-xs text-blue-900 font-bold focus:ring-2 focus:ring-white/50 cursor-pointer outline-none">
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

        @if($allRequests->isEmpty())
            <div class="p-12 text-center bg-slate-50">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm mb-4">
                    <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <p class="text-slate-500 text-sm font-semibold">ไม่มีข้อมูลรายการจัดซื้อในขณะนี้</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1200px] text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700 border-b border-slate-200">
                            <th rowspan="2" class="border-r border-slate-200 py-3 px-3 text-center align-middle font-bold whitespace-nowrap">ลำดับ</th>
                            <th rowspan="2" class="border-r border-slate-200 py-3 px-4 text-left align-middle font-bold min-w-[200px]">รายการ / เลขที่คำขอ</th>
                            <th rowspan="2" class="border-r border-slate-200 py-3 px-2 text-center align-middle font-bold whitespace-nowrap">ขอใบ<br>เสนอราคา</th>
                            <th colspan="3" class="border-r border-slate-200 py-2.5 px-2 text-center align-middle font-bold bg-blue-50/50">ขออนุมัติการดำเนินการ</th>
                            <th colspan="3" class="border-r border-slate-200 py-2.5 px-2 text-center align-middle font-bold bg-indigo-50/50">เปิด PR / PO</th>
                            <th colspan="2" class="border-r border-slate-200 py-2.5 px-2 text-center align-middle font-bold bg-emerald-50/50">ส่งเอกสารให้บัญชี</th>
                            <th rowspan="2" class="border-r border-slate-200 py-3 px-3 text-center align-middle font-bold whitespace-nowrap">ผลการดำเนินงาน</th>
                            <th colspan="2" class="border-r border-slate-200 py-2.5 px-2 text-center align-middle font-bold">จำนวนเงิน</th>
                            <th rowspan="2" class="border-r border-slate-200 py-3 px-3 text-center align-middle font-bold whitespace-nowrap">วันที่เริ่ม<br>ดำเนินการ</th>
                            <th rowspan="2" class="border-r border-slate-200 py-3 px-3 text-center align-middle font-bold whitespace-nowrap">เลขที่ PO /<br>ดำเนินการจริง</th>
                            <th rowspan="2" class="border-r border-slate-200 py-3 px-3 text-center align-middle font-bold whitespace-nowrap">วันที่หมดอายุ</th>
                            <th rowspan="2" class="border-r border-slate-200 py-3 px-3 text-center align-middle font-bold whitespace-nowrap">ต่ออายุครั้งถัดไป</th>
                            <th rowspan="2" class="py-3 px-4 text-left align-middle font-bold min-w-[150px]">หมายเหตุ</th>
                        </tr>
                        <tr class="bg-white text-slate-500 text-[10px] border-b border-slate-200 uppercase tracking-wider">
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-blue-50/20">จัดทำ</th>
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-blue-50/20">Manager ICT</th>
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-blue-50/20">CAO อนุมัติ</th>
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-indigo-50/20">จัดทำ</th>
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-indigo-50/20">Manager ICT</th>
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-indigo-50/20">CAO อนุมัติ</th>
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-emerald-50/20">ส่งเอกสาร SUP</th>
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-emerald-50/20">ส่งใบกำกับภาษี</th>
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-slate-50/50">งบประมาณ (ตั้ง)</th>
                            <th class="border-r border-slate-200 py-2 px-2 text-center align-middle font-semibold whitespace-nowrap bg-slate-50/50">ใช้จริง</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 font-mono">
                        @foreach($allRequests as $index => $req)
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
                                $step7 = in_array($req->status, ['po_created', 'delivered', 'completed']);
                                $step8 = in_array($req->status, ['delivered', 'completed']);
                                $step9 = in_array($req->status, ['completed']);

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
                            <tr class="hover:bg-slate-50/80 transition-colors" data-current-owner="{{ $currentOwner }}">
                                <!-- ลำดับ -->
                                <td class="border border-slate-200 py-2 px-2 text-center text-slate-800 font-semibold bg-slate-50/50">{{ $index + 1 }}</td>
                                
                                <!-- รายการ / เลขที่คำขอ -->
                                <td class="border border-slate-200 py-2 px-3 font-semibold text-slate-900 font-sans">
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
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    <input type="checkbox" 
                                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                           {{ $step2 ? 'checked' : '' }} 
                                           onclick="confirmStepUpdate({{ $req->id }}, 'step2', 'ขออนุมัติการดำเนินการ: จัดทำ', this)">
                                </td>

                                <!-- Manager/ICT -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    <input type="checkbox" 
                                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                           {{ $step3 ? 'checked' : '' }} 
                                           onclick="confirmStepUpdate({{ $req->id }}, 'step3', 'ขออนุมัติการดำเนินการ: Manager ICT', this)">
                                </td>

                                <!-- CAO -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    <input type="checkbox" 
                                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                           {{ $step4 ? 'checked' : '' }} 
                                           onclick="confirmStepUpdate({{ $req->id }}, 'step4', 'ขออนุมัติการดำเนินการ: CAO อนุมัติ', this)">
                                </td>

                                <!-- จัดทำ PR/PO -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    <input type="checkbox" 
                                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                           {{ $step5 ? 'checked' : '' }} 
                                           onclick="confirmStepUpdate({{ $req->id }}, 'step5', 'เปิด PR/PO: จัดทำ', this)">
                                </td>

                                <!-- Manager ICT อนุมัติ PR/PO -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    <input type="checkbox" 
                                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                           {{ $step_pr_ict ? 'checked' : '' }} 
                                           onclick="confirmStepUpdate({{ $req->id }}, 'step_pr_ict', 'เปิด PR/PO: Manager ICT อนุมัติ', this)">
                                </td>

                                <!-- CAO อนุมัติ PR/PO -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    <input type="checkbox" 
                                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                           {{ $step_pr_cao ? 'checked' : '' }} 
                                           onclick="confirmStepUpdate({{ $req->id }}, 'step_pr_cao', 'เปิด PR/PO: CAO อนุมัติ', this)">
                                </td>

                                <!-- ส่งเอกสารให้ SUP -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    <input type="checkbox" 
                                           class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all duration-200" 
                                           {{ $step6 ? 'checked' : '' }} 
                                           onclick="confirmStepUpdate({{ $req->id }}, 'step6', 'ส่งเอกสารให้ SUP', this)">
                                </td>

                                <!-- ส่งใบกำกับภาษี -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
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
                                <td class="border border-slate-200 py-2 px-2 text-center text-slate-600 whitespace-nowrap text-[10px]">
                                    {{ $req->created_at->format('d/m/') . ($req->created_at->format('Y') + 543) }}
                                </td>

                                <!-- เลขที่ PO / ดำเนินการจริง -->
                                <td class="border border-slate-200 py-2 px-2 text-center text-slate-600 whitespace-nowrap text-[10px]">
                                    -
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
                                <td class="border border-slate-200 py-2 px-3 text-slate-600 text-[10px] font-sans break-words">
                                    {{ $req->description ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div> --}}



    </div>

    <script>
        function filterByRole(role) {
            const rows = document.querySelectorAll('tbody tr[data-current-owner]');
            rows.forEach(row => {
                const owner = row.getAttribute('data-current-owner');
                if (role === 'all' || owner === role) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
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
                                // Reload to reflect status changes and updated budgets/logs
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
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Sarabun', sans-serif";
            Chart.defaults.color = '#64748b';

            const stats = @json($stats);
            const statusTracker = @json($statusTracker);
            const budgetSpent = parseFloat(stats.budget_spent) || 0;
            const remainingBudget = {{ $budget ? $budget->remaining_budget : 0 }};
            const totalBudget = budgetSpent + remainingBudget;

            // 1. Status Overview Chart
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['เสร็จสิ้น', 'กำลังดำเนินการ', 'ยกเลิก'],
                    datasets: [{
                        data: [stats.completed, stats.pending, stats.rejected],
                        backgroundColor: ['#10b981', '#3b82f6', '#f43f5e'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { family: 'Sarabun' }, boxWidth: 10, padding: 16, usePointStyle: true, pointStyle: 'circle' } }
                    },
                    cutout: '70%'
                }
            });

            // 2. Bottleneck Analysis Chart
            const ctxBottleneck = document.getElementById('bottleneckChart').getContext('2d');
            new Chart(ctxBottleneck, {
                type: 'bar',
                data: {
                    labels: Object.keys(statusTracker),
                    datasets: [{
                        label: 'รายการ',
                        data: Object.values(statusTracker),
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        maxBarThickness: 22,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                        y: { ticks: { font: { family: 'Sarabun', size: 11 } }, grid: { display: false } }
                    }
                }
            });

            // 3. Budget Utilization Chart (Half-Doughnut)
            const ctxBudget = document.getElementById('budgetChart').getContext('2d');
            new Chart(ctxBudget, {
                type: 'doughnut',
                data: {
                    labels: ['งบที่ใช้ไป', 'งบคงเหลือ'],
                    datasets: [{
                        data: [budgetSpent, remainingBudget],
                        backgroundColor: ['#10b981', '#e2e8f0'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    rotation: -90,
                    circumference: 180,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed !== null) {
                                        label += new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB' }).format(context.parsed);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection