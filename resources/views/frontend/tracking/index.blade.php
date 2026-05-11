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

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[10px] uppercase tracking-widest font-black">
                    <tr>
                        <th class="px-8 py-4">เลขที่คำร้อง</th>
                        <th class="px-8 py-4">ผู้ส่งคำร้อง</th>
                        <th class="px-8 py-4">สถานะ</th>
                        <th class="px-8 py-4">ผู้อนุมัติลำดับถัดไป</th>
                        <th class="px-8 py-4">วันที่ส่ง</th>
                        <th class="px-8 py-4">เครื่องมือ</th>
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
                            <td class="px-8 py-5">
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
    </div>
</div>
@endsection