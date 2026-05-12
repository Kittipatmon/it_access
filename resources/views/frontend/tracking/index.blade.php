@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-slate-800">ติดตามสถานะคำร้อง</h1>
        <a href="{{ route('request.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            สร้างคำร้องใหม่
        </a>
    </div>

    {{-- งานที่รอคุณดำเนินการ --}}
    @if($toApprove->count() > 0 || $toAcknowledge->count() > 0)
    <div class="mb-10 space-y-4">
        <h3 class="text-sm font-bold text-orange-600 uppercase tracking-widest flex items-center gap-2">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
            </span>
            งานที่รอคุณดำเนินการ ({{ $toApprove->count() + $toAcknowledge->count() }})
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($toApprove as $req)
            <a href="{{ route('tracking.show', $req->request_no) }}" class="block p-5 bg-orange-50 border border-orange-200 rounded-2xl hover:shadow-md transition group">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-bold text-orange-700 bg-white px-2 py-1 rounded-lg border border-orange-200">รอคุณอนุมัติ</span>
                    <span class="text-[10px] text-orange-400 font-medium">{{ $req->created_at->diffForHumans() }}</span>
                </div>
                <div class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition">{{ $req->request_no }}</div>
                <div class="text-xs text-slate-500 mt-1">{{ $req->firstname }} {{ $req->lastname }}</div>
                <div class="mt-3 flex items-center text-[10px] font-bold text-blue-600">
                    คลิกเพื่อดำเนินการ
                    <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
            @endforeach

            @foreach($toAcknowledge as $req)
            <a href="{{ route('tracking.show', $req->request_no) }}" class="block p-5 bg-blue-50 border border-blue-200 rounded-2xl hover:shadow-md transition group">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-bold text-blue-700 bg-white px-2 py-1 rounded-lg border border-blue-200">รอคุณยืนยันรับทราบ</span>
                    <span class="text-[10px] text-blue-400 font-medium">{{ $req->created_at->diffForHumans() }}</span>
                </div>
                <div class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition">{{ $req->request_no }}</div>
                <div class="text-xs text-slate-500 mt-1">{{ $req->firstname }} {{ $req->lastname }}</div>
                <div class="mt-3 flex items-center text-[10px] font-bold text-blue-600">
                    คลิกเพื่อดำเนินการ
                    <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">รายการคำร้องทั้งหมด</h3>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        {{-- Desktop View --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] uppercase tracking-widest font-black">
                    <tr>
                        <th class="px-8 py-4">เลขที่คำร้อง</th>
                        <th class="px-8 py-4">ผู้ส่งคำร้อง</th>
                        <th class="px-8 py-4">สถานะ</th>
                        <th class="px-8 py-4">ผู้อนุมัติลำดับถัดไป</th>
                        <th class="px-8 py-4">วันที่ส่ง</th>
                        <th class="px-8 py-4 text-right">เครื่องมือ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $request)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-blue-600">{{ $request->request_no }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm font-bold text-slate-700">{{ $request->firstname }} {{ $request->lastname }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ $request->department_name }}</div>
                            </td>
                            <td class="px-8 py-5">
                                @if($request->status == 'pending')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">รออนุมัติ</span>
                                @elseif($request->status == 'completed')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-600 text-white border border-green-700 shadow-sm">เสร็จสมบูรณ์</span>
                                @elseif($request->status == 'approved' && $request->it_status == 'completed')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">รอคุณยืนยัน</span>
                                @elseif($request->status == 'approved')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">อนุมัติแล้ว</span>
                                @else
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">ถูกปฏิเสธ</span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                @if($request->status == 'pending')
                                    @php
                                        $currentStep = $request->steps->where('step_order', $request->current_step)->where('status', 'pending')->first();
                                    @endphp
                                    <span class="text-xs font-bold text-orange-600">{{ $currentStep->step_name ?? '-' }}</span>
                                @elseif($request->status == 'approved' && $request->it_status == 'completed')
                                    <span class="text-xs font-bold text-orange-600 animate-pulse">รอผู้ใช้งานยืนยัน</span>
                                @elseif($request->status == 'approved')
                                    <span class="text-xs font-bold text-blue-600">IT ดำเนินการ</span>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs text-slate-500 font-medium">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('tracking.show', $request->request_no) }}" class="inline-flex items-center px-4 py-1.5 border border-blue-600 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-600 hover:text-white transition group-hover:scale-105">
                                    ดูรายละเอียด
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-slate-400 font-medium">ยังไม่มีรายการคำร้องในขณะนี้</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile View --}}
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($requests as $request)
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-xs font-black text-blue-600 mb-1 uppercase tracking-tighter">{{ $request->request_no }}</div>
                            <div class="text-sm font-bold text-slate-800">{{ $request->firstname }} {{ $request->lastname }}</div>
                            <div class="text-[10px] text-slate-500">{{ $request->department_name }}</div>
                        </div>
                        <div>
                            @if($request->status == 'pending')
                                <span class="px-2 py-0.5 inline-flex text-[9px] font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200 uppercase">รออนุมัติ</span>
                            @elseif($request->status == 'completed')
                                <span class="px-2 py-0.5 inline-flex text-[9px] font-bold rounded-full bg-green-600 text-white border border-green-700 uppercase shadow-sm">เสร็จสมบูรณ์</span>
                            @elseif($request->status == 'approved' && $request->it_status == 'completed')
                                <span class="px-2 py-0.5 inline-flex text-[9px] font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200 uppercase">รอคุณยืนยัน</span>
                            @elseif($request->status == 'approved')
                                <span class="px-2 py-0.5 inline-flex text-[9px] font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200 uppercase">อนุมัติแล้ว</span>
                            @else
                                <span class="px-2 py-0.5 inline-flex text-[9px] font-bold rounded-full bg-red-100 text-red-800 border border-red-200 uppercase">ถูกปฏิเสธ</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">สถานะปัจจุบัน</span>
                            @if($request->status == 'pending')
                                @php
                                    $currentStep = $request->steps->where('step_order', $request->current_step)->where('status', 'pending')->first();
                                @endphp
                                <span class="text-[10px] font-bold text-orange-600">{{ $currentStep->step_name ?? '-' }}</span>
                            @elseif($request->status == 'approved' && $request->it_status == 'completed')
                                <span class="text-[10px] font-bold text-orange-600">รอผู้ใช้งานยืนยัน</span>
                            @elseif($request->status == 'approved')
                                <span class="text-[10px] font-bold text-blue-600">IT ดำเนินการ</span>
                            @else
                                <span class="text-[10px] text-slate-400 font-medium">-</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">วันที่ส่ง</span>
                            <span class="text-[10px] text-slate-600">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('tracking.show', $request->request_no) }}" class="block w-full text-center py-2.5 bg-white border-2 border-slate-900 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition active:scale-[0.98]">
                        ดูรายละเอียด
                    </a>
                </div>
            @empty
                <div class="p-10 text-center">
                    <p class="text-slate-400 font-medium text-sm">ยังไม่มีรายการคำร้องในขณะนี้</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection