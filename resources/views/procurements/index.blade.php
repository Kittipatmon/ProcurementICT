@extends('layouts.app')

@section('title', 'รายการคำขอจัดซื้อทั้งหมด')
@section('page_title', 'ติดตามสถานะจัดซื้อและดำเนินงาน ICT')

@section('content')

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-blue-900 border-l-4 border-blue-600 pl-3">รายการเอกสารจัดซื้อ</h2>
            <p class="text-sm text-slate-500 mt-1 pl-4">ระบบจัดการข้อมูลคำขอจัดซื้อและดำเนินงาน ICT ทั้งหมด</p>
        </div>
    </div>

    <!-- Workflow Status Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <!-- Card 1: Manager — breakdown by department -->
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-[0_2px_10px_rgba(0,0,0,0.04)] flex items-start justify-between relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-blue-50 rounded-full z-0 opacity-50"></div>
            <div class="z-10 relative w-full">
                <p class="text-[11px] font-bold text-slate-500 mb-0.5">ผู้จัดการแผนก</p>
                <p class="text-[9px] text-slate-400 mb-1">Manager</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $statusTracker['manager'] }}</h3>
                <!-- Breakdown by department -->
                @if($managerByDept->isNotEmpty())
                    <div class="mt-2 space-y-1 max-h-20 overflow-y-auto pr-1">
                        @foreach($managerByDept as $dept)
                            <a href="{{ route('procurements.index', ['status' => 'submitted']) }}"
                               class="flex items-center justify-between w-full group">
                                <span class="text-[9px] text-blue-700 font-semibold group-hover:underline truncate max-w-[90px]" title="{{ $dept['name'] }}">
                                    {{ $dept['name'] }}
                                </span>
                                <span class="text-[10px] font-black text-blue-800 bg-blue-100 rounded px-1.5 py-0.5 min-w-[18px] text-center flex-shrink-0">
                                    {{ $dept['count'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-[10px] text-blue-600 font-bold mt-2">รออนุมัติขั้นต้น</p>
                @endif
            </div>
            <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center z-10 mt-1 shadow-sm flex-shrink-0 ml-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
        </div>

        <!-- Card 2: ICT -->
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-[0_2px_10px_rgba(0,0,0,0.04)] flex items-start justify-between relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-indigo-50 rounded-full z-0 opacity-50"></div>
            <div class="z-10 relative">
                <p class="text-[11px] font-bold text-slate-500 mb-1">ผู้จัดการฝ่าย ICT</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $statusTracker['ict'] }}</h3>
                <p class="text-[10px] text-indigo-600 font-bold mt-2">รอตรวจสอบสเปก</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center z-10 mt-1 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <!-- Card 3: CAO -->
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-[0_2px_10px_rgba(0,0,0,0.04)] flex items-start justify-between relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-amber-50 rounded-full z-0 opacity-50"></div>
            <div class="z-10 relative">
                <p class="text-[11px] font-bold text-slate-500 mb-1">ผู้อำนวยการบริหาร</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $statusTracker['cao'] }}</h3>
                <p class="text-[10px] text-amber-600 font-bold mt-2">รออนุมัติงบประมาณ</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center z-10 mt-1 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Procurement -->
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-[0_2px_10px_rgba(0,0,0,0.04)] flex items-start justify-between relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-teal-50 rounded-full z-0 opacity-50"></div>
            <div class="z-10 relative">
                <p class="text-[11px] font-bold text-slate-500 mb-1">เจ้าหน้าที่จัดซื้อ</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $statusTracker['procurement'] }}</h3>
                <p class="text-[10px] text-teal-600 font-bold mt-2">รอออก PR/PO</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center z-10 mt-1 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 5: Finance -->
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-[0_2px_10px_rgba(0,0,0,0.04)] flex items-start justify-between relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-emerald-50 rounded-full z-0 opacity-50"></div>
            <div class="z-10 relative">
                <p class="text-[11px] font-bold text-slate-500 mb-1">ฝ่ายบัญชีและการเงิน</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $statusTracker['finance'] }}</h3>
                <p class="text-[10px] text-emerald-600 font-bold mt-2">รอรับมอบเอกสาร</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center z-10 mt-1 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-blue-50 border border-blue-200 p-5 rounded-lg mb-6 shadow-sm">
        <form action="{{ route('procurements.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
            
            <!-- Search Query -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-1">ค้นหาคำขอ</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" oninput="clearTimeout(this.timer); this.timer = setTimeout(() => this.form.submit(), 800)" placeholder="เลขที่คำขอ, ชื่อเรื่อง, รายละเอียด..." class="w-full bg-white border border-blue-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-1">สถานะ</label>
                <select name="status" id="status" onchange="this.form.submit()" class="w-full bg-white border border-blue-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                    <option value="">ทั้งหมด</option>
                    <optgroup label="ค้นหาตามผู้รับผิดชอบสิทธิ์">
                        <option value="role_user" {{ request('status') === 'role_user' ? 'selected' : '' }}>
                            ผู้ขอจัดซื้อ (ขั้นตอน 1: ขอใบเสนอราคา, 6: จัดทำ PR/PO, 9: ส่งเอกสาร SUP)
                        </option>
                        <option value="role_manager" {{ request('status') === 'role_manager' ? 'selected' : '' }}>
                            ผู้จัดการแผนก (ขั้นตอน 3: อนุมัติขั้นต้นแผนก)
                        </option>
                        <option value="role_ict" {{ request('status') === 'role_ict' ? 'selected' : '' }}>
                            Manager ICT (ขั้นตอน 4: ตรวจสอบสเปก ICT, 7: ตรวจสอบ PR/PO)
                        </option>
                        <option value="role_cao" {{ request('status') === 'role_cao' ? 'selected' : '' }}>
                            CAO (ขั้นตอน 5: อนุมัติงบ CAO, 8: อนุมัติเปิด PR/PO)
                        </option>
                        <option value="role_accounting" {{ request('status') === 'role_accounting' ? 'selected' : '' }}>
                            ฝ่ายบัญชี (ขั้นตอน 10: ส่งใบกำกับภาษี/เบิกจ่าย)
                        </option>
                    </optgroup>
                    <optgroup label="ผลการดำเนินงานสำเร็จ/ยกเลิก">
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>เสร็จสิ้นสมบูรณ์</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>ถูกปฏิเสธ</option>
                    </optgroup>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-1">หมวดหมู่</label>
                <select name="category" id="category" onchange="this.form.submit()" class="w-full bg-white border border-blue-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                    <option value="">ทั้งหมด</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action buttons -->
            <div class="flex items-end gap-2">
                <a href="{{ route('procurements.index') }}" class="w-full py-2 bg-white hover:bg-slate-100 text-slate-600 font-bold rounded text-xs transition-colors border border-slate-300 text-center shadow-sm">
                    ล้างการค้นหา
                </a>
            </div>

        </form>
    </div>

    <!-- Requests Table Panel -->
    <div class="bg-white border border-slate-300 shadow-md rounded-lg overflow-hidden">
        <div class="bg-blue-800 px-4 py-3 flex items-center justify-between text-white border-b-4 border-blue-900">
            <h3 class="text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                รายการคำขอจัดซื้อจัดจ้าง
            </h3>
            @if(in_array(Auth::user()->procurement_role, ['user', 'admin']))
                <a href="{{ route('procurements.create') }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded border border-blue-500 text-xs shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    สร้างคำขอใหม่
                </a>
            @endif
        </div>

        @if($requests->isEmpty())
            <div class="p-8 text-center bg-slate-50">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-slate-500 text-sm font-semibold">ไม่พบข้อมูลใบขอจัดซื้อตามเงื่อนไขที่ระบุ</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-blue-50 text-blue-900 border-b-2 border-blue-200">
                            <th class="border-r border-blue-200 py-2.5 px-3 font-bold uppercase tracking-wide">เลขที่เอกสาร / หัวข้อ</th>
                            <th class="border-r border-blue-200 py-2.5 px-3 font-bold uppercase tracking-wide">ผู้ยื่นคำขอ</th>
                            <th class="border-r border-blue-200 py-2.5 px-3 font-bold uppercase tracking-wide">หมวดหมู่</th>
                            <th class="border-r border-blue-200 py-2.5 px-3 text-right font-bold uppercase tracking-wide">งบประมาณ</th>
                            <th class="border-r border-blue-200 py-2.5 px-2 text-center font-bold uppercase tracking-wide">สถานะ Workflow</th>
                            <th class="border-r border-blue-200 py-2.5 px-2 text-center font-bold uppercase tracking-wide">วันที่บันทึก</th>
                            <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wide">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($requests as $request)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- เลขที่เอกสาร / หัวข้อ -->
                                <td class="border border-slate-200 py-2.5 px-3 font-semibold text-slate-900">
                                    <span class="block text-[10px] font-mono text-slate-500">{{ $request->request_no }}</span>
                                    <a href="{{ route('procurements.show', $request->id) }}" class="text-xs text-blue-800 hover:text-blue-600 block mt-0.5" title="{{ $request->title }}">{{ $request->title }}</a>
                                </td>
                                
                                <!-- ผู้ยื่นคำขอ -->
                                <td class="border border-slate-200 py-2.5 px-3 text-slate-600">
                                    <div class="flex flex-col whitespace-nowrap">
                                        <span class="font-bold">{{ $request->requester->name }}</span>
                                        <span class="text-[9px] text-slate-400 font-semibold mt-0.5">{{ $request->department->name }}</span>
                                    </div>
                                </td>
                                
                                <!-- หมวดหมู่ -->
                                <td class="border border-slate-200 py-2.5 px-3 text-slate-500 capitalize whitespace-nowrap">
                                    {{ $request->category }}
                                </td>
                                
                                <!-- งบประมาณ -->
                                <td class="border border-slate-200 py-2.5 px-3 text-right font-mono font-bold text-slate-700 whitespace-nowrap">
                                    ฿{{ number_format($request->estimated_budget, 2) }}
                                </td>
                                
                                <!-- สถานะ Workflow -->
                                <td class="border border-slate-200 py-2.5 px-2 text-center whitespace-nowrap">
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded border
                                        @if($request->status === 'completed') bg-emerald-50 text-emerald-800 border-emerald-200
                                        @elseif($request->status === 'rejected') bg-rose-50 text-rose-800 border-rose-200
                                        @elseif($request->status === 'draft') bg-slate-50 text-slate-600 border-slate-200
                                        @elseif($request->status === 'submitted') bg-amber-50 text-amber-800 border-amber-200
                                        @elseif(in_array($request->status, ['approved_manager', 'pr_created'])) bg-indigo-50 text-indigo-800 border-indigo-200
                                        @elseif(in_array($request->status, ['approved_ict', 'pr_approved_ict'])) bg-purple-50 text-purple-800 border-purple-200
                                        @elseif(in_array($request->status, ['approved_cao', 'pr_approved_cao', 'po_created'])) bg-blue-50 text-blue-800 border-blue-200
                                        @elseif($request->status === 'delivered') bg-teal-50 text-teal-800 border-teal-200
                                        @else bg-slate-50 text-slate-600 border-slate-200 @endif">
                                        {{ $request->status_text }}
                                    </span>
                                </td>
                                
                                <!-- วันที่บันทึก -->
                                <td class="border border-slate-200 py-2.5 px-2 text-center text-slate-600 whitespace-nowrap font-mono text-[10px]">
                                    {{ $request->created_at->format('d/m/') . ($request->created_at->format('Y') + 543) }}
                                </td>
                                
                                <!-- การจัดการ -->
                                <td class="border border-slate-200 py-2.5 px-3 text-center whitespace-nowrap">
                                    <a href="{{ route('procurements.show', $request->id) }}" class="inline-block px-2.5 py-1 bg-white border border-slate-200 text-slate-600 hover:text-blue-700 hover:border-blue-300 text-[10px] font-bold rounded transition-colors">
                                        เปิดพิจารณา
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $requests->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

@endsection
