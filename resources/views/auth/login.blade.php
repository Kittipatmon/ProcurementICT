<!DOCTYPE html>
<html lang="th" class="h-full bg-slate-100 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | Procurement Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Instrument Sans', 'Sarabun', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-y-auto py-12 px-4 bg-slate-100">
    <!-- Subtle Background Elements -->
    <div class="absolute w-[500px] h-[500px] rounded-full bg-indigo-500/5 blur-[120px] top-[-10%] left-[-10%]"></div>
    <div class="absolute w-[600px] h-[600px] rounded-full bg-purple-500/5 blur-[130px] bottom-[-20%] right-[-10%]"></div>

    <div class="w-full max-w-md bg-white border border-slate-200/80 p-10 rounded-3xl shadow-xl relative z-10">
        <!-- Brand/Icon -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-md shadow-indigo-500/10 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">เข้าสู่ระบบ</h2>
            <p class="text-sm text-slate-500 mt-1 font-medium text-center">ระบบติดตามสถานะจัดซื้อและดำเนินงาน ICT Enterprise</p>
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

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="emp_code" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">รหัสพนักงาน (Employee Code)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </span>
                    <input type="text" name="emp_code" id="emp_code" required placeholder="เช่น EMP001" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 pl-12 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-200">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">รหัสผ่าน</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3.5 pl-12 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-200">
                </div>
            </div>

            <div class="flex items-center justify-between py-1">
                <label class="flex items-center gap-2 cursor-pointer text-slate-500 text-sm font-semibold select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-50 border border-slate-200 text-indigo-600 focus:ring-0 focus:ring-offset-0">
                    จดจำฉันในระบบ
                </label>
            </div>

            <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600 text-white font-bold rounded-2xl text-sm transition-all duration-300 transform hover:scale-[1.01] active:scale-[0.99] shadow-md shadow-indigo-500/10">
                เข้าสู่ระบบ
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-sm text-slate-500">ยังไม่มีบัญชีผู้ใช้ในระบบ? <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 underline transition-colors">ลงทะเบียนที่นี่</a></p>
        </div>
    </div>
</body>
</html>
