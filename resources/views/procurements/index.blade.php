@extends('layouts.app')

@section('title', 'รายการคำขอจัดซื้อทั้งหมด')
@section('page_title', 'ติดตามสถานะจัดซื้อและดำเนินงาน ICT')

@section('content')

    <!-- Filter & Search Bar -->
    <div class="bg-white border border-slate-200 p-6 rounded-3xl mb-8 shadow-sm">
        <form action="{{ route('procurements.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
            
            <!-- Search Query -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ค้นหาคำขอ</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="เลขที่คำขอ, ชื่อเรื่อง, รายละเอียด..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-indigo-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">สถานะ</label>
                <select name="status" id="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-500 focus:outline-none focus:border-indigo-500">
                    <option value="">ทั้งหมด</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft (แบบร่าง)</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted (ส่งคำขอแล้ว)</option>
                    <option value="approved_manager" {{ request('status') === 'approved_manager' ? 'selected' : '' }}>Approved (หัวหน้าแผนก)</option>
                    <option value="approved_ict" {{ request('status') === 'approved_ict' ? 'selected' : '' }}>Approved (ICT Technical)</option>
                    <option value="approved_cao" {{ request('status') === 'approved_cao' ? 'selected' : '' }}>Approved (งบประมาณ CAO)</option>
                    <option value="pr_created" {{ request('status') === 'pr_created' ? 'selected' : '' }}>PR Created (ออก PR แล้ว)</option>
                    <option value="po_created" {{ request('status') === 'po_created' ? 'selected' : '' }}>PO Created (ออก PO แล้ว)</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered (ส่งมอบแล้ว)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (เสร็จสิ้น)</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected (ปฏิเสธคำขอ)</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">หมวดหมู่</label>
                <select name="category" id="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-500 focus:outline-none focus:border-indigo-500">
                    <option value="">ทั้งหมด</option>
                    <option value="hardware" {{ request('category') === 'hardware' ? 'selected' : '' }}>Hardware (ฮาร์ดแวร์)</option>
                    <option value="software" {{ request('category') === 'software' ? 'selected' : '' }}>Software (ซอฟต์แวร์)</option>
                    <option value="network" {{ request('category') === 'network' ? 'selected' : '' }}>Network (เครือข่าย)</option>
                    <option value="service" {{ request('category') === 'service' ? 'selected' : '' }}>Service (บริการ)</option>
                    <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Other (อื่นๆ)</option>
                </select>
            </div>

            <!-- Action buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm transition-all shadow-md">
                    กรองข้อมูล
                </button>
                <a href="{{ route('procurements.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-sm transition-all border border-slate-200">
                    ล้าง
                </a>
            </div>

        </form>
    </div>

    <!-- Requests Table Panel -->
    <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-bold text-slate-800">รายการคำขอจัดซื้อจัดจ้าง</h3>
            <a href="{{ route('procurements.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                สร้างคำขอใหม่
            </a>
        </div>

        @if($requests->isEmpty())
            <div class="py-16 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-sm font-semibold text-slate-400">ไม่พบข้อมูลใบขอจัดซื้อตามเงื่อนไขที่ระบุ</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="py-3.5 text-xs font-bold uppercase text-slate-400 tracking-wider">เลขที่เอกสาร / หัวข้อ</th>
                            <th class="py-3.5 text-xs font-bold uppercase text-slate-400 tracking-wider">ผู้ยื่นคำขอ</th>
                            <th class="py-3.5 text-xs font-bold uppercase text-slate-400 tracking-wider">หมวดหมู่</th>
                            <th class="py-3.5 text-xs font-bold uppercase text-slate-400 tracking-wider">งบประมาณ</th>
                            <th class="py-3.5 text-xs font-bold uppercase text-slate-400 tracking-wider">สถานะ workflow</th>
                            <th class="py-3.5 text-xs font-bold uppercase text-slate-400 tracking-wider">วันที่บันทึก</th>
                            <th class="py-3.5 text-xs font-bold uppercase text-slate-400 tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($requests as $request)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4">
                                    <div class="space-y-0.5">
                                        <p class="text-xs font-bold text-slate-400">{{ $request->request_no }}</p>
                                        <p class="text-sm font-bold text-slate-700">{{ $request->title }}</p>
                                    </div>
                                </td>
                                <td class="py-4 text-sm text-slate-600">
                                    <div class="flex flex-col">
                                        <span class="font-bold">{{ $request->requester->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-semibold">{{ $request->department->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 text-sm text-slate-500 capitalize">
                                    {{ $request->category }}
                                </td>
                                <td class="py-4 text-sm font-extrabold text-slate-700">
                                    ฿{{ number_format($request->estimated_budget, 2) }}
                                </td>
                                <td class="py-4">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full border
                                        @if($request->status === 'completed') bg-emerald-50 text-emerald-700 border-emerald-200
                                        @elseif($request->status === 'rejected') bg-rose-50 text-rose-700 border-rose-200
                                        @elseif($request->status === 'draft') bg-slate-50 text-slate-600 border-slate-200
                                        @elseif($request->status === 'approved_cao' || $request->status === 'pr_created' || $request->status === 'po_created') bg-indigo-50 text-indigo-700 border-indigo-200
                                        @else bg-amber-50 text-amber-700 border-amber-200 @endif">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td class="py-4 text-xs text-slate-400 font-bold">
                                    {{ $request->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('procurements.show', $request->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 hover:text-slate-800 text-slate-600 text-xs font-bold rounded-lg border border-slate-200 transition-colors">
                                        รายละเอียด
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $requests->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

@endsection
