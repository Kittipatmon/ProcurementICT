@extends('layouts.app')

@section('title', 'สร้างคำขอจัดซื้อใหม่')
@section('page_title', 'เปิดคำขอจัดซื้อและดำเนินงาน ICT ใหม่')

@section('content')

    <form action="{{ route('procurements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Main Form Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Panel: General Information -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 p-6 rounded-lg space-y-4">
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 uppercase tracking-wider">ข้อมูลพื้นฐานของเอกสาร</h3>

                    <div>
                        <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">หัวข้อเรื่อง / อุปกรณ์ที่ต้องการจัดซื้อ</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}" placeholder="เช่น ขออนุมัติจัดซื้อเครื่อง Notebook สำหรับโปรแกรมเมอร์ใหม่ 2 เครื่อง" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">หมวดหมู่</label>
                            <select name="category" id="category" required class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->slug }}" {{ old('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">ระดับความเร่งด่วน</label>
                            <select name="priority" id="priority" required class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low (ทั่วไป)</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium (ปานกลาง)</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High (ด่วน)</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent (ด่วนที่สุด)</option>
                            </select>
                        </div>

                        <div>
                            <label for="expected_date" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">วันที่ต้องการใช้งานอุปกรณ์</label>
                            <input type="date" name="expected_date" id="expected_date" value="{{ old('expected_date') }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label for="next_renewal_date" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">วันที่ต่ออายุครั้งถัดไป</label>
                            <input type="date" name="next_renewal_date" id="next_renewal_date" value="{{ old('next_renewal_date') }}" class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">รายละเอียดความจำเป็นและเหตุผลความต้องการ</label>
                        <textarea name="description" id="description" rows="5" placeholder="ระบุเหตุผลความจำเป็นในการขอจัดซื้อจัดจ้างครั้งนี้..." class="w-full bg-slate-50 border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Procurement Items Section -->
                <div class="bg-white border border-slate-200 p-6 rounded-lg">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                        <h3 class="text-base font-bold text-slate-900 uppercase tracking-wider">รายการวัสดุ/อุปกรณ์จัดซื้อ (Items List)</h3>
                        <button type="button" onclick="addItemRow()" class="px-3 py-1.5 bg-blue-50 border border-blue-200 text-blue-800 hover:bg-blue-100 text-xs font-bold rounded transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            เพิ่มแถวรายการ
                        </button>
                    </div>

                    <div class="space-y-4" id="items-container">
                        <!-- Default Item Row 1 -->
                        <div class="p-4 rounded bg-slate-50 border border-slate-200 space-y-4 item-row relative">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ชื่อรายการอุปกรณ์/บริการ</label>
                                    <input type="text" name="items[0][item_name]" required placeholder="เช่น Notebook Dell Latitude 3440" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ผู้ขายที่เสนอราคา (Vendor)</label>
                                    <select name="items[0][vendor_id]" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                        <option value="">-- ไม่ระบุ / ให้จัดซื้อหาผู้ขาย --</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">คุณสมบัติเฉพาะ (Specifications)</label>
                                    <input type="text" name="items[0][specification]" placeholder="เช่น CPU Intel Core i5, RAM 16GB, SSD 512GB" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">จำนวน</label>
                                    <input type="number" name="items[0][quantity]" required min="1" value="1" oninput="calculateTotal(this)" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 quantity-input">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ราคาต่อหน่วย (บาท)</label>
                                    <input type="number" step="0.01" name="items[0][unit_price]" required min="0" value="0.00" oninput="calculateTotal(this)" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 price-input">
                                </div>
                            </div>
                            
                            <div class="text-right text-xs text-slate-500 font-bold">
                                ราคารวมรายการ: ฿<span class="row-total text-slate-700">0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Grand Total Summary -->
                    <div class="mt-4 flex items-center justify-between p-4 rounded bg-slate-50 border border-slate-200 text-right">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">งบประมาณรวมประมาณการ (Grand Total)</span>
                        <span class="text-base font-bold text-blue-700 font-mono">฿<span id="grand-total">0.00</span></span>
                    </div>
                </div>

            </div>

            <!-- Right Panel: Side attachments & submit -->
            <div class="space-y-6">
                
                <!-- File upload box -->
                <div class="bg-white border border-slate-200 p-6 rounded-lg space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">เอกสารแนบประกอบพิจารณา</h3>
                    <p class="text-xs text-slate-500">กรุณาแนบใบเสนอราคา (Quotation) หรือเอกสารสเปกการเปรียบเทียบราคา (เช่น PDF, JPG, Max: 10MB)</p>
                    
                    <div class="space-y-2">
                        <input type="file" name="attachments[]" id="attachment-input" multiple onchange="previewImages(this)" class="w-full bg-slate-50 border border-slate-200 rounded p-2 text-xs text-slate-500 focus:outline-none focus:border-blue-500">
                    </div>
                    <!-- Image Preview Container -->
                    <div id="image-preview-container" class="space-y-3 mt-3"></div>
                </div>

                <!-- Submit / Save Card -->
                <div class="bg-white border border-slate-200 p-6 rounded-lg space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-3 mb-2">การดำเนินการของคำขอ</h3>
                    <p class="text-xs text-slate-500">เมื่อบันทึกแล้ว เอกสารจะถูกบันทึกเป็น 'ฉบับร่าง (Draft)' คุณสามารถตรวจสอบข้อมูล ทบทวน และกดส่งขออนุมัติต่อไปได้ที่หน้ารายละเอียดคำขอ</p>
                    
                    <div class="pt-2 space-y-3">
                        <button type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-600 text-white font-bold rounded text-xs transition-colors shadow-sm">
                            บันทึกเป็นฉบับร่าง
                        </button>
                        <a href="{{ route('procurements.index') }}" class="block text-center w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-650 font-bold rounded text-xs border border-slate-200 transition-colors">
                            ยกเลิก
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </form>

    <script>
        let rowCount = 1;

        function addItemRow() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'p-4 rounded bg-slate-50 border border-slate-200 space-y-4 item-row relative';
            newRow.innerHTML = `
                <button type="button" onclick="removeRow(this)" class="absolute top-4 right-4 text-slate-400 hover:text-rose-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ชื่อรายการอุปกรณ์/บริการ</label>
                        <input type="text" name="items[${rowCount}][item_name]" required placeholder="เช่น อุปกรณ์สวิตช์เครือข่าย Cisco C9200" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ผู้ขายที่เสนอราคา (Vendor)</label>
                        <select name="items[${rowCount}][vendor_id]" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                            <option value="">-- ไม่ระบุ / ให้จัดซื้อหาผู้ขาย --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">คุณสมบัติเฉพาะ (Specifications)</label>
                        <input type="text" name="items[${rowCount}][specification]" placeholder="เช่น 24 Ports PoE, Gigabit, Layer 3" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">จำนวน</label>
                        <input type="number" name="items[${rowCount}][quantity]" required min="1" value="1" oninput="calculateTotal(this)" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 quantity-input">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">ราคาต่อหน่วย (บาท)</label>
                        <input type="number" step="0.01" name="items[${rowCount}][unit_price]" required min="0" value="0.00" oninput="calculateTotal(this)" class="w-full bg-white border border-slate-200 rounded px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-blue-500 price-input">
                    </div>
                </div>
                
                <div class="text-right text-xs text-slate-500 font-bold">
                    ราคารวมรายการ: ฿<span class="row-total text-slate-700">0.00</span>
                </div>
            `;
            container.appendChild(newRow);
            rowCount++;
            updateGrandTotal();
        }

        function removeRow(btn) {
            btn.closest('.item-row').remove();
            updateGrandTotal();
        }

        function calculateTotal(input) {
            const row = input.closest('.item-row');
            const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = qty * price;
            row.querySelector('.row-total').textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            updateGrandTotal();
        }

        function updateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                total += qty * price;
            });
            document.getElementById('grand-total').textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Image preview logic for attachments
        function previewImages(input) {
            const container = document.getElementById('image-preview-container');
            container.innerHTML = ''; // Clear old previews
            
            if (input.files && input.files.length > 0) {
                Array.from(input.files).forEach(file => {
                    const isImage = file.type.startsWith('image/');
                    
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'p-2 rounded bg-slate-50 border border-slate-200';
                    
                    const headerDiv = document.createElement('div');
                    headerDiv.className = 'flex items-center justify-between mb-2 text-xs';
                    
                    const nameSpan = document.createElement('span');
                    nameSpan.className = 'text-slate-700 font-bold truncate max-w-[200px]';
                    nameSpan.textContent = file.name;
                    nameSpan.title = file.name;
                    
                    const typeSpan = document.createElement('span');
                    typeSpan.className = 'text-[10px] text-slate-400 font-extrabold uppercase shrink-0';
                    typeSpan.textContent = isImage ? 'รูปภาพ' : 'เอกสาร';
                    
                    headerDiv.appendChild(nameSpan);
                    headerDiv.appendChild(typeSpan);
                    itemDiv.appendChild(headerDiv);
                    
                    if (isImage) {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'overflow-hidden rounded border border-slate-200 flex justify-center bg-white p-1 hover:border-blue-400 transition-colors block cursor-pointer';
                        
                        const img = document.createElement('img');
                        img.className = 'max-w-full h-auto object-contain max-h-[200px]';
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            img.src = e.target.result;
                            imgContainer.onclick = function() {
                                const w = window.open();
                                w.document.write('<html><body style="margin:0;display:flex;justify-content:center;align-items:center;background:#0f172a;min-height:100vh;"><img src="' + e.target.result + '" style="max-width:100%;max-height:100vh;"></body></html>');
                            };
                        };
                        reader.readAsDataURL(file);
                        
                        imgContainer.appendChild(img);
                        itemDiv.appendChild(imgContainer);
                    }
                    
                    container.appendChild(itemDiv);
                });
            }
        }
    </script>

@endsection
