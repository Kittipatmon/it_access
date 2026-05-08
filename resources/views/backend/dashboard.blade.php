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
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Approved</p>
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
                        <td class="px-8 py-5 text-sm text-slate-700 font-medium">{{ $request->user->name }}</td>
                        <td class="px-8 py-5 text-sm">
                            @if($request->status == 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-orange-50 text-orange-600 border border-orange-100 uppercase">Pending</span>
                            @elseif($request->status == 'approved')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-600 border border-green-100 uppercase">Approved</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase">Rejected</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-sm">
                            @php $currentStep = $request->steps->where('step_order', $request->current_step)->first(); @endphp
                            <span class="text-slate-400 text-xs">{{ $currentStep ? $currentStep->step_name : 'Complete' }}</span>
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
