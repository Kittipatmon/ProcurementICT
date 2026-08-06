@extends('layouts.app')

@section('title', 'งบประมาณแผนก')
@section('page_title', 'งบประมาณการจัดซื้อแยกตามแผนก')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Budgets List (Hospital-style dense grid) -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="mb-2 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-blue-900 border-l-4 border-blue-600 pl-3">งบประมาณการจัดซื้อ</h2>
                    <p class="text-sm text-slate-500 mt-1 pl-4">ข้อมูลการจัดสรรและการเบิกจ่ายงบประมาณรายแผนก</p>
                </div>
            </div>

            <div class="bg-white border border-slate-300 shadow-md rounded-lg overflow-hidden">
                <div class="bg-blue-800 px-4 py-3 flex items-center justify-between text-white border-b-4 border-blue-900">
                    <h3 class="text-sm font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        งบประมาณประจำปี 2026
                    </h3>
                    @if(in_array(Auth::user()->procurement_role, ['admin', 'cao']))
                        <button type="button" onclick="resetForm(); document.getElementById('department_id').focus();" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded border border-blue-500 text-xs flex items-center gap-1.5 transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            เพิ่มงบประมาณ
                        </button>
                    @endif
                </div>
                
                @if($budgets->isEmpty())
                    <div class="p-8 text-center bg-slate-50">
                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-slate-500 text-sm font-semibold">ไม่มีข้อมูลการจัดสรรงบประมาณในขณะนี้</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-blue-50 text-blue-900 border-b-2 border-blue-200 uppercase tracking-wide">
                                    <th class="border-r border-blue-200 py-2.5 px-3 font-bold">ชื่อแผนก / ชื่อรายการ</th>
                                    <th class="border-r border-blue-200 py-2.5 px-2 text-center font-bold">ปีงบประมาณ</th>
                                    <th class="border-r border-blue-200 py-2.5 px-3 text-right font-bold">งบจัดสรร</th>
                                    <th class="border-r border-blue-200 py-2.5 px-3 text-right font-bold">งบใช้ไป</th>
                                    <th class="border-r border-blue-200 py-2.5 px-3 text-right font-bold">คงเหลือ</th>
                                    <th class="border-r border-blue-200 py-2.5 px-2 text-center font-bold">สัดส่วนที่ใช้</th>
                                    @if(in_array(Auth::user()->procurement_role, ['admin', 'cao']))
                                        <th class="py-2.5 px-3 text-center font-bold">การจัดการ</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($budgets as $b)
                                    @php
                                        $percent = $b->allocated_budget > 0 ? ($b->used_budget / $b->allocated_budget) * 100 : 0;
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <!-- ชื่อแผนก / ชื่อรายการ -->
                                        <td class="border border-slate-200 py-2.5 px-3 font-semibold text-slate-900">
                                            {{ $b->department->name ?? 'ไม่ระบุแผนก (ID: ' . $b->department_id . ')' }}
                                            @if($b->name)
                                                <span class="text-blue-700 ml-1">({{ $b->name }})</span>
                                            @endif
                                        </td>
                                        
                                        <!-- ปีงบประมาณ -->
                                        <td class="border border-slate-200 py-2.5 px-2 text-center font-medium text-slate-700">
                                            {{ $b->fiscal_year }}
                                        </td>
                                        
                                        <!-- งบจัดสรร -->
                                        <td class="border border-slate-200 py-2.5 px-3 text-right font-mono font-bold text-slate-700 bg-slate-50/20">
                                            ฿{{ number_format($b->allocated_budget, 2) }}
                                        </td>
                                        
                                        <!-- งบใช้ไป -->
                                        <td class="border border-slate-200 py-2.5 px-3 text-right font-mono font-bold text-emerald-700 bg-slate-50/20">
                                            ฿{{ number_format($b->used_budget, 2) }}
                                        </td>
                                        
                                        <!-- คงเหลือ -->
                                        <td class="border border-slate-200 py-2.5 px-3 text-right font-mono font-bold text-blue-800 bg-blue-50/10">
                                            ฿{{ number_format($b->remaining_budget, 2) }}
                                        </td>
                                        
                                        <!-- สัดส่วนที่ใช้ -->
                                        <td class="border border-slate-200 py-2.5 px-2 text-center whitespace-nowrap">
                                            <div class="flex items-center gap-1.5 justify-center">
                                                <span class="font-bold text-[10px] text-slate-700">{{ number_format($percent, 1) }}%</span>
                                                <div class="w-12 h-1.5 bg-slate-200 rounded overflow-hidden">
                                                    <div class="h-full bg-blue-700" style="width: {{ min($percent, 100) }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- การจัดการ -->
                                        @if(in_array(Auth::user()->procurement_role, ['admin', 'cao']))
                                            <td class="border border-slate-200 py-2.5 px-3 text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button type="button" title="แก้ไข"
                                                            onclick="loadEditData('{{ $b->id }}', '{{ $b->department_id }}', '{{ $b->fiscal_year }}', '{{ $b->allocated_budget }}', '{{ addslashes($b->name ?? '') }}', '{{ addslashes($b->department->name ?? 'ไม่ระบุแผนก') }}')"
                                                            class="w-7 h-7 rounded bg-white border border-slate-200 shadow-sm flex items-center justify-center text-blue-600 hover:bg-blue-50 hover:border-blue-300 transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                    <form id="delete-form-{{ $b->id }}" action="{{ route('budgets.destroy', $b->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" title="ลบ" onclick="confirmDelete('{{ $b->id }}', '{{ addslashes($b->department->name ?? 'ไม่ระบุแผนก') }}')" class="w-7 h-7 rounded bg-white border border-slate-200 shadow-sm flex items-center justify-center text-rose-500 hover:bg-rose-50 hover:border-rose-300 transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right 1 Col: Allocate Budget Form (Admin/CAO only) -->
        <div>
            @if(in_array(Auth::user()->procurement_role, ['admin', 'cao']))
                <div class="bg-white border border-slate-200 p-6 rounded-lg space-y-4">
                    <h3 id="form_title" class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">จัดสรรงบประมาณ (Allocate Budget)</h3>
                    <p class="text-xs text-slate-500">ระบุการปรับปรุงหรือเพิ่มยอดงบประมาณให้แต่ละแผนกรายปี</p>
                    
                    <form action="{{ route('budgets.store') }}" method="POST" class="space-y-4 pt-2">
                        @csrf
                        <input type="hidden" name="is_edit" id="is_edit" value="0">
                        <input type="hidden" name="budget_id" id="budget_id" value="">
                        
                        <div>
                            <label for="name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ชื่อรายการงบประมาณ (ไม่บังคับ)</label>
                            <input type="text" name="name" id="name" placeholder="เช่น งบไอทีส่วนกลาง" class="w-full bg-slate-55 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="department_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">เลือกแผนก</label>
                            <select name="department_id" id="department_id" required class="w-full bg-slate-55 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                <option value="">เลือก...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="fiscal_year" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ปีงบประมาณ</label>
                            <input type="number" name="fiscal_year" id="fiscal_year" required value="2026" class="w-full bg-slate-55 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="allocated_budget" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">งบประมาณจัดสรร (บาท)</label>
                            <input type="number" step="0.01" name="allocated_budget" id="allocated_budget" required placeholder="เช่น 500000.00" class="w-full bg-slate-55 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>

                        <div class="flex flex-col gap-2 pt-2">
                            <button type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-600 text-white font-bold rounded text-xs transition-colors shadow-sm">
                                บันทึกการจัดสรรงบ
                            </button>
                            <button type="button" id="cancel_edit_btn" onclick="resetForm()" class="hidden w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded text-xs transition-colors border border-slate-200">
                                ยกเลิกการแก้ไข (สลับมาเพิ่มใหม่)
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white border border-slate-200 p-6 rounded-lg">
                    <p class="text-xs text-slate-400 text-center">สิทธิ์การใช้งานของคุณไม่สามารถระบุการจัดสรรงบประมาณได้ (เฉพาะผู้ดูแลระบบหรือ CAO)</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Script for Delete Confirmations & Edit Feedbacks via SweetAlert2 -->
    <script>
        function confirmDelete(id, deptName) {
            Swal.fire({
                title: 'ยืนยันการลบงบประมาณ?',
                text: `คุณต้องการลบข้อมูลการจัดสรรงบประมาณของแผนก ${deptName} หรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', 
                cancelButtonColor: '#64748b', 
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    popup: 'rounded-lg border border-slate-200 shadow-lg font-sans',
                    title: 'font-bold text-slate-900 text-base',
                    htmlContainer: 'text-slate-600 text-xs',
                    confirmButton: 'rounded px-4 py-2 font-bold text-xs shadow transition-colors ml-2',
                    cancelButton: 'rounded px-4 py-2 font-semibold text-xs transition-colors mr-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        function loadEditData(id, departmentId, fiscalYear, allocatedBudget, name, deptName) {
            document.getElementById('budget_id').value = id;
            document.getElementById('department_id').value = departmentId;
            document.getElementById('fiscal_year').value = fiscalYear;
            document.getElementById('allocated_budget').value = allocatedBudget;
            document.getElementById('name').value = name;
            document.getElementById('form_title').innerText = 'แก้ไขการจัดสรรงบประมาณ';
            document.getElementById('is_edit').value = '1';
            
            const cancelBtn = document.getElementById('cancel_edit_btn');
            if (cancelBtn) {
                cancelBtn.classList.remove('hidden');
            }

            document.getElementById('department_id').focus();
            
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            
            toast.fire({
                icon: 'info',
                title: `โหลดข้อมูลแผนก ${deptName} เพื่อทำการแก้ไขแล้ว`
            });
        }

        function resetForm() {
            document.getElementById('budget_id').value = '';
            document.getElementById('department_id').value = '';
            document.getElementById('fiscal_year').value = '2026';
            document.getElementById('allocated_budget').value = '';
            document.getElementById('name').value = '';
            document.getElementById('form_title').innerText = 'จัดสรรงบประมาณ (Allocate Budget)';
            document.getElementById('is_edit').value = '0';
            
            const cancelBtn = document.getElementById('cancel_edit_btn');
            if (cancelBtn) {
                cancelBtn.classList.add('hidden');
            }
        }
    </script>

@endsection
