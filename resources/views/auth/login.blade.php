<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - IT Access Request</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=kanit:300,400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Kanit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">
        <div class="p-8">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-800">IT ACCESS REQUEST</h1>
                <p class="text-slate-500 text-sm mt-1">กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="emp_code" class="block text-sm font-bold text-slate-700 mb-2">รหัสพนักงาน</label>
                    <input type="text" id="emp_code" name="emp_code" value="{{ old('emp_code') }}" required 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                           placeholder="กรอกรหัสพนักงาน...">
                    @error('emp_code')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">รหัสผ่าน</label>
                    <input type="password" name="password" id="password" required 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" 
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-slate-600">จดจำฉันไว้</label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                        เข้าสู่ระบบ
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-slate-400 text-xs">
                © 2026 IT Department. All rights reserved.
            </div>
        </div>
        
        <!-- ข้อมูลทดสอบ (ลบออกภายหลังได้) -->
        <div class="bg-slate-50 p-6 border-t border-slate-100">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">บัญชีทดสอบ</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-600">User: EMP004</span>
                    <span class="text-slate-400">pass: password</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-600">Admin: EMP001</span>
                    <span class="text-slate-400">pass: password</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
