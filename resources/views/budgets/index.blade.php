@extends('layouts.app')

@section('title', 'งบประมาณแผนก')
@section('page_title', 'งบประมาณการจัดซื้อแยกตามแผนก')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Budgets List -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-bold text-slate-800">งบประมาณประจำปี 2026</h3>
                    @if(in_array(Auth::user()->procurement_role, ['admin', 'cao']))
                        <button type="button" onclick="resetForm(); document.getElementById('department_id').focus();" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            เพิ่มงบประมาณ
                        </button>
                    @endif
                </div>
                
                <div class="space-y-6">
                    @foreach($budgets as $b)
                        @php
                            $percent = $b->allocated_budget > 0 ? ($b->used_budget / $b->allocated_budget) * 100 : 0;
                        @endphp
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:border-indigo-500/20 hover:bg-white transition-all space-y-4 shadow-sm relative group/card">
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-slate-700 text-sm">{{ $b->department->name ?? 'ไม่ระบุแผนก (ID: ' . $b->department_id . ')' }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">ปีงบประมาณ {{ $b->fiscal_year }}</p>
                                </div>
                                <span class="text-xs font-bold text-slate-500">ถูกใช้ไป {{ number_format($percent, 1) }}%</span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500" style="width: {{ min($percent, 100) }}%"></div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 text-xs">
                                <div>
                                    <span class="text-slate-400">งบจัดสรร:</span>
                                    <p class="font-bold text-slate-700 mt-1">฿{{ number_format($b->allocated_budget, 2) }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-400">งบใช้ไป:</span>
                                    <p class="font-bold text-emerald-600 mt-1">฿{{ number_format($b->used_budget, 2) }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-400">คงเหลือ:</span>
                                    <p class="font-bold text-indigo-650 mt-1">฿{{ number_format($b->remaining_budget, 2) }}</p>
                                </div>
                            </div>

                            @if(in_array(Auth::user()->procurement_role, ['admin', 'cao']))
                                <!-- Action Buttons (Edit / Delete) - Relocated to Bottom Right -->
                                <div class="absolute bottom-6 right-6 flex items-center gap-2 opacity-0 group-hover/card:opacity-100 transition-opacity">
                                    <!-- Edit Button (Loads data to form on the right) -->
                                    <button type="button" 
                                            onclick="loadEditData('{{ $b->department_id }}', '{{ $b->fiscal_year }}', '{{ $b->allocated_budget }}', '{{ addslashes($b->department->name ?? 'ไม่ระบุแผนก') }}')"
                                            class="p-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-150 text-indigo-600 transition-colors" title="แก้ไข">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <!-- Delete Button -->
                                    <form id="delete-form-{{ $b->id }}" action="{{ route('budgets.destroy', $b->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $b->id }}', '{{ addslashes($b->department->name ?? 'ไม่ระบุแผนก') }}')" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition-colors" title="ลบ">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Allocate Budget Form (Admin/CAO only) -->
        <div>
            @if(in_array(Auth::user()->procurement_role, ['admin', 'cao']))
                <div class="bg-white border border-slate-200 p-8 rounded-3xl space-y-4 shadow-sm">
                    <h3 id="form_title" class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">จัดสรรงบประมาณ (Allocate Budget)</h3>
                    <p class="text-xs text-slate-550">ระบุการปรับปรุงหรือเพิ่มยอดงบประมาณให้แต่ละแผนกรายปี</p>
                    
                    <form action="{{ route('budgets.store') }}" method="POST" class="space-y-4 pt-2">
                        @csrf
                        
                        <div>
                            <label for="department_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">เลือกแผนก</label>
                            <select name="department_id" id="department_id" required class="w-full bg-slate-55 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                                <option value="">เลือก...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="fiscal_year" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">ปีงบประมาณ</label>
                            <input type="number" name="fiscal_year" id="fiscal_year" required value="2026" class="w-full bg-slate-55 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="allocated_budget" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">งบประมาณจัดสรร (บาท)</label>
                            <input type="number" step="0.01" name="allocated_budget" id="allocated_budget" required placeholder="เช่น 500000.00" class="w-full bg-slate-55 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-indigo-500">
                        </div>

                        <div class="flex flex-col gap-2">
                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-xs transition-all shadow-md">
                                บันทึกการจัดสรรงบ
                            </button>
                            <button type="button" id="cancel_edit_btn" onclick="resetForm()" class="hidden w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 font-semibold rounded-2xl text-xs transition-all border border-slate-200">
                                ยกเลิกการแก้ไข (สลับมาเพิ่มใหม่)
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
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
                confirmButtonColor: '#e11d48', // rose-600
                cancelButtonColor: '#64748b', // slate-500
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    popup: 'rounded-3xl border border-slate-200 shadow-xl font-sans',
                    title: 'font-bold text-slate-800 text-lg',
                    htmlContainer: 'text-slate-500 text-xs',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold text-xs shadow-md transition-all ml-2',
                    cancelButton: 'rounded-xl px-4 py-2 font-semibold text-xs transition-all mr-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        function loadEditData(departmentId, fiscalYear, allocatedBudget, deptName) {
            document.getElementById('department_id').value = departmentId;
            document.getElementById('fiscal_year').value = fiscalYear;
            document.getElementById('allocated_budget').value = allocatedBudget;
            document.getElementById('form_title').innerText = 'แก้ไขการจัดสรรงบประมาณ';
            
            // Show cancel edit button
            const cancelBtn = document.getElementById('cancel_edit_btn');
            if (cancelBtn) {
                cancelBtn.classList.remove('hidden');
            }

            document.getElementById('department_id').focus();
            
            // Toast notification
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
            document.getElementById('department_id').value = '';
            document.getElementById('fiscal_year').value = '2026';
            document.getElementById('allocated_budget').value = '';
            document.getElementById('form_title').innerText = 'จัดสรรงบประมาณ (Allocate Budget)';
            
            // Hide cancel edit button
            const cancelBtn = document.getElementById('cancel_edit_btn');
            if (cancelBtn) {
                cancelBtn.classList.add('hidden');
            }
        }
    </script>

@endsection
