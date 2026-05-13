@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-sky-50/30 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                            <i class="fa-solid fa-list-check text-xl"></i>
                        </span>
                        ติดตามสถานะคำร้อง
                    </h1>
                    <p class="text-slate-400 text-sm mt-2 font-medium">ตรวจสอบและติดตามความคืบหน้าของคำร้องขอสิทธิใช้งานสารสนเทศ</p>
                </div>
                
                <a href="{{ route('request.index') }}"
                    class="inline-flex items-center px-8 py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-blue-500 transition-all shadow-xl shadow-blue-200 hover:shadow-blue-300 transform hover:-translate-y-1 active:scale-95 group">
                    <i class="fa-solid fa-plus mr-3 transition-transform group-hover:rotate-90"></i>
                    สร้างคำร้องใหม่
                </a>
            </div>

            {{-- งานที่รอคุณดำเนินการ (Priority Actions) --}}
            @if($toApprove->count() > 0 || $toAcknowledge->count() > 0 || $toVerifyNDA->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center gap-4 mb-6">
                        <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
                            </span>
                            งานที่รอคุณดำเนินการ ({{ $toApprove->count() + $toAcknowledge->count() + $toVerifyNDA->count() }})
                        </h3>
                        <div class="flex-grow h-px bg-blue-100"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($toApprove as $req)
                            <a href="{{ route('tracking.show', $req->request_no) }}"
                                class="relative block p-6 bg-white border border-blue-100 rounded-[2rem] hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-500 group overflow-hidden">
                                <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                                
                                <div class="relative">
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-black rounded-lg uppercase border border-orange-100">Waiting Approval</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $req->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-lg font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ $req->request_no }}</div>
                                    <div class="text-xs text-slate-500 mt-1 font-medium">{{ $req->firstname }} {{ $req->lastname }}</div>
                                    
                                    <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                                        <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest">คลิกเพื่ออนุมัติ</span>
                                        <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center transition-transform group-hover:translate-x-1">
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        @foreach($toAcknowledge as $req)
                            <a href="{{ route('tracking.show', $req->request_no) }}"
                                class="relative block p-6 bg-white border border-blue-100 rounded-[2rem] hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-500 group overflow-hidden">
                                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                                
                                <div class="relative">
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg uppercase border border-blue-100 text-center">Waiting Acknowledge</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $req->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-lg font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ $req->request_no }}</div>
                                    <div class="text-xs text-slate-500 mt-1 font-medium">{{ $req->firstname }} {{ $req->lastname }}</div>
                                    
                                    <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                                        <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest">คลิกเพื่อรับทราบ</span>
                                        <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center transition-transform group-hover:translate-x-1">
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        @foreach($toVerifyNDA as $req)
                            <a href="{{ route('request.nda', $req->request_no) }}"
                                class="relative block p-6 bg-white border border-indigo-100 rounded-[2rem] hover:shadow-2xl hover:shadow-indigo-200/50 transition-all duration-500 group overflow-hidden">
                                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                                
                                <div class="relative">
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-lg uppercase border border-indigo-100 text-center">NDA Witness</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Pending NDA</span>
                                    </div>
                                    <div class="text-lg font-black text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $req->request_no }}</div>
                                    <div class="text-xs text-slate-500 mt-1 font-medium">{{ $req->firstname }} {{ $req->lastname }}</div>
                                    
                                    <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">คลิกเพื่อรับรองพยาน</span>
                                        <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center transition-transform group-hover:translate-x-1">
                                            <i class="fa-solid fa-file-contract text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Main List -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">รายการคำร้องทั้งหมด (History)</h3>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-blue-100 overflow-hidden shadow-2xl shadow-blue-100/50">
                    {{-- Desktop View --}}
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-blue-100 border-b border-blue-50">
                                    <th class="px-10 py-6 text-[10px] font-black text-black-100 uppercase tracking-widest">เลขที่คำร้อง</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-black-100 uppercase tracking-widest">ผู้ส่งคำร้อง</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-black-400 uppercase tracking-widest">สถานะ</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-black-400 uppercase tracking-widest">ขั้นตอนถัดไป</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-black-400 uppercase tracking-widest">วันที่ส่ง</th>
                                    <th class="px-10 py-6 text-right text-[10px] font-black text-black-400 uppercase tracking-widest">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-50">
                                @forelse($requests as $request)
                                    <tr class="hover:bg-blue-50/20 transition-all duration-300 group">
                                        <td class="px-10 py-6">
                                            <span class="text-sm font-black text-blue-600 group-hover:underline underline-offset-4 decoration-2">{{ $request->request_no }}</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center font-black text-xs uppercase tracking-tighter shadow-inner">
                                                    {{ substr($request->firstname, 0, 1) }}{{ substr($request->lastname, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-700">{{ $request->firstname }} {{ $request->lastname }}</div>
                                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $request->department_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            @if($request->status == 'pending')
                                                <span class="px-3 py-1 inline-flex text-[10px] font-black leading-5 rounded-lg bg-yellow-50 text-yellow-600 border border-yellow-100 uppercase">Pending</span>
                                            @elseif($request->status == 'completed')
                                                <span class="px-3 py-1 inline-flex text-[10px] font-black leading-5 rounded-lg bg-green-500 text-white shadow-lg shadow-green-100 uppercase">Completed</span>
                                            @elseif($request->status == 'approved' && $request->it_status == 'completed')
                                                <span class="px-3 py-1 inline-flex text-[10px] font-black leading-5 rounded-lg bg-blue-600 text-white shadow-lg shadow-blue-100 uppercase">Acknowledge</span>
                                            @elseif($request->status == 'approved')
                                                <span class="px-3 py-1 inline-flex text-[10px] font-black leading-5 rounded-lg bg-blue-100 text-blue-600 border border-blue-200 uppercase">Approved</span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-[10px] font-black leading-5 rounded-lg bg-red-50 text-red-600 border border-red-100 uppercase">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-6">
                                            @if($request->status == 'pending')
                                                @php
                                                    $currentStep = $request->steps->where('step_order', $request->current_step)->where('status', 'pending')->first();
                                                @endphp
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-orange-500">{{ $currentStep->step_name ?? '-' }}</span>
                                                    <span class="text-[9px] text-slate-300 font-bold uppercase tracking-widest mt-0.5">รออนุมัติ</span>
                                                </div>
                                            @elseif($request->status == 'approved' && $request->it_status == 'completed')
                                                <div class="flex items-center gap-2 text-orange-500">
                                                    <i class="fa-solid fa-user-check text-xs"></i>
                                                    <span class="text-xs font-bold">รอผู้ใช้งานยืนยัน</span>
                                                </div>
                                            @elseif($request->status == 'approved')
                                                <div class="flex items-center gap-2 text-blue-500">
                                                    <i class="fa-solid fa-gear fa-spin text-xs"></i>
                                                    <span class="text-xs font-bold">เจ้าหน้าที่ IT ดำเนินการ</span>
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-300 font-medium">-</span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="text-xs text-slate-500 font-bold uppercase tracking-tighter">{{ $request->created_at->format('d/m/Y') }}</span>
                                            <div class="text-[10px] text-slate-300 font-medium">{{ $request->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-10 py-6 text-right">
                                            <div class="flex flex-wrap justify-end gap-2 sm:gap-3">
                                                @php
                                                    $hasNda = \App\Models\ConfidentialityAgreement::where('request_form_id', $request->id)->exists();
                                                    $isRequester = Auth::id() == $request->user_id;
                                                @endphp
                                                
                                                @if($isRequester && $request->status == 'completed' && !$hasNda)
                                                    <a href="{{ route('request.nda', $request->request_no) }}"
                                                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-500 transition-all shadow-lg shadow-blue-100 whitespace-nowrap text-center min-w-[110px]">
                                                        บันทึก NDA
                                                    </a>
                                                @endif

                                                <a href="{{ route('tracking.show', $request->request_no) }}"
                                                    class="inline-flex items-center justify-center px-4 py-2 bg-sky-50 text-sky-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all border border-sky-100 shadow-sm whitespace-nowrap text-center min-w-[110px]">
                                                    ดูรายละเอียด
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-10 py-24 text-center">
                                            <div class="max-w-xs mx-auto">
                                                <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                                    <i class="fa-solid fa-folder-open text-3xl text-blue-200"></i>
                                                </div>
                                                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-sm">ไม่พบรายการคำร้อง</p>
                                                <p class="text-slate-300 text-xs mt-2 font-medium">คุณยังไม่มีการสร้างหรือเกี่ยวข้องกับคำร้องใดๆ ในขณะนี้</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile View --}}
                    <div class="lg:hidden divide-y divide-blue-50">
                        @forelse($requests as $request)
                            <div class="p-8 space-y-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-[10px] font-black text-blue-600 mb-2 uppercase tracking-[0.2em]">
                                            {{ $request->request_no }}</div>
                                        <div class="text-lg font-black text-slate-800 leading-tight">{{ $request->firstname }}<br>{{ $request->lastname }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-1">{{ $request->department_name }}</div>
                                    </div>
                                    <div>
                                        @if($request->status == 'pending')
                                            <span class="px-3 py-1 inline-flex text-[9px] font-black rounded-lg bg-yellow-50 text-yellow-600 border border-yellow-100 uppercase">Pending</span>
                                        @elseif($request->status == 'completed')
                                            <span class="px-3 py-1 inline-flex text-[9px] font-black rounded-lg bg-green-500 text-white shadow-lg shadow-green-100 uppercase">Completed</span>
                                        @elseif($request->status == 'approved' && $request->it_status == 'completed')
                                            <span class="px-3 py-1 inline-flex text-[9px] font-black rounded-lg bg-blue-600 text-white shadow-lg shadow-blue-100 uppercase">Acknowledge</span>
                                        @elseif($request->status == 'approved')
                                            <span class="px-3 py-1 inline-flex text-[9px] font-black rounded-lg bg-blue-50 text-blue-600 border border-blue-100 uppercase">Approved</span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-[9px] font-black rounded-lg bg-red-50 text-red-600 border border-red-100 uppercase">Rejected</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-blue-50/50 rounded-3xl p-5 space-y-4 border border-blue-100/50 shadow-inner">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-black text-blue-300 uppercase tracking-widest">สถานะปัจจุบัน</span>
                                        @if($request->status == 'pending')
                                            @php
                                                $currentStep = $request->steps->where('step_order', $request->current_step)->where('status', 'pending')->first();
                                            @endphp
                                            <span class="text-xs font-bold text-orange-600">{{ $currentStep->step_name ?? '-' }}</span>
                                        @elseif($request->status == 'approved' && $request->it_status == 'completed')
                                            <span class="text-xs font-bold text-orange-600">รอผู้ใช้งานยืนยัน</span>
                                        @elseif($request->status == 'approved')
                                            <span class="text-xs font-bold text-blue-600 flex items-center gap-2">
                                                <i class="fa-solid fa-gear fa-spin"></i>
                                                IT ดำเนินการ
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 font-bold uppercase">-</span>
                                        @endif
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-black text-blue-300 uppercase tracking-widest">วันที่ส่ง</span>
                                        <span class="text-xs text-slate-600 font-bold">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    @if($isRequester && $request->status == 'completed' && !$hasNda)
                                        <a href="{{ route('request.nda', $request->request_no) }}"
                                            class="block w-full text-center py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-100 active:scale-[0.98] transition-all">
                                            บันทึก NDA
                                        </a>
                                    @endif
                                    <a href="{{ route('tracking.show', $request->request_no) }}"
                                        class="block w-full text-center py-4 bg-white border border-blue-100 rounded-2xl text-xs font-black uppercase tracking-widest text-blue-600 shadow-xl shadow-blue-100 hover:bg-blue-600 hover:text-white transition-all active:scale-[0.98]">
                                        ดูรายละเอียดข้อมูล
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <p class="text-slate-300 font-black uppercase tracking-widest text-xs">ไม่พบรายการคำร้อง</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection