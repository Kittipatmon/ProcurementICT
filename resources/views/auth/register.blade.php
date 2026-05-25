<!DOCTYPE html>
<html lang="th" class="h-full bg-slate-100 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนเข้าใช้งาน | Procurement Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Instrument Sans', 'Sarabun', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-y-auto py-12 px-4 bg-slate-100">
    <div class="absolute w-[500px] h-[500px] rounded-full bg-indigo-500/5 blur-[120px] top-[-5%] left-[-5%]"></div>
    <div class="absolute w-[600px] h-[600px] rounded-full bg-purple-500/5 blur-[130px] bottom-[-10%] right-[-5%]"></div>

    <div class="w-full max-w-lg bg-white border border-slate-200/80 p-10 rounded-3xl shadow-xl relative z-10">
        <div class="flex flex-col items-center mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">ลงทะเบียนพนักงานใหม่</h2>
            <p class="text-sm text-slate-500 mt-1 font-medium text-center">สร้างบัญชีผู้ใช้เพื่อเปิดและติดตามเอกสารจัดซื้อจัดจ้าง ICT</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-700">
                <ul class="text-xs font-semibold space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="emp_code" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">รหัสพนักงาน</label>
                    <input type="text" name="emp_code" id="emp_code" required value="{{ old('emp_code') }}" placeholder="เช่น EMP102" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                </div>

                <div>
                    <label for="dept_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">แผนก / สังกัด</label>
                    <select name="dept_id" id="dept_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm text-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                        <option value="">เลือกแผนก...</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('dept_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="firstname" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">ชื่อจริง (ภาษาไทย)</label>
                    <input type="text" name="firstname" id="firstname" required value="{{ old('firstname') }}" placeholder="สมบัติ" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                </div>

                <div>
                    <label for="lastname" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">นามสกุล</label>
                    <input type="text" name="lastname" id="lastname" required value="{{ old('lastname') }}" placeholder="รักดี" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">อีเมลบริษัท</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="name@company.com" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>

            <div>
                <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">ชื่อผู้ใช้ (Username สำหรับ Login)</label>
                <input type="text" name="username" id="username" required value="{{ old('username') }}" placeholder="เช่น sombat_r" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">รหัสผ่าน</label>
                    <input type="password" name="password" id="password" required placeholder="อย่างน้อย 6 หลัก" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">ยืนยันรหัสผ่าน</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="ป้อนรหัสผ่านอีกครั้ง" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                </div>
            </div>

            <button type="submit" class="w-full mt-2 py-4 px-6 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600 text-white font-bold rounded-2xl text-sm transition-all duration-300 transform hover:scale-[1.01] active:scale-[0.99] shadow-md shadow-indigo-500/10">
                ลงทะเบียนพนักงาน
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-sm text-slate-500">มีบัญชีผู้ใช้อยู่แล้ว? <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 underline transition-colors">เข้าสู่ระบบที่นี่</a></p>
        </div>
    </div>
</body>
</html>
