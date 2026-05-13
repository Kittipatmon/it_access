@extends('layouts.admin')

@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">IT Access Overview</h2>
        <p class="text-sm text-slate-400">สรุปสถานะการร้องขอสิทธิในระบบทั้งหมด</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pending</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['pending'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-[10px] text-orange-600 font-bold mt-4">ต้องการการพิจารณา</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">IT Action</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['it_pending'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-gears text-xl"></i>
                </div>
            </div>
            <p class="text-[10px] text-blue-600 font-bold mt-4">รอ IT ดำเนินการ</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Completed</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['approved'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-500 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <p class="text-[10px] text-green-600 font-bold mt-4">ดำเนินการเสร็จสิ้น</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Rejected</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['rejected'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-50 text-red-500 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <p class="text-[10px] text-red-600 font-bold mt-4">คำร้องที่ไม่ผ่าน</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Users</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ \App\Models\User::count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-[10px] text-blue-600 font-bold mt-4">พนักงานในระบบ</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 tracking-tight">Recent Requests</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-4 font-bold">Request No</th>
                        <th class="px-8 py-4 font-bold">Requester</th>
                        <th class="px-8 py-4 font-bold">Status</th>
                        <th class="px-8 py-4 font-bold">Step</th>
                        <th class="px-8 py-4 font-bold text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($allRequests as $request)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-5 text-sm font-bold text-blue-600">{{ $request->request_no }}</td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-medium text-slate-700">{{ $request->firstname }} {{ $request->lastname }}</div>
                            <div class="text-[10px] text-slate-400 uppercase font-bold">{{ $request->department_name }}</div>
                        </td>
                        <td class="px-8 py-5">
                            @if($request->status === 'completed')
                                <span class="px-2 py-0.5 rounded-full bg-green-600 text-white text-[10px] font-bold uppercase shadow-sm">เสร็จสมบูรณ์</span>
                            @elseif($request->status === 'approved' && $request->it_status == 'completed')
                                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-bold uppercase border border-blue-200">รอผู้ใช้งานยืนยัน</span>
                            @elseif($request->status === 'approved')
                                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-bold uppercase">อนุมัติแล้ว</span>
                            @elseif($request->status === 'rejected')
                                <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[10px] font-bold uppercase">ปฏิเสธ</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-600 text-[10px] font-bold uppercase">รอดำเนินการ</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-500">
                            @if($request->status === 'completed')
                                <span class="inline-flex items-center gap-1 text-green-600 font-bold text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    เสร็จสมบูรณ์
                                </span>
                            @elseif($request->status === 'approved' && $request->it_status == 'completed')
                                <span class="inline-flex items-center gap-1 text-orange-500 font-bold text-xs animate-pulse">
                                    รอผู้ใช้งานยืนยัน
                                </span>
                            @elseif($request->status === 'approved')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-bold text-[11px] animate-pulse border border-blue-200 shadow-sm">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                    เจ้าหน้าที่ IT ดำเนินการ
                                </span>
                            @elseif($request->status === 'rejected')
                                <span class="text-red-400 text-xs font-medium">ถูกยกเลิก</span>
                            @else
                                {{ $request->steps->where('status', 'pending')->first()->step_name ?? '-' }}
                            @endif
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-400 text-right">{{ $request->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
