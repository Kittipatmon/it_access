@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="mb-4">
            <a href="{{ route('tracking.index') }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-700 font-bold text-sm transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                กลับไปที่รายการคำร้อง
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200">
            <!-- Header: Exact match to index.blade.php -->
            <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">แบบฟอร์มการร้องขอสิทธิใช้งานเทคโนโลยีสารสนเทศ</h2>
                        <p class="text-sm text-slate-500 mt-1">QF-IT-08: Rev: 02</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Request No: <span
                                class="text-blue-600 font-bold">{{ $request->request_no }}</span></p>
                        <p class="text-[10px] text-slate-300 mt-1">วันที่สร้าง:
                            {{ $request->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-10">
                {{-- Status Banner --}}
                <div
                    class="flex items-center justify-between p-4 rounded-2xl {{ $request->status == 'approved' ? 'bg-green-50 border border-green-100' : ($request->status == 'rejected' ? 'bg-red-50 border border-red-100' : 'bg-yellow-50 border border-yellow-100') }}">
                    <div class="flex items-center gap-3">
                        <div
                            class="h-10 w-10 rounded-full flex items-center justify-center {{ $request->status == 'approved' ? 'bg-green-500' : ($request->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                            @if($request->status == 'approved')
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif($request->status == 'rejected')
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">สถานะปัจจุบัน</p>
                            <p
                                class="text-lg font-black {{ $request->status == 'approved' ? 'text-green-700' : ($request->status == 'rejected' ? 'text-red-700' : 'text-yellow-700') }}">
                                {{ $request->status == 'pending' ? 'รออนุมัติ (Pending)' : ($request->status == 'approved' ? 'อนุมัติแล้ว (Approved)' : 'ถูกปฏิเสธ (Rejected)') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ส่วนที่ 1: ผู้ร้องขอ --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-l-4 border-blue-500 pl-3">
                        ส่วนที่ 1 ผู้ร้องขอ</h3>

                    <!-- ประเภทคำร้อง -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">ประเภทคำร้อง</label>
                        <div
                            class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-xl border border-blue-200 font-bold text-sm">
                            @php
                                $types = [
                                    'new_employee' => 'พนักงานใหม่',
                                    'resign' => 'ลาออก',
                                    'position_change' => 'ปรับตำแหน่ง',
                                    'transfer' => 'โอนย้าย',
                                    'add_remove_access' => 'เพิ่มสิทธิ์/ลบสิทธิ์',
                                ];
                            @endphp
                            {{ $types[$request->request_type] ?? $request->request_type }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ชื่อ (ภาษาไทย)</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->firstname }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">นามสกุล (ภาษาไทย)</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->lastname }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ชื่อเล่น</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->nickname_th ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Name (English)</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->firstname_en ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Last Name (English)</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->lastname_en ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nick Name (English)</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->nickname_en ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">โทรศัพท์</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->phone ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ภายใน (Ext.)</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->phone_ext ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">รหัสพนักงาน</label>
                            <p
                                class="block w-full bg-blue-50 border border-blue-100 rounded-xl px-4 py-2.5 text-sm text-blue-700 font-bold">
                                {{ $request->emp_code }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ระดับ</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->position_level }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ตำแหน่ง</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->position_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">แผนก/ฝ่าย</label>
                            <p
                                class="block w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium">
                                {{ $request->department_name }}
                                {{ $request->division_name ? '/ ' . $request->division_name : '' }}</p>
                        </div>
                    </div>
                </div>

                {{-- ส่วนที่ 2: การเข้าถึง --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-l-4 border-blue-500 pl-3">
                        ส่วนที่ 2 การเข้าถึง</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- การเข้าถึงระบบ -->
                        <div class="bg-slate-50/50 rounded-2xl border border-slate-200 p-6 space-y-4">
                            <h4
                                class="text-xs font-black text-slate-400 uppercase flex items-center gap-2 pb-2 border-b border-slate-100">
                                <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                การเข้าถึงระบบ
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @php $systems = $request->system_access ?? []; @endphp
                                @foreach($systems as $key => $val)
                                    @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                        <div class="bg-white border border-slate-200 rounded-xl px-4 py-2 shadow-sm">
                                            <span
                                                class="text-sm font-bold text-slate-700">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                            @if(isset($systems[$key . '_sub']))
                                                <span
                                                    class="ml-1 text-[10px] font-medium text-slate-400 block border-t border-slate-100 mt-1 pt-1">{{ is_array($systems[$key . '_sub']) ? implode(', ', $systems[$key . '_sub']) : $systems[$key . '_sub'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- การเข้าถึงโปรแกรม -->
                        <div class="bg-slate-50/50 rounded-2xl border border-slate-200 p-6 space-y-4">
                            <h4
                                class="text-xs font-black text-slate-400 uppercase flex items-center gap-2 pb-2 border-b border-slate-100">
                                <svg class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                การเข้าถึงโปรแกรม
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @php $programs = $request->program_access ?? []; @endphp
                                @foreach($programs as $key => $val)
                                    @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                        <div class="bg-white border border-slate-200 rounded-xl px-4 py-2 shadow-sm">
                                            <span
                                                class="text-sm font-bold text-slate-700">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                            @if(isset($programs[$key . '_sub']))
                                                <span
                                                    class="ml-1 text-[10px] font-medium text-slate-400 block border-t border-slate-100 mt-1 pt-1">{{ is_array($programs[$key . '_sub']) ? implode(', ', $programs[$key . '_sub']) : $programs[$key . '_sub'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- รายละเอียดเพิ่มเติม --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-l-4 border-blue-500 pl-3">
                        รายละเอียดเพิ่มเติม</h3>
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 text-sm text-slate-600 italic">
                        {{ $request->details ?: 'ไม่มีรายละเอียดเพิ่มเติม' }}
                    </div>
                </div>

                {{-- ลายมือชื่อและการอนุมัติ --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-100">
                    <!-- ลายเซ็น -->
                    <div class="flex flex-col items-center p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <label
                            class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">ลายมือชื่อผู้ร้องขอ</label>
                        @if($request->signature_path)
                            <img src="{{ asset('storage/' . $request->signature_path) }}" alt="Signature"
                                class="h-20 w-auto mb-2">
                        @else
                            <div class="h-20 flex items-center text-slate-300 italic text-xs">ไม่ได้ระบุลายเซ็น</div>
                        @endif
                        <div class="w-32 h-px bg-slate-200 mb-2"></div>
                        <p class="text-xs font-bold text-slate-700">{{ $request->firstname }} {{ $request->lastname }}</p>
                    </div>

                    <!-- การอนุมัติ -->
                    <div class="space-y-3">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ขั้นตอนการอนุมัติ</label>
                        @foreach($request->steps as $step)
                            <div
                                class="flex items-center justify-between p-3 rounded-xl border {{ $step->status == 'approved' ? 'bg-green-50 border-green-100' : ($step->status == 'rejected' ? 'bg-red-50 border-red-100' : 'bg-white border-slate-100') }}">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-6 w-6 rounded-full flex items-center justify-center text-[10px] font-bold {{ $step->status == 'approved' ? 'bg-green-500 text-white' : ($step->status == 'rejected' ? 'bg-red-500 text-white' : 'bg-slate-200 text-slate-500') }}">
                                        {{ $step->step_order }}
                                    </div>
                                    <p class="text-[11px] font-bold text-slate-700">{{ $step->step_name }}</p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-[9px] font-bold uppercase {{ $step->status == 'approved' ? 'text-green-600' : ($step->status == 'rejected' ? 'text-red-600' : 'text-slate-400') }}">
                                        {{ $step->status == 'pending' ? 'รอคิว' : ($step->status == 'approved' ? 'อนุมัติแล้ว' : 'ปฏิเสธ') }}
                                    </span>
                                    @if($step->status == 'approved' && $step->approved_at)
                                        <p class="text-[8px] text-slate-400 mt-0.5">{{ $step->approved_at->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ส่วนสำหรับการอนุมัติ (เฉพาะผู้อนุมัติลำดับปัจจุบัน หรือ Admin) --}}
                @php
                    $currentStep = $request->steps->where('step_order', $request->current_step)->where('status', 'pending')->first();
                    $isAdmin = Auth::user()->role === 'admin';
                    $isMyTurn = $currentStep && ($currentStep->approver_id == Auth::id() || $isAdmin);
                @endphp

                @if($isMyTurn)
                    <div class="mt-8 pt-8 border-t-2 border-dashed border-slate-100 no-print animate-fade-in" x-data="{ showForm: false, actionType: '', method: 'draw', previewUrl: null, useExisting: {{ Auth::user()->signature ? 'true' : 'false' }} }">
                        <div x-show="!showForm" class="bg-blue-600 rounded-3xl p-8 text-center shadow-xl shadow-blue-200">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $isAdmin && $currentStep->approver_id != Auth::id() ? 'คุณกำลังดำเนินการในฐานะ Administrator' : 'คุณคือผู้อนุมัติในลำดับนี้' }}</h3>
                            <p class="text-blue-100 text-sm mb-6">กรุณาตรวจสอบข้อมูลและดำเนินการอนุมัติหรือปฏิเสธคำร้อง</p>
                            <div class="flex items-center justify-center gap-4">
                                <button @click="showForm = true; actionType = 'approve'" class="px-8 py-3 bg-white text-blue-600 rounded-2xl font-bold hover:bg-blue-50 transition shadow-sm">
                                    ดำเนินการอนุมัติ
                                </button>
                                <button @click="showForm = true; actionType = 'reject'" class="px-8 py-3 bg-red-500 text-white rounded-2xl font-bold hover:bg-red-600 transition shadow-sm">
                                    ปฏิเสธคำร้อง
                                </button>
                            </div>
                        </div>

                        <div x-show="showForm" x-transition class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-xl font-bold text-slate-800" x-text="actionType === 'approve' ? 'ยืนยันการอนุมัติ' : 'ยืนยันการปฏิเสธ'"></h3>
                                <button @click="showForm = false" class="text-slate-400 hover:text-slate-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <form :action="actionType === 'approve' ? '{{ route('backend.approvals.approve', $currentStep->id) }}' : '{{ route('backend.approvals.reject', $currentStep->id) }}'" method="POST" enctype="multipart/form-data" id="approval-form">
                                @csrf
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">ความคิดเห็น / หมายเหตุ</label>
                                        <textarea name="remark" rows="3" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition" :required="actionType === 'reject'" placeholder="ระบุเหตุผลหรือข้อเสนอแนะ..."></textarea>
                                    </div>

                                    <div x-show="actionType === 'approve'" class="space-y-6 animate-fade-in">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-sm font-bold text-slate-700">ลายมือชื่อผู้อนุมัติ</label>
                                            @if(Auth::user()->signature)
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="use_existing" value="1" x-model="useExisting" class="rounded text-blue-600 mr-2">
                                                <span class="text-xs text-slate-500">ใช้ลายเซ็นที่บันทึกไว้</span>
                                            </label>
                                            @endif
                                        </div>

                                        <div x-show="useExisting && {{ Auth::user()->signature ? 'true' : 'false' }}" class="p-6 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center">
                                            <img src="{{ asset('storage/signatures/' . Auth::user()->signature) }}" class="h-20 w-auto opacity-80" alt="My Signature">
                                            <p class="text-[10px] text-slate-400 mt-2 italic">ใช้ลายเซ็นในระบบของคุณ</p>
                                        </div>

                                        <div x-show="!useExisting" class="space-y-4">
                                            <div class="flex p-1 bg-slate-100 rounded-xl w-fit">
                                                <button type="button" @click="method = 'draw'" :class="method === 'draw' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500'" class="px-4 py-1.5 rounded-lg text-xs font-bold transition">วาดใหม่</button>
                                                <button type="button" @click="method = 'upload'" :class="method === 'upload' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500'" class="px-4 py-1.5 rounded-lg text-xs font-bold transition">อัปโหลดรูป</button>
                                            </div>

                                            <div x-show="method === 'draw'" class="relative bg-slate-50 border-2 border-slate-100 rounded-3xl overflow-hidden">
                                                <canvas id="approval-signature-pad" class="w-full h-48 touch-none"></canvas>
                                                <button type="button" id="clear-approval-signature" class="absolute top-4 right-4 p-2 bg-white shadow-md border border-slate-100 rounded-xl text-slate-400 hover:text-red-500 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                <input type="hidden" name="signature" id="approval-signature-input">
                                            </div>

                                            <div x-show="method === 'upload'">
                                                <input type="file" name="signature_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full py-4 rounded-2xl font-bold transition-all duration-300 text-white shadow-lg" :class="actionType === 'approve' ? 'bg-blue-600 hover:bg-blue-700 shadow-blue-200' : 'bg-red-500 hover:bg-red-600 shadow-red-200'">
                                        <span x-text="actionType === 'approve' ? 'ยืนยันการอนุมัติคำร้อง' : 'ยืนยันการปฏิเสธคำร้อง'"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @yield('scripts')
            </main>
        </div>
    </div>

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const canvas = document.getElementById('approval-signature-pad');
                if (!canvas) return;

                const signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(0, 0, 0, 0)',
                    penColor: 'rgb(30, 41, 59)'
                });

                function resizeCanvas() {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                    signaturePad.clear();
                }

                window.addEventListener("resize", resizeCanvas);
                resizeCanvas();

                document.getElementById('clear-approval-signature').addEventListener('click', () => {
                    signaturePad.clear();
                });

                const form = document.getElementById('approval-form');
                form.addEventListener('submit', (e) => {
                    const alpineData = document.querySelector('[x-data]').__x.$data;
                    if (alpineData.actionType === 'approve' && !alpineData.useExisting && alpineData.method === 'draw') {
                        if (signaturePad.isEmpty()) {
                            e.preventDefault();
                            alert('กรุณาเซ็นชื่อก่อนดำเนินการอนุมัติ');
                            return;
                        }
                        document.getElementById('approval-signature-input').value = signaturePad.toDataURL();
                    }
                });
            });
        </script>
    @endsection

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .max-w-5xl {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .shadow-sm {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
            }

            .rounded-2xl {
                border-radius: 0 !important;
            }

            .bg-white {
                padding: 0 !important;
            }
        }
    </style>
@endsection