@extends('layouts.app')

@section('title', 'แผงควบคุมระบบ')
@section('page_title', 'แผงควบคุมหลัก (Dashboard)')

@section('content')

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        <!-- Total Requests -->
        <div class="bg-white border border-slate-200 p-5 rounded-lg hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">คำขอทั้งหมดของคุณ</span>
                <span class="p-1.5 rounded bg-slate-100 text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">รายการคำขอจัดซื้อในระบบ</p>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white border border-slate-200 p-5 rounded-lg hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">อยู่ระหว่างดำเนินการ</span>
                <span class="p-1.5 rounded bg-amber-50 text-amber-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">รอการตรวจสอบ/จัดซื้อ</p>
        </div>

        <!-- Completed Budget Spent -->
        <div class="bg-white border border-slate-200 p-5 rounded-lg hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">อนุมัติจัดซื้อแล้ว</span>
                <span class="p-1.5 rounded bg-emerald-50 text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-emerald-700">฿{{ number_format($stats['budget_spent'], 2) }}</p>
            <p class="text-[11px] text-slate-400 mt-1">มูลค่ารวมงบประมาณที่ใช้จริง</p>
        </div>

        <!-- Department Budget Remaining -->
        <div class="bg-white border border-slate-200 p-5 rounded-lg hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">งบคงเหลือ (แผนกคุณ)</span>
                <span class="p-1.5 rounded bg-blue-50 text-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-blue-700">
                ฿{{ number_format($budget ? $budget->remaining_budget : 0, 2) }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">ปีงบประมาณ {{ $budget ? $budget->fiscal_year : 2026 }}</p>
        </div>

    </div>

    @if($licenseAlertsCount > 0)
        <!-- Software License Warning Notification Alert -->
        <div class="mb-8 p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="p-2 rounded bg-amber-100 text-amber-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </span>
                <div>
                    <h4 class="font-bold text-sm">การแจ้งเตือนสัญญาลิขสิทธิ์ซอฟต์แวร์ (License Alerts)</h4>
                    <p class="text-xs text-slate-600 mt-0.5">มีทั้งหมด {{ $licenseAlertsCount }} รายการสัญญากำลังจะหมดอายุภายใน 30 วัน กรุณาตรวจสอบแผนการดำเนินงานจัดซื้อต่ออายุ</p>
                </div>
            </div>
            <a href="{{ route('licenses.index') }}" class="px-3.5 py-1.5 bg-amber-700 hover:bg-amber-600 text-white text-xs font-bold rounded transition-colors whitespace-nowrap">ตรวจสอบเลย</a>
        </div>
    @endif

    <!-- Procurement & Renewal Tracking Timeline Table (Hospital/Clinical Style Grid) -->
    <div class="bg-white border border-slate-200 rounded-lg p-6 mb-8">
        <div class="mb-4">
            <h3 class="text-base font-bold text-slate-900">ตารางแสดงความคืบหน้าการสั่งซื้อ ต่ออายุการใช้งานอุปกรณ์ และโปรแกรมต่างๆ</h3>
            <p class="text-xs text-slate-500 mt-0.5">ติดตามขั้นตอนการดำเนินงานเสนอราคา, การอนุมัติงบประมาณ CAO, การออกเลข PR/PO และการตรวจสอบเอกสารบัญชี</p>
        </div>

        @if($allRequests->isEmpty())
            <div class="py-10 text-center border border-dashed border-slate-200 rounded-lg">
                <p class="text-xs text-slate-400">ไม่มีข้อมูลรายการจัดซื้อในขณะนี้</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1200px] text-xs border border-slate-200">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th rowspan="2" class="border border-slate-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap bg-slate-100/80">ลำดับ</th>
                            <th rowspan="2" class="border border-slate-200 py-2.5 px-3 text-left align-middle font-bold min-w-[180px] bg-slate-100/80">รายการ / เลขที่คำขอ</th>
                            <th rowspan="2" class="border border-slate-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap bg-slate-100/80">ขอใบ<br>เสนอราคา</th>
                            <th colspan="3" class="border border-slate-200 py-2 px-2 text-center align-middle font-bold">ขออนุมัติการดำเนินการ</th>
                            <th colspan="3" class="border border-slate-200 py-2 px-2 text-center align-middle font-bold">เปิด PR / PO</th>
                            <th colspan="2" class="border border-slate-200 py-2 px-2 text-center align-middle font-bold">ส่งเอกสารให้บัญชี</th>
                            <th rowspan="2" class="border border-slate-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap bg-slate-100/80">ผลการดำเนินงาน</th>
                            <th colspan="2" class="border border-slate-200 py-2 px-2 text-center align-middle font-bold">จำนวนเงิน</th>
                            <th rowspan="2" class="border border-slate-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap bg-slate-100/80">วันที่เริ่ม<br>ดำเนินการ</th>
                            <th rowspan="2" class="border border-slate-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap bg-slate-100/80">เลขที่ PO /<br>ดำเนินการจริง</th>
                            <th rowspan="2" class="border border-slate-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap bg-slate-100/80">วันที่หมดอายุ</th>
                            <th rowspan="2" class="border border-slate-200 py-2.5 px-2 text-center align-middle font-bold whitespace-nowrap bg-slate-100/80">ต่ออายุครั้งถัดไป</th>
                            <th rowspan="2" class="border border-slate-200 py-2.5 px-3 text-left align-middle font-bold min-w-[150px] bg-slate-100/80">หมายเหตุ</th>
                        </tr>
                        <tr class="bg-slate-50 text-slate-600 text-[10px]">
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">จัดทำ</th>
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">Manager ICT</th>
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">CAO อนุมัติ</th>
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">จัดทำ</th>
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">Manager ICT</th>
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">CAO อนุมัติ</th>
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">ส่งเอกสาร SUP</th>
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">ส่งใบกำกับภาษี</th>
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">งบประมาณ (ตั้ง)</th>
                            <th class="border border-slate-200 py-2 px-2 text-center align-middle font-bold whitespace-nowrap">ใช้จริง</th>
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
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- ลำดับ -->
                                <td class="border border-slate-200 py-2 px-2 text-center text-slate-800 font-semibold bg-slate-50/50">{{ $index + 1 }}</td>
                                
                                <!-- รายการ / เลขที่คำขอ -->
                                <td class="border border-slate-200 py-2 px-3 font-semibold text-slate-900 font-sans">
                                    <a href="{{ route('procurements.show', $req->id) }}" class="hover:text-blue-700 transition-colors text-xs block">
                                        {{ $req->title }}
                                    </a>
                                    <span class="text-[10px] text-slate-500 font-mono mt-0.5 block">{{ $req->request_no }}</span>
                                </td>
                                
                                <!-- ขอใบเสนอราคา -->
                                <td class="border border-slate-200 py-2 px-1 text-center bg-slate-50/30">
                                    @if($step1)
                                        <span class="text-blue-700 font-bold">✔</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- จัดทำ -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    @if($step2)
                                        <span class="text-blue-700 font-bold">✔</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- Manager/ICT -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    @if($step3)
                                        <span class="text-blue-700 font-bold">✔</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- CAO -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    @if($step4)
                                        <span class="text-blue-700 font-bold">✔</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- จัดทำ PR/PO -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    @if($step5)
                                        <span class="text-blue-700 font-bold">✔</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- Manager ICT อนุมัติ PR/PO -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    @if($step_pr_ict)
                                        <span class="text-blue-700 font-bold">✔</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- CAO อนุมัติ PR/PO -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    @if($step_pr_cao)
                                        <span class="text-blue-700 font-bold">✔</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- ส่งเอกสารให้ SUP -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    @if($step6)
                                        <span class="text-blue-700 font-bold">✔</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- ส่งใบกำกับภาษี -->
                                <td class="border border-slate-200 py-2 px-1 text-center">
                                    @if($step8)
                                        <span class="text-blue-700 font-bold">✔</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
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
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Main Tasks / Recent Actions -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Pending Approvals section -->
            <div class="bg-white border border-slate-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">งานที่รอดำเนินการอนุมัติ (Your Pending Tasks)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">รายการจัดซื้อที่คุณต้องดำเนินการตรวจสอบ อนุมัติ หรือออกเอกสาร</p>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-bold bg-amber-50 border border-amber-200 text-amber-800 rounded">
                        {{ $pendingApprovals->count() }} งาน
                    </span>
                </div>

                @if($pendingApprovals->isEmpty())
                    <div class="py-8 text-center border border-dashed border-slate-200 rounded-lg">
                        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-slate-400">ไม่มีภารกิจรอดำเนินการของคุณในขณะนี้</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($pendingApprovals as $request)
                            <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 hover:border-slate-300 transition-colors flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-mono font-bold text-blue-800 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200">{{ $request->request_no }}</span>
                                        <span class="text-[11px] text-slate-500">แผนก: {{ $request->department->name }}</span>
                                    </div>
                                    <h4 class="font-bold text-slate-800 text-sm">{{ $request->title }}</h4>
                                    <p class="text-[11px] text-slate-500">ผู้ขอ: {{ $request->requester->name }} • วงเงินประเมิน: <span class="text-slate-800 font-semibold">฿{{ number_format($request->estimated_budget, 2) }}</span></p>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('procurements.show', $request->id) }}" class="inline-block px-3 py-1.5 bg-blue-700 hover:bg-blue-600 text-white font-bold rounded text-xs transition-colors">
                                        เปิดพิจารณา
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Requests -->
            <div class="bg-white border border-slate-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">รายการคำขอล่าสุด (Recent Activities)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">รายการจัดซื้อจัดจ้างที่คุณสร้างหรือมีสิทธิ์เข้าถึงพิจารณาล่าสุด</p>
                    </div>
                    <a href="{{ route('procurements.index') }}" class="text-xs font-bold text-blue-700 hover:underline">ดูทั้งหมด →</a>
                </div>

                @if($recentRequests->isEmpty())
                    <div class="py-8 text-center border border-dashed border-slate-200 rounded-lg">
                        <p class="text-xs text-slate-400">ไม่มีข้อมูลการทำรายการคำขอจัดซื้อ</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse border border-slate-200">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-3 py-2 text-[11px] font-bold text-slate-600 uppercase tracking-wider">รหัสคำขอ / ชื่อเรื่อง</th>
                                    <th class="px-3 py-2 text-[11px] font-bold text-slate-600 uppercase tracking-wider">ความเร่งด่วน</th>
                                    <th class="px-3 py-2 text-[11px] font-bold text-slate-600 uppercase tracking-wider text-right">งบประมาณ</th>
                                    <th class="px-3 py-2 text-[11px] font-bold text-slate-600 uppercase tracking-wider text-center">สถานะ</th>
                                    <th class="px-3 py-2 w-10 border-l border-slate-200"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-xs">
                                @foreach($recentRequests as $request)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-3 py-3 border-r border-slate-200">
                                            <div class="space-y-0.5">
                                                <p class="text-[10px] font-mono font-bold text-slate-500">{{ $request->request_no }}</p>
                                                <p class="font-semibold text-slate-800 line-clamp-1" title="{{ $request->title }}">{{ $request->title }}</p>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap border-r border-slate-200">
                                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded uppercase border 
                                                @if($request->priority === 'urgent') bg-rose-50 text-rose-800 border-rose-200
                                                @elseif($request->priority === 'high') bg-amber-50 text-amber-800 border-amber-200
                                                @elseif($request->priority === 'medium') bg-blue-50 text-blue-800 border-blue-200
                                                @else bg-slate-50 text-slate-600 border-slate-200 @endif">
                                                {{ $request->priority }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 text-right font-bold text-slate-700 whitespace-nowrap border-r border-slate-200">
                                            ฿{{ number_format($request->estimated_budget, 2) }}
                                        </td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">
                                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded border
                                                @if($request->status === 'completed') bg-emerald-50 text-emerald-800 border-emerald-200
                                                @elseif($request->status === 'rejected') bg-rose-50 text-rose-800 border-rose-200
                                                @elseif($request->status === 'draft') bg-slate-50 text-slate-600 border-slate-200
                                                @else bg-amber-50 text-amber-800 border-amber-200 @endif">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">
                                            <a href="{{ route('procurements.show', $request->id) }}" class="text-slate-400 hover:text-slate-700 inline-block p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right 1 Col: Quick Actions / Budget Chart details -->
        <div class="space-y-6">

            <!-- Status Tracker (Bottleneck Analysis) -->
            <div class="bg-white border border-slate-200 rounded-lg p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">ติดตามสถานะการอนุมัติ (ค้างอยู่ส่วนไหน?)</h3>
                <div class="space-y-3.5">
                    @foreach($statusTracker as $label => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-600">{{ $label }}</span>
                            <div class="flex items-center gap-3">
                                <div class="w-20 h-1.5 bg-slate-100 rounded overflow-hidden">
                                    <div class="h-full @if($count > 0) bg-amber-500 @else bg-slate-200 @endif" style="width: {{ $stats['pending'] > 0 ? ($count / $stats['pending']) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-xs font-bold w-4 text-right {{ $count > 0 ? 'text-amber-700' : 'text-slate-400' }}">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Department Budget Healthcard -->
            <div class="bg-white border border-slate-200 rounded-lg p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">ความพร้อมงบประมาณแผนกคุณ</h3>
                
                @if($budget)
                    @php
                        $percentage = $budget->allocated_budget > 0 ? ($budget->used_budget / $budget->allocated_budget) * 100 : 0;
                    @endphp
                    <div class="space-y-5">
                        <div class="flex items-end justify-between">
                            <div>
                                <p class="text-2xl font-bold text-slate-900">฿{{ number_format($budget->used_budget, 2) }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">จากทั้งหมด ฿{{ number_format($budget->allocated_budget, 2) }}</p>
                            </div>
                            <span class="text-xs font-bold text-blue-700">{{ number_format($percentage, 1) }}% ถูกใช้</span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full h-2 rounded bg-slate-100 overflow-hidden border border-slate-200/50">
                            <div class="h-full bg-blue-700" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 text-xs">
                            <div>
                                <p class="text-slate-500">งบประมาณจัดสรร</p>
                                <p class="font-bold text-slate-700 mt-0.5">฿{{ number_format($budget->allocated_budget, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">งบประมาณคงเหลือ</p>
                                <p class="font-bold text-blue-700 mt-0.5">฿{{ number_format($budget->remaining_budget, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="py-4 text-center">
                        <p class="text-xs text-slate-400">ยังไม่มีการอนุมัติหรือจัดสรรงบประมาณให้แผนกของคุณสำหรับปีงบประมาณนี้</p>
                    </div>
                @endif
            </div>

        </div>

    </div>

@endsection
