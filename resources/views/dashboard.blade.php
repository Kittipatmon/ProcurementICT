@extends('layouts.app')

@section('title', 'แผงควบคุมระบบ')
@section('page_title', 'แผงควบคุมหลัก (Dashboard)')

@section('content')

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <!-- Total Requests -->
        <div class="bg-white border border-slate-200 p-6 rounded-3xl relative overflow-hidden group hover:border-indigo-500/30 hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/[0.02] rounded-full blur-xl group-hover:bg-indigo-500/5 transition-all duration-300"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">คำขอทั้งหมดของคุณ</span>
                <span class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-800">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-500 mt-2">รายการคำขอจัดซื้อในระบบ</p>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white border border-slate-200 p-6 rounded-3xl relative overflow-hidden group hover:border-amber-500/30 hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/[0.02] rounded-full blur-xl group-hover:bg-amber-500/5 transition-all duration-300"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">อยู่ระหว่างดำเนินการ</span>
                <span class="p-2 rounded-lg bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-amber-600">{{ $stats['pending'] }}</p>
            <p class="text-xs text-slate-500 mt-2">รอการตรวจสอบ/จัดซื้อ</p>
        </div>

        <!-- Completed Budget Spent -->
        <div class="bg-white border border-slate-200 p-6 rounded-3xl relative overflow-hidden group hover:border-emerald-500/30 hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/[0.02] rounded-full blur-xl group-hover:bg-emerald-500/5 transition-all duration-300"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">อนุมัติจัดซื้อแล้ว</span>
                <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-emerald-600">฿{{ number_format($stats['budget_spent'], 2) }}</p>
            <p class="text-xs text-slate-500 mt-2">มูลค่ารวมงบประมาณที่ใช้จริง</p>
        </div>

        <!-- Department Budget Remaining -->
        <div class="bg-white border border-slate-200 p-6 rounded-3xl relative overflow-hidden group hover:border-fuchsia-500/30 hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 bg-fuchsia-500/[0.02] rounded-full blur-xl group-hover:bg-fuchsia-500/5 transition-all duration-300"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">งบคงเหลือ (แผนกคุณ)</span>
                <span class="p-2 rounded-lg bg-fuchsia-50 text-fuchsia-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-fuchsia-600">
                ฿{{ number_format($budget ? $budget->remaining_budget : 0, 2) }}
            </p>
            <p class="text-xs text-slate-500 mt-2">ปีงบประมาณ {{ $budget ? $budget->fiscal_year : 2026 }}</p>
        </div>

    </div>

    @if($licenseAlertsCount > 0)
        <!-- Software License Warning Notification Alert -->
        <div class="mb-10 p-5 rounded-3xl bg-amber-50 border border-amber-200 text-amber-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <span class="p-2.5 rounded-xl bg-amber-100 text-amber-600">
                    <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </span>
                <div>
                    <h4 class="font-bold text-sm">การแจ้งเตือนสัญญาลิขสิทธิ์ซอฟต์แวร์ (License Alerts)</h4>
                    <p class="text-xs text-slate-500 mt-0.5">มีทั้งหมด {{ $licenseAlertsCount }} รายการสัญญากำลังจะหมดอายุภายใน 30 วัน กรุณาตรวจสอบแผนการดำเนินงานจัดซื้อต่ออายุ</p>
                </div>
            </div>
            <a href="{{ route('licenses.index') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-xl shadow transition-all">ตรวจสอบเลย</a>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Main Tasks / Recent Actions -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Pending Approvals section -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">งานที่รอดำเนินการอนุมัติ (Your Pending Tasks)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">รายการจัดซื้อที่คุณต้องดำเนินการตรวจสอบ อนุมัติ หรือออกเอกสาร</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100 rounded-full">
                        {{ $pendingApprovals->count() }} งานรอดำเนินการ
                    </span>
                </div>

                @if($pendingApprovals->isEmpty())
                    <div class="py-12 text-center">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-semibold text-slate-400">ไม่มีภารกิจรอดำเนินการของคุณในขณะนี้</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($pendingApprovals as $request)
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 hover:border-indigo-500/20 hover:bg-white transition-all flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $request->request_no }}</span>
                                        <span class="text-xs font-semibold text-slate-400">แผนก: {{ $request->department->name }}</span>
                                    </div>
                                    <h4 class="font-bold text-slate-700 text-sm hover:text-slate-800">{{ $request->title }}</h4>
                                    <p class="text-xs text-slate-500">ผู้ขอ: {{ $request->requester->name }} • วงเงินประเมิน: <span class="text-slate-700 font-bold">฿{{ number_format($request->estimated_budget, 2) }}</span></p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('procurements.show', $request->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow transition-all">
                                        เปิดพิจารณา
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Procurement & Renewal Tracking Timeline Table (Premium UI Matching User Image) -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">ตารางแสดงความคืบหน้าการสั่งซื้อ ต่ออายุการใช้งานอุปกรณ์ และโปรแกรมต่างๆ</h3>
                        <p class="text-xs text-slate-500 mt-1">ติดตามขั้นตอนการดำเนินงานเสนอราคา, การอนุมัติงบประมาณ CAO, การออกเลข PR/PO และการตรวจสอบเอกสารบัญชี</p>
                    </div>
                </div>

                @if($allRequests->isEmpty())
                    <p class="text-xs text-slate-400 py-10 text-center">ไม่มีข้อมูลรายการจัดซื้อในขณะนี้</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[1200px] text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="py-3 px-4 font-bold text-slate-700 uppercase tracking-wider text-center">ลำดับ</th>
                                    <th class="py-3 px-4 font-bold text-slate-700 uppercase tracking-wider min-w-[200px]">รายการ</th>
                                    <th class="py-3 px-2 font-bold text-slate-700 uppercase tracking-wider text-center">ขอใบเสนอราคา</th>
                                    <th class="py-3 px-2 font-bold text-slate-700 uppercase tracking-wider text-center">จัดทำคำขอ</th>
                                    <th class="py-3 px-2 font-bold text-slate-700 uppercase tracking-wider text-center">Manager/ICT อนุมัติ</th>
                                    <th class="py-3 px-2 font-bold text-slate-700 uppercase tracking-wider text-center">CAO อนุมัติ</th>
                                    <th class="py-3 px-2 font-bold text-slate-700 uppercase tracking-wider text-center">จัดทำ PR/PO</th>
                                    <th class="py-3 px-2 font-bold text-slate-700 uppercase tracking-wider text-center">จัดซื้ออนุมัติ</th>
                                    <th class="py-3 px-2 font-bold text-slate-700 uppercase tracking-wider text-center">ส่งเอกสารให้ SUP</th>
                                    <th class="py-3 px-2 font-bold text-slate-700 uppercase tracking-wider text-center">ส่งใบกำกับภาษี</th>
                                    <th class="py-3 px-4 font-bold text-slate-700 uppercase tracking-wider text-center">ผลการดำเนินงาน</th>
                                    <th class="py-3 px-4 font-bold text-slate-700 uppercase tracking-wider text-right">งบประมาณ (ตั้ง)</th>
                                    <th class="py-3 px-4 font-bold text-slate-700 uppercase tracking-wider text-right">ใช้จริง</th>
                                    <th class="py-3 px-4 font-bold text-slate-700 uppercase tracking-wider">วันสิ้นสุดสัญญา / หมดอายุ</th>
                                    <th class="py-3 px-4 font-bold text-slate-700 uppercase tracking-wider min-w-[150px]">หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($allRequests as $index => $req)
                                    @php
                                        // Calculate percentage progress based on status steps
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
                                        
                                        // Map checkboxes dynamically based on progress
                                        $step1 = true; // Request Quote is always true if created
                                        $step2 = in_array($req->status, ['submitted', 'approved_manager', 'approved_ict', 'approved_cao', 'pr_created', 'po_created', 'delivered', 'completed']);
                                        $step3 = in_array($req->status, ['approved_manager', 'approved_ict', 'approved_cao', 'pr_created', 'po_created', 'delivered', 'completed']);
                                        $step4 = in_array($req->status, ['approved_ict', 'approved_cao', 'pr_created', 'po_created', 'delivered', 'completed']);
                                        $step5 = in_array($req->status, ['approved_cao', 'pr_created', 'po_created', 'delivered', 'completed']);
                                        $step6 = in_array($req->status, ['pr_created', 'po_created', 'delivered', 'completed']);
                                        $step7 = in_array($req->status, ['po_created', 'delivered', 'completed']);
                                        $step8 = in_array($req->status, ['delivered', 'completed']);
                                        $step9 = in_array($req->status, ['completed']);
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <!-- ลำดับ -->
                                        <td class="py-4 px-4 text-center text-slate-500 font-bold">{{ $index + 1 }}</td>
                                        
                                        <!-- รายการ -->
                                        <td class="py-4 px-4 font-bold text-slate-700">
                                            <a href="{{ route('procurements.show', $req->id) }}" class="hover:text-indigo-600 transition-colors">
                                                {{ $req->title }}
                                            </a>
                                            <p class="text-[10px] text-slate-400 font-normal mt-0.5">{{ $req->request_no }}</p>
                                        </td>
                                        
                                        <!-- ขอใบเสนอราคา -->
                                        <td class="py-4 px-2 text-center">
                                            <input type="checkbox" disabled {{ $step1 ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        </td>

                                        <!-- จัดทำ -->
                                        <td class="py-4 px-2 text-center">
                                            <input type="checkbox" disabled {{ $step2 ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        </td>

                                        <!-- Manager/ICT -->
                                        <td class="py-4 px-2 text-center">
                                            <input type="checkbox" disabled {{ $step3 ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        </td>

                                        <!-- CAO -->
                                        <td class="py-4 px-2 text-center">
                                            <input type="checkbox" disabled {{ $step4 ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        </td>

                                        <!-- จัดทำ PR/PO -->
                                        <td class="py-4 px-2 text-center">
                                            <input type="checkbox" disabled {{ $step5 ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        </td>

                                        <!-- จัดซื้ออนุมัติ -->
                                        <td class="py-4 px-2 text-center">
                                            <input type="checkbox" disabled {{ $step6 ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        </td>

                                        <!-- ส่งเอกสารให้ SUP -->
                                        <td class="py-4 px-2 text-center">
                                            <input type="checkbox" disabled {{ $step7 ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        </td>

                                        <!-- ส่งใบกำกับภาษี -->
                                        <td class="py-4 px-2 text-center">
                                            <input type="checkbox" disabled {{ $step8 ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                        </td>

                                        <!-- ผลการดำเนินงาน (Progress Bar) -->
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex items-center gap-2 justify-center">
                                                <span class="font-extrabold text-[10px] text-slate-500 w-8">{{ number_format($progress, 1) }}%</span>
                                                <div class="w-16 h-2 bg-slate-100 rounded-full overflow-hidden border border-slate-200/50">
                                                    <div class="h-full rounded-full @if($progress == 100) bg-emerald-500 @elseif($req->status === 'rejected') bg-rose-500 @else bg-indigo-500 @endif" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- งบประมาณ (ตั้ง) -->
                                        <td class="py-4 px-4 text-right font-bold text-slate-700">
                                            ฿{{ number_format($req->estimated_budget, 2) }}
                                        </td>

                                        <!-- ใช้จริง -->
                                        <td class="py-4 px-4 text-right font-bold text-emerald-600">
                                            ฿{{ number_format($req->approved_budget ?? 0, 2) }}
                                        </td>

                                        <!-- วันสิ้นสุดสัญญา/หมดอายุ -->
                                        <td class="py-4 px-4 text-slate-500 font-semibold">
                                            {{ $req->expected_date ? $req->expected_date->format('Y-m-d') : '-' }}
                                        </td>

                                        <!-- หมายเหตุ -->
                                        <td class="py-4 px-4 text-slate-400 truncate max-w-[180px]" title="{{ $req->description }}">
                                            {{ $req->description ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Recent Requests -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">รายการคำขอล่าสุด (Recent Activities)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">รายการจัดซื้อจัดจ้างที่คุณสร้างหรือมีสิทธิ์เข้าถึงพิจารณาล่าสุด</p>
                    </div>
                    <a href="{{ route('procurements.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">ดูทั้งหมด →</a>
                </div>

                @if($recentRequests->isEmpty())
                    <div class="py-12 text-center">
                        <p class="text-sm font-semibold text-slate-400">ไม่มีข้อมูลการทำรายการคำขอจัดซื้อ</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 text-xs font-bold uppercase text-slate-400 tracking-wider">รหัสคำขอ / ชื่อเรื่อง</th>
                                    <th class="py-3 text-xs font-bold uppercase text-slate-400 tracking-wider">ความเร่งด่วน</th>
                                    <th class="py-3 text-xs font-bold uppercase text-slate-400 tracking-wider">งบประมาณ</th>
                                    <th class="py-3 text-xs font-bold uppercase text-slate-400 tracking-wider">สถานะ</th>
                                    <th class="py-3 text-xs font-bold uppercase text-slate-400 tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentRequests as $request)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-4">
                                            <div class="space-y-0.5">
                                                <p class="text-xs font-bold text-slate-400">{{ $request->request_no }}</p>
                                                <p class="text-sm font-bold text-slate-700">{{ $request->title }}</p>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md uppercase border 
                                                @if($request->priority === 'urgent') bg-red-50 text-red-700 border-red-200
                                                @elseif($request->priority === 'high') bg-orange-50 text-orange-700 border-orange-200
                                                @elseif($request->priority === 'medium') bg-indigo-50 text-indigo-700 border-indigo-200
                                                @else bg-slate-50 text-slate-600 border-slate-200 @endif">
                                                {{ $request->priority }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-sm font-bold text-slate-600">
                                            ฿{{ number_format($request->estimated_budget, 2) }}
                                        </td>
                                        <td class="py-4">
                                            <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full border
                                                @if($request->status === 'completed') bg-emerald-50 text-emerald-700 border-emerald-200
                                                @elseif($request->status === 'rejected') bg-rose-50 text-rose-700 border-rose-200
                                                @elseif($request->status === 'draft') bg-slate-50 text-slate-600 border-slate-200
                                                @else bg-amber-50 text-amber-700 border-amber-200 @endif">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-right">
                                            <a href="{{ route('procurements.show', $request->id) }}" class="text-slate-400 hover:text-slate-700 p-1.5 transition-colors">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
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
        <div class="space-y-8">
            
            <!-- Quick actions panel -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 rounded-3xl p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4">ดำเนินการด่วน (Quick Actions)</h3>
                <p class="text-xs text-slate-500 mb-6">คุณสามารถเปิดเสนอใบจัดซื้อจัดจ้างชิ้นใหม่ สัญญาซอฟต์แวร์ หรือตรวจสอบข้อมูลผู้จัดจำหน่ายได้รวดเร็วที่นี่</p>
                
                <div class="space-y-3">
                    <a href="{{ route('procurements.create') }}" class="flex items-center justify-between p-4 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-md transition-all duration-200 transform hover:scale-[1.01]">
                        <span>เปิดคำขอจัดซื้อจัดจ้างใหม่</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </a>
                    
                    <a href="{{ route('vendors.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-200 hover:border-indigo-500/20 text-slate-600 hover:text-slate-800 font-semibold text-sm transition-all duration-200">
                        <span>เพิ่มรายชื่อผู้จัดจำหน่าย (Vendor)</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <a href="{{ route('licenses.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-200 hover:border-indigo-500/20 text-slate-600 hover:text-slate-800 font-semibold text-sm transition-all duration-200">
                        <span>จดบันทึกสัญญาลิขสิทธิ์ซอฟต์แวร์</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Department Budget Healthcard -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-6">ความพร้อมงบประมาณแผนกคุณ</h3>
                
                @if($budget)
                    @php
                        $percentage = $budget->allocated_budget > 0 ? ($budget->used_budget / $budget->allocated_budget) * 100 : 0;
                    @endphp
                    <div class="space-y-6">
                        <div class="flex items-end justify-between">
                            <div>
                                <p class="text-3xl font-extrabold text-slate-800">฿{{ number_format($budget->used_budget, 2) }}</p>
                                <p class="text-xs text-slate-500 mt-1">จากทั้งหมด ฿{{ number_format($budget->allocated_budget, 2) }}</p>
                            </div>
                            <span class="text-sm font-bold text-indigo-600">{{ number_format($percentage, 1) }}% ถูกใช้ไป</span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden p-[2px] border border-slate-200/50">
                            <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-6">
                            <div>
                                <p class="text-xs text-slate-500">งบประมาณจัดสรร</p>
                                <p class="text-sm font-bold text-slate-600 mt-1">฿{{ number_format($budget->allocated_budget, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">งบประมาณคงเหลือ</p>
                                <p class="text-sm font-bold text-fuchsia-600 mt-1">฿{{ number_format($budget->remaining_budget, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="py-6 text-center">
                        <p class="text-xs text-slate-400">ยังไม่มีการอนุมัติหรือจัดสรรงบประมาณให้แผนกของคุณสำหรับปีงบประมาณนี้</p>
                    </div>
                @endif
            </div>

        </div>

    </div>

@endsection
