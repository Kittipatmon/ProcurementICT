@extends('layouts.app')

@section('title', 'รายละเอียดคำขอจัดซื้อ #' . $procRequest->request_no)
@section('page_title', 'รายละเอียดคำขอจัดซื้อและติดตามการดำเนินงาน')

@section('content')

    <!-- Workflow Progress Bar (Visually Premium Timeline) -->
    <div class="bg-white border border-slate-200 p-8 rounded-3xl mb-8 shadow-sm">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6">ความคืบหน้าของเอกสาร (Procurement Workflow Timeline)</h3>
        
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 relative">
            <!-- Connector Line -->
            <div class="absolute hidden md:block top-1/2 left-[5%] right-[5%] h-0.5 bg-slate-100 -translate-y-1/2 z-0"></div>
            
            @php
                $steps = [
                    'draft' => '1. ร่างคำขอ',
                    'submitted' => '2. รออนุมัติแผนก',
                    'approved_manager' => '3. รอตรวจ ICT',
                    'approved_ict' => '4. รออนุมัติงบ CAO',
                    'approved_cao' => '5. รอออก PR/PO',
                    'po_created' => '6. รอจัดส่งสินค้า',
                    'delivered' => '7. รอยืนยันรับมอบ',
                    'completed' => '8. เสร็จสิ้น'
                ];
                
                // Determine current step index
                $statusList = array_keys($steps);
                $currentIndex = array_search($procRequest->status, $statusList);
                if ($procRequest->status === 'pr_created') {
                    $currentIndex = 4; // Map pr_created to step 5
                }
                if ($procRequest->status === 'rejected') {
                    $currentIndex = -1;
                }
            @endphp

            @if($procRequest->status === 'rejected')
                <!-- Rejected State Alert -->
                <div class="w-full text-center py-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl font-bold text-sm relative z-10 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    เอกสารนี้ถูกยกเลิก/ปฏิเสธการอนุมัติ (REJECTED)
                </div>
            @else
                @foreach($steps as $key => $label)
                    @php
                        $stepIndex = array_search($key, $statusList);
                        $isCompleted = $currentIndex >= $stepIndex;
                        $isActive = $procRequest->status === $key || ($key === 'approved_cao' && $procRequest->status === 'pr_created');
                    @endphp
                    <div class="flex flex-col items-center relative z-10 flex-1">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all border
                            @if($isCompleted) bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-600/20
                            @else bg-slate-50 border-slate-200 text-slate-400 @endif
                            @if($isActive) ring-4 ring-indigo-500/20 scale-110 @endif">
                            @if($isCompleted && !$isActive)
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                {{ $stepIndex + 1 }}
                            @endif
                        </div>
                        <span class="text-[10px] font-extrabold mt-3 tracking-wide text-center uppercase
                            @if($isActive) text-indigo-600 font-black
                            @elseif($isCompleted) text-slate-700
                            @else text-slate-400 @endif">
                            {{ $label }}
                        </span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Main Content Detail Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Details, Items, Comments -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- General Info Panel -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 space-y-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">ใบส่งความต้องการจัดซื้อจัดจ้าง</span>
                        <h3 class="text-xl font-bold text-slate-800 mt-1">{{ $procRequest->title }}</h3>
                    </div>
                    <span class="inline-block px-3 py-1.5 text-xs font-bold rounded-full uppercase
                        @if($procRequest->status === 'completed') bg-emerald-50 text-emerald-600 border border-emerald-100
                        @elseif($procRequest->status === 'rejected') bg-rose-55 text-rose-600 border border-rose-100
                        @else bg-amber-50 text-amber-600 border border-amber-100 @endif">
                        {{ $procRequest->status }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">ผู้เปิดคำขอ</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">{{ $procRequest->requester->name }}</p>
                        <p class="text-xs text-slate-500">{{ $procRequest->department->name }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">ความเร่งด่วน / หมวดหมู่</p>
                        <p class="text-sm font-bold text-slate-700 mt-1 capitalize">{{ $procRequest->priority }}</p>
                        <p class="text-xs text-slate-500 capitalize">{{ $procRequest->category }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">วันที่ต้องการใช้อุปกรณ์</p>
                        <p class="text-sm font-bold text-indigo-600 mt-1">
                            {{ $procRequest->expected_date ? $procRequest->expected_date->format('Y-m-d') : 'ไม่ระบุ' }}
                        </p>
                    </div>
                </div>

                @if($procRequest->description)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">คำอธิบายความจำเป็น</p>
                        <p class="text-sm text-slate-650 whitespace-pre-line leading-relaxed">{{ $procRequest->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Items Table Panel -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6 uppercase tracking-wider">รายการอุปกรณ์/การจัดซื้อ (Items)</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">ชื่ออุปกรณ์/รายละเอียด</th>
                                <th class="py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">จำนวน</th>
                                <th class="py-3 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">ราคาหน่วย (บาท)</th>
                                <th class="py-3 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">รวม (บาท)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($procRequest->items as $item)
                                <tr>
                                    <td class="py-4">
                                        <div class="space-y-0.5">
                                            <p class="text-sm font-bold text-slate-700">{{ $item->item_name }}</p>
                                            @if($item->specification)
                                                <p class="text-xs text-slate-400">{{ $item->specification }}</p>
                                            @endif
                                            @if($item->vendor)
                                                <p class="text-[10px] text-indigo-650 font-semibold">ผู้เสนอขาย: {{ $item->vendor->vendor_name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 text-sm text-slate-600 font-bold">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="py-4 text-sm text-slate-600 font-semibold text-right">
                                        ฿{{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="py-4 text-sm font-extrabold text-indigo-600 text-right">
                                        ฿{{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-xs font-bold text-slate-400">งบประมาณรวมทั้งสิ้น (Grand Total)</span>
                    <span class="text-lg font-extrabold text-indigo-600">฿{{ number_format($procRequest->estimated_budget, 2) }}</span>
                </div>
            </div>

            <!-- Comments & Discussion Stream -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 space-y-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 uppercase tracking-wider">การสนทนาและบันทึกข้อความ (Comments)</h3>
                
                @if($procRequest->comments->isEmpty())
                    <p class="text-xs text-slate-400 py-4 text-center">ไม่มีความคิดเห็นหรือบันทึกข้อความสำหรับคำขอนี้</p>
                @else
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2">
                        @foreach($procRequest->comments as $comment)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-indigo-600">{{ $comment->user->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $comment->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('procurements.comments', $procRequest->id) }}" method="POST" class="space-y-3 pt-2">
                    @csrf
                    <textarea name="comment" required rows="3" placeholder="เขียนข้อความสนทนา สอบถามข้อมูล หรือระบุเหตุผลประกอบพิจารณา..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-750 focus:outline-none focus:border-indigo-500"></textarea>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-all shadow-md">
                        โพสต์ข้อความ
                    </button>
                </form>
            </div>

        </div>

        <!-- Right 1 Col: Status Actions Sidebar & PR/PO details -->
        <div class="space-y-8">
            
            <!-- Contextual Workflow Actions Panel -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 space-y-6 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-3">การตรวจสอบเอกสารและอนุมัติ</h3>
                
                @php
                    $user = Auth::user();
                @endphp

                <!-- 1. Draft submission -->
                @if($procRequest->status === 'draft' && $procRequest->requester_id === $user->id)
                    <div class="space-y-3">
                        <p class="text-xs text-slate-500">คำขอจัดซื้อนี้ยังคงเก็บอยู่ในขั้นตอนแบบร่าง ดำเนินการยื่นขอพิจารณาต่อแผนกเลย</p>
                        <form action="{{ route('procurements.submit', $procRequest->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-xs shadow-lg transition-all transform hover:scale-[1.01]">
                                ส่งยื่นขออนุมัติจัดซื้อ
                            </button>
                        </form>
                    </div>
                @endif

                <!-- 2. Manager Approval action -->
                @if($procRequest->status === 'submitted' && ($user->procurement_role === 'manager' || $user->procurement_role === 'admin') && ($user->procurement_role === 'admin' || $procRequest->department_id === $user->dept_id))
                    <div class="space-y-4">
                        <p class="text-xs text-slate-500">ในฐานะหัวหน้าแผนก กรุณาตรวจสอบวัตถุประสงค์การใช้งานและอนุมัติผ่านเพื่อส่งต่อไปยังฝ่ายไอที</p>
                        
                        <form action="{{ route('procurements.approve', $procRequest->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="text" name="comment" placeholder="ความคิดเห็นประกอบการอนุมัติ (ไม่บังคับ)" class="w-full bg-slate-50 border border-slate-250 rounded-xl px-3 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500">
                            
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                                    อนุมัติใบคำขอ
                                </button>
                        </form>
                        
                        <form action="{{ route('procurements.reject', $procRequest->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="comment" id="reject-comment-mgr" value="ไม่อนุมัติเนื่องจาก...">
                            <button type="submit" onclick="let c=prompt('โปรดระบุสาเหตุที่ปฏิเสธใบคำขอนี้:'); if(!c) return false; document.getElementById('reject-comment-mgr').value=c;" class="w-full py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                                ปฏิเสธ (Reject)
                            </button>
                        </form>
                            </div>
                    </div>
                @endif

                <!-- 3. ICT Technical approval action -->
                @if($procRequest->status === 'approved_manager' && ($user->procurement_role === 'ict' || $user->procurement_role === 'admin'))
                    <div class="space-y-4">
                        <p class="text-xs text-slate-500">ในฐานะเจ้าหน้าที่ IT ตรวจสอบสเปกทางเทคนิคของอุปกรณ์ เครือข่าย และความเหมาะสมเพื่ออนุมัติ</p>
                        
                        <form action="{{ route('procurements.approve', $procRequest->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="text" name="comment" placeholder="ความคิดเห็นประกอบการตรวจเทคนิค (ไม่บังคับ)" class="w-full bg-slate-50 border border-slate-250 rounded-xl px-3 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500">
                            
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                                    ตรวจผ่านเทคนิค
                                </button>
                        </form>
                        
                        <form action="{{ route('procurements.reject', $procRequest->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="comment" id="reject-comment-ict" value="ไม่อนุมัติเนื่องจาก...">
                            <button type="submit" onclick="let c=prompt('โปรดระบุสาเหตุการไม่ผ่านเทคนิค:'); if(!c) return false; document.getElementById('reject-comment-ict').value=c;" class="w-full py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                                ปฏิเสธการตรวจ
                            </button>
                        </form>
                            </div>
                    </div>
                @endif

                <!-- 4. CAO Budget Approval action -->
                @if($procRequest->status === 'approved_ict' && ($user->procurement_role === 'cao' || $user->procurement_role === 'admin'))
                    <div class="space-y-4">
                        <p class="text-xs text-slate-500">ในฐานะผู้อนุมัติงบประมาณ (CAO) ตรวจสอบความพร้อมของยอดเงินงบประมาณแผนกและอนุมัติจัดซื้อ</p>
                        
                        <form action="{{ route('procurements.approve', $procRequest->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="text" name="comment" placeholder="บันทึกงบประมาณการอนุมัติ (ไม่บังคับ)" class="w-full bg-slate-50 border border-slate-250 rounded-xl px-3 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500">
                            
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                                    อนุมัติงบประมาณ
                                </button>
                        </form>
                        
                        <form action="{{ route('procurements.reject', $procRequest->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="comment" id="reject-comment-cao" value="ไม่อนุมัติเนื่องจาก...">
                            <button type="submit" onclick="let c=prompt('โปรดระบุสาเหตุที่ปฏิเสธงบประมาณนี้:'); if(!c) return false; document.getElementById('reject-comment-cao').value=c;" class="w-full py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                                ปฏิเสธงบจัดซื้อ
                            </button>
                        </form>
                            </div>
                    </div>
                @endif

                <!-- 5. Procurement assigns PR -->
                @if($procRequest->status === 'approved_cao' && ($user->procurement_role === 'procurement' || $user->procurement_role === 'admin'))
                    <div class="space-y-4">
                        <p class="text-xs text-slate-500">งบประมาณผ่านแล้ว กรุณาเปิดเลข PR จากระบบ ERP และระบุบันทึกเลขที่เอกสารเพื่อติดตาม</p>
                        
                        <form action="{{ route('procurements.pr', $procRequest->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label for="pr_no" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">เลขที่ใบขอซื้อ (PR No.)</label>
                                <input type="text" name="pr_no" id="pr_no" required placeholder="เช่น PR-2026-0005" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                                บันทึกการออกเลข PR
                            </button>
                        </form>
                    </div>
                @endif

                <!-- 6. Procurement assigns PO -->
                @if($procRequest->status === 'pr_created' && ($user->procurement_role === 'procurement' || $user->procurement_role === 'admin'))
                    <div class="space-y-4">
                        <p class="text-xs text-slate-500">เลข PR พร้อมแล้ว ดำเนินการระบุใบสั่งซื้อ PO และกำหนดเลือกผู้ขายรับเหมา</p>
                        
                        <form action="{{ route('procurements.po', $procRequest->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label for="po_no" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">เลขที่ใบสั่งซื้อ (PO No.)</label>
                                <input type="text" name="po_no" id="po_no" required placeholder="เช่น PO-2026-0002" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="vendor_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ผู้ขายที่ส่งมอบใบสั่งสั่งซื้อ</label>
                                <select name="vendor_id" id="vendor_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-400 focus:outline-none focus:border-indigo-500">
                                    <option value="">เลือกผู้ขาย...</option>
                                    @foreach($vendors as $v)
                                        <option value="{{ $v->id }}">{{ $v->vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="delivery_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">วันนัดรับสินค้าประมาณการ</label>
                                <input type="date" name="delivery_date" id="delivery_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-400 focus:outline-none focus:border-indigo-500">
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                                บันทึกการออกเลข PO
                            </button>
                        </form>
                    </div>
                @endif

                <!-- 7. Procurement updates shipment delivered -->
                @if($procRequest->status === 'po_created' && ($user->procurement_role === 'procurement' || $user->procurement_role === 'admin'))
                    <div class="space-y-3">
                        <p class="text-xs text-slate-500">ผู้ขายทำการจัดส่งมอบของเรียบร้อยแล้วหรือไม่? ยืนยันการส่งของเพื่อเข้าขั้นตอนรับมอบ</p>
                        <form action="{{ route('procurements.deliver', $procRequest->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl text-xs shadow-md transition-all">
                                อัปเดตสินค้ามาส่งแล้ว (Delivered)
                            </button>
                        </form>
                    </div>
                @endif

                <!-- 8. Completion by requester or admin -->
                @if($procRequest->status === 'delivered' && ($procRequest->requester_id === $user->id || $user->procurement_role === 'admin'))
                    <div class="space-y-3">
                        <p class="text-xs text-slate-500">พนักงานตรวจสอบความถูกต้องและสภาพอุปกรณ์เรียบร้อยแล้ว ยืนยันเสร็จสิ้นจัดซื้อ</p>
                        <form action="{{ route('procurements.complete', $procRequest->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-xs shadow-md transition-all">
                                ยืนยันอุปกรณ์ถูกต้อง (เสร็จงาน)
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Normal message if no actions -->
                @if(in_array($procRequest->status, ['completed', 'rejected']) || 
                    ($procRequest->status === 'submitted' && $user->procurement_role !== 'manager' && $user->procurement_role !== 'admin') ||
                    ($procRequest->status === 'approved_manager' && $user->procurement_role !== 'ict' && $user->procurement_role !== 'admin') ||
                    ($procRequest->status === 'approved_ict' && $user->procurement_role !== 'cao' && $user->procurement_role !== 'admin') ||
                    (in_array($procRequest->status, ['approved_cao', 'pr_created', 'po_created']) && $user->procurement_role !== 'procurement' && $user->procurement_role !== 'admin' && $procRequest->status !== 'delivered'))
                    <p class="text-xs text-slate-400 text-center py-2">ไม่มีการดำเนินการที่จำเป็นสำหรับสิทธิ์ของคุณในขั้นตอนนี้</p>
                @endif
            </div>

            <!-- Attached Files Box -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 space-y-4 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">เอกสารแนบในคำขอ</h3>
                
                @if($procRequest->files->isEmpty())
                    <p class="text-xs text-slate-450 text-center py-2">ไม่มีเอกสารแนบ</p>
                @else
                    <div class="space-y-2">
                        @foreach($procRequest->files as $file)
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 transition-colors">
                                <span class="text-xs text-slate-700 font-bold truncate max-w-[180px]">{{ $file->file_name }}</span>
                                <span class="text-[10px] text-indigo-600 font-extrabold uppercase shrink-0">ดาวน์โหลด</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- PR / PO Tracking Status Summary -->
            @if(!$procRequest->purchaseRequisitions->isEmpty() || !$procRequest->purchaseOrders->isEmpty())
                <div class="bg-white border border-slate-200 rounded-3xl p-8 space-y-4 shadow-sm">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">หมายเลขเอกสารอ้างอิงจัดซื้อ</h3>
                    
                    @foreach($procRequest->purchaseRequisitions as $pr)
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400">เลขที่ใบขอซื้อ PR:</span>
                            <span class="font-bold text-slate-700">{{ $pr->pr_no }} ({{ $pr->created_at->format('Y-m-d') }})</span>
                        </div>
                    @endforeach

                    @foreach($procRequest->purchaseOrders as $po)
                        <div class="flex justify-between items-center text-xs pt-1 border-t border-slate-100">
                            <span class="text-slate-400">เลขที่ใบสั่งซื้อ PO:</span>
                            <span class="font-bold text-slate-700">{{ $po->po_no }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400">ผู้ขาย (Vendor):</span>
                            <span class="font-semibold text-indigo-600">{{ $po->vendor->vendor_name }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400">สถานะจัดส่ง:</span>
                            <span class="font-bold text-slate-650 capitalize">{{ $po->status }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- History Logs (Audit Trail) -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-4">ประวัติการดำเนินงาน (Audit Trail)</h3>
                
                <div class="space-y-4 max-h-[250px] overflow-y-auto pr-2">
                    @foreach($procRequest->logs as $log)
                        <div class="text-xs flex items-start gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                            <div>
                                <p class="text-slate-700 font-bold">{{ $log->action }}</p>
                                <p class="text-[10px] text-slate-400">{{ $log->user->name }} • {{ $log->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

@endsection
