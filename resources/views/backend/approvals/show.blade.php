@extends('layouts.admin')

@section('breadcrumb', 'รายละเอียดคำร้องขอสิทธิ')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('backend.approvals.index') }}" class="text-xs font-bold text-blue-600 uppercase tracking-widest hover:underline flex items-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                กลับหน้าหลัก
            </a>
            <h2 class="text-2xl font-bold text-slate-800">รายละเอียดคำร้อง: {{ $request->request_no }}</h2>
        </div>
        <div>
            @if($request->status == 'pending')
                <span class="px-4 py-1.5 rounded-full bg-yellow-100 text-yellow-600 text-xs font-bold uppercase border border-yellow-200">รอดำเนินการ</span>
            @elseif($request->status == 'approved')
                <span class="px-4 py-1.5 rounded-full bg-green-100 text-green-600 text-xs font-bold uppercase border border-green-200">สำเร็จแล้ว</span>
            @else
                <span class="px-4 py-1.5 rounded-full bg-red-100 text-red-600 text-xs font-bold uppercase border border-red-200">ปฏิเสธ</span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <!-- User & Basic Info -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">ข้อมูลผู้ร้องขอ</h3>
                </div>
                <div class="p-8 grid grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">ชื่อ-นามสกุล</label>
                        <p class="text-sm font-medium text-slate-700">{{ $request->firstname }} {{ $request->lastname }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">รหัสพนักงาน</label>
                        <p class="text-sm font-medium text-slate-700">{{ $request->emp_code }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">แผนก / ฝ่าย</label>
                        <p class="text-sm font-medium text-slate-700">{{ $request->department_name }} / {{ $request->division_name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">ประเภทคำร้อง</label>
                        <p class="text-sm font-medium text-slate-700">
                            {{ [
                                'new_employee' => 'พนักงานใหม่',
                                'resign' => 'ลาออก',
                                'position_change' => 'ปรับตำแหน่ง',
                                'transfer' => 'โอนย้าย',
                                'add_remove_access' => 'เพิ่มสิทธิ์/ลบสิทธิ์',
                            ][$request->request_type] ?? $request->request_type }}
                        </p>
                    </div>
                    @if($request->signature_path)
                    <div class="col-span-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">ลายมือชื่อผู้ร้องขอ</label>
                        <div class="mt-2 p-3 bg-slate-50 rounded-2xl border border-slate-100 inline-block">
                            <img src="{{ asset('storage/' . $request->signature_path) }}" alt="Requester Signature" class="h-16 w-auto opacity-80">
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Access Details -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">สิทธิ์การเข้าถึงที่ร้องขอ</h3>
                </div>
                <div class="p-8 space-y-6">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">ระบบและโปรแกรม</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $systemAccess = is_array($request->system_access) ? $request->system_access : json_decode($request->system_access, true) ?? [];
                                $programAccess = is_array($request->program_access) ? $request->program_access : json_decode($request->program_access, true) ?? [];
                            @endphp

                            @foreach($systemAccess as $key => $val)
                                @if($val == "1" && $key !== 'other_text')
                                    <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-xl text-[11px] font-bold border border-blue-100 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                @endif
                            @endforeach
                            @foreach($programAccess as $key => $val)
                                @if($val == "1" && $key !== 'other_text')
                                    <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-xl text-[11px] font-bold border border-blue-100 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                @endif
                            @endforeach
                            
                            @if(isset($systemAccess['other_text']) && $systemAccess['other_text'])
                                <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-[11px] font-bold border border-slate-200 italic">อื่นๆ: {{ $systemAccess['other_text'] }}</span>
                            @endif
                        </div>
                    </div>

                    @if($request->details)
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">รายละเอียดเพิ่มเติม</label>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-sm text-slate-600 italic">
                            "{{ $request->details }}"
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- History -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">ประวัติการดำเนินการ</h3>
                </div>
                <div class="p-8">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($request->histories as $index => $history)
                            <li>
                                <div class="relative pb-8">
                                    @if($index !== count($request->histories) - 1)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-100" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $history->action_type == 'approved' ? 'bg-green-500' : ($history->action_type == 'rejected' ? 'bg-red-500' : 'bg-blue-500') }}">
                                                @if($history->action_type == 'approved')
                                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                @elseif($history->action_type == 'rejected')
                                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                                                @else
                                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-slate-700">
                                                    <span class="font-bold">{{ $history->action_type == 'created' ? 'สร้างคำร้อง' : ($history->action_type == 'approved' ? 'อนุมัติ' : 'ปฏิเสธ') }}</span> 
                                                    โดย <span class="text-blue-600 font-bold">{{ $history->user->name }}</span>
                                                </p>
                                                @if($history->remark)
                                                    <p class="text-xs text-slate-400 mt-1 italic">"{{ $history->remark }}"</p>
                                                @endif
                                                @if($history->signature_path)
                                                    <div class="mt-2 p-2 bg-slate-50 rounded-xl border border-slate-100 inline-block">
                                                        <img src="{{ asset('storage/' . $history->signature_path) }}" alt="Signature" class="h-12 w-auto opacity-80">
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-right text-[10px] font-bold text-slate-400 uppercase">
                                                {{ $history->created_at->format('d M H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Approval Steps -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden sticky top-6">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">ขั้นตอนการอนุมัติ</h3>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($request->steps as $step)
                    <div class="group relative pl-10">
                        <!-- Step Line -->
                        @if(!$loop->last)
                        <div class="absolute left-[11px] top-6 bottom-0 w-0.5 bg-slate-100"></div>
                        @endif
                        
                        <!-- Step Icon -->
                        <div class="absolute left-0 top-0 w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold z-10 
                            {{ $step->status == 'approved' ? 'bg-green-500 text-white shadow-lg shadow-green-100' : ($step->status == 'rejected' ? 'bg-red-500 text-white shadow-lg shadow-red-100' : ($request->current_step == $step->step_order ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 'bg-slate-100 text-slate-400')) }}">
                            {{ $step->step_order }}
                        </div>

                        <!-- Step Content -->
                        <div class="pb-6">
                            <p class="text-xs font-bold text-slate-800">{{ $step->step_name }}</p>
                            <p class="text-[10px] text-slate-400 mb-1">{{ $step->approver->name }}</p>
                            @if($step->status == 'approved')
                                <span class="text-[9px] font-bold text-green-500 uppercase tracking-wider">สำเร็จแล้ว</span>
                            @elseif($step->status == 'rejected')
                                <span class="text-[9px] font-bold text-red-500 uppercase tracking-wider">ถูกปฏิเสธ</span>
                            @elseif($request->current_step == $step->step_order)
                                <span class="text-[9px] font-bold text-blue-600 uppercase tracking-wider flex items-center">
                                    <span class="flex h-1.5 w-1.5 rounded-full bg-blue-600 mr-1.5 animate-pulse"></span>
                                    รอดำเนินการ
                                </span>
                            @else
                                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-wider">รอคิว</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Action for current approver -->
                @php
                    $currentStep = $request->steps->where('step_order', $request->current_step)->where('status', 'pending')->first();
                @endphp
                @if($currentStep && $currentStep->approver_id == Auth::id())
                <div class="p-6 bg-blue-50/50 border-t border-blue-100">
                    <h4 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-4">ดำเนินการพิจารณา</h4>
                    <div class="flex space-x-2">
                        <button onclick="openModal('approve', {{ $currentStep->id }})" class="flex-1 py-2.5 bg-green-500 hover:bg-green-600 text-white text-xs font-bold rounded-xl transition shadow-md shadow-green-100">อนุมัติ</button>
                        <button onclick="openModal('reject', {{ $currentStep->id }})" class="flex-1 py-2.5 bg-white border border-slate-200 text-red-500 text-xs font-bold rounded-xl hover:bg-red-50 transition">ปฏิเสธ</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approve/Reject Modal (Reused from index but simplified) -->
<div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
    <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl">
        <h3 id="modal-title" class="text-xl font-bold text-slate-800">ดำเนินการ</h3>
        <form id="modal-form" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">หมายเหตุการพิจารณา</label>
                <textarea name="remark" id="remark-field" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="ระบุเหตุผล..."></textarea>
            </div>
            
            <div id="signature-container" class="hidden">
                <div class="flex flex-col mb-4 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">เลือกวิธีลงลายเซ็น</label>
                    <div class="inline-flex p-1 bg-slate-100 rounded-2xl border border-slate-200">
                        @if(Auth::user()->signature)
                        <button type="button" onclick="switchMethod('existing')" id="method-existing" 
                            class="flex-1 px-3 py-2 rounded-xl text-[10px] font-bold transition-all duration-200 flex items-center justify-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>ใช้ของเดิม</span>
                        </button>
                        @endif
                        <button type="button" onclick="switchMethod('draw')" id="method-draw" 
                            class="flex-1 px-3 py-2 rounded-xl text-[10px] font-bold transition-all duration-200 flex items-center justify-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            <span>วาดใหม่</span>
                        </button>
                        <button type="button" onclick="switchMethod('upload')" id="method-upload" 
                            class="flex-1 px-3 py-2 rounded-xl text-[10px] font-bold transition-all duration-200 flex items-center justify-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span>อัปโหลด</span>
                        </button>
                    </div>
                </div>

                <!-- Draw New -->
                <div id="draw-new-sig-container">
                    <div class="relative bg-slate-50 border border-slate-100 rounded-2xl overflow-hidden">
                        <canvas id="signature-pad" class="w-full h-40 touch-none"></canvas>
                        <button type="button" id="clear-signature" class="absolute top-2 right-2 p-1.5 bg-white shadow-sm border border-slate-200 rounded-lg text-slate-400 hover:text-red-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 italic text-center">กรุณาวาดลายเซ็นภายในช่องสี่เหลี่ยมด้านบน</p>
                </div>

                @if(Auth::user()->signature)
                <!-- Use Existing -->
                <div id="use-existing-sig-container" class="hidden">
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col items-center">
                        <div class="bg-white p-2 rounded-lg border border-slate-50 inline-block shadow-sm">
                            <img src="{{ asset('storage/signatures/' . Auth::user()->signature) }}" alt="Existing Signature" class="h-16 w-auto">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 italic">ใช้ลายเซ็นที่บันทึกไว้ในระบบ</p>
                    </div>
                </div>
                @endif

                <!-- Upload Image -->
                <div id="upload-sig-container" class="hidden">
                    <div class="relative">
                        <input type="file" name="signature_file" id="modal_signature_file" accept="image/*" class="hidden" onchange="previewSignature(this)">
                        <label for="modal_signature_file" id="modal-upload-label" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 hover:bg-slate-100 transition-all cursor-pointer">
                            <div class="flex flex-col items-center justify-center p-4 text-center">
                                <svg class="w-6 h-6 mb-2 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="text-[10px] text-slate-500"><span class="font-bold text-blue-600">อัปโหลดรูป</span> หรือลากมาวาง</p>
                            </div>
                        </label>
                        <div id="modal-preview-container" class="hidden absolute inset-0 bg-slate-50 rounded-2xl flex flex-col items-center justify-center p-4">
                            <button type="button" onclick="clearPreview()" class="absolute -top-2 -right-2 p-1.5 bg-red-500 text-white rounded-full shadow-lg hover:bg-red-600 transition z-20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <img id="modal-sig-preview" class="h-24 w-auto object-contain mb-2 rounded-lg">
                            <button type="button" onclick="document.getElementById('modal_signature_file').click()" class="text-[9px] font-bold text-blue-500 underline">เปลี่ยนรูป</button>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="signature" id="signature-input">
                <input type="hidden" name="use_existing" id="use-existing-input" value="0">
                <input type="hidden" name="signature_method" id="signature-method-input" value="draw">
            </div>
            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl transition font-bold text-xs uppercase tracking-widest">ยกเลิก</button>
                <button type="submit" id="submit-btn" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold transition text-xs uppercase tracking-widest">ยืนยัน</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    let signaturePad;
    const canvas = document.getElementById('signature-pad');
    
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        if (signaturePad) signaturePad.clear();
    }

    window.addEventListener("resize", resizeCanvas);

    function openModal(type, stepId) {
        const modal = document.getElementById('modal');
        const form = document.getElementById('modal-form');
        const title = document.getElementById('modal-title');
        const btn = document.getElementById('submit-btn');
        const remark = document.getElementById('remark-field');
        const sigContainer = document.getElementById('signature-container');

        if (type === 'approve') {
            title.innerText = 'ยืนยันการอนุมัติคำร้อง';
            form.action = `/backend/approvals/${stepId}/approve`;
            btn.className = 'flex-1 px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-2xl font-bold transition text-xs uppercase tracking-widest';
            remark.required = false;
            sigContainer.classList.remove('hidden');
            
            // Initialize Signature Pad
            setTimeout(() => {
                resizeCanvas();
                if (!signaturePad) {
                    signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        penColor: 'rgb(30, 41, 59)'
                    });
                } else {
                    signaturePad.clear();
                }
            }, 100);
        } else {
            title.innerText = 'ยืนยันการปฏิเสธคำร้อง';
            form.action = `/backend/approvals/${stepId}/reject`;
            btn.className = 'flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-bold transition text-xs uppercase tracking-widest';
            remark.required = true;
            sigContainer.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('clear-signature').addEventListener('click', () => {
        if (signaturePad) signaturePad.clear();
    });

    document.getElementById('modal-form').addEventListener('submit', function(e) {
        const sigContainer = document.getElementById('signature-container');
        const method = document.getElementById('signature-method-input').value;

        if (!sigContainer.classList.contains('hidden')) {
            if (method === 'draw') {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    alert('กรุณาลงลายเซ็นก่อนกดยืนยัน');
                    return;
                }
                document.getElementById('signature-input').value = signaturePad.toDataURL();
            } else if (method === 'upload') {
                const fileInput = document.getElementById('modal_signature_file');
                if (fileInput.files.length === 0) {
                    e.preventDefault();
                    alert('กรุณาเลือกไฟล์รูปภาพลายเซ็น');
                    return;
                }
            }
        }
    });

    function switchMethod(method) {
        const drawContainer = document.getElementById('draw-new-sig-container');
        const existContainer = document.getElementById('use-existing-sig-container');
        const uploadContainer = document.getElementById('upload-sig-container');
        const useExistingInput = document.getElementById('use-existing-input');
        const methodInput = document.getElementById('signature-method-input');
        
        // Reset buttons
        ['existing', 'draw', 'upload'].forEach(m => {
            const btn = document.getElementById(`method-${m}`);
            if (btn) {
                if (m === method) {
                    btn.classList.remove('text-slate-500', 'hover:text-slate-700');
                    btn.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
                } else {
                    btn.classList.add('text-slate-500', 'hover:text-slate-700');
                    btn.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
                }
            }
        });

        methodInput.value = method;
        
        drawContainer.classList.add('hidden');
        if (existContainer) existContainer.classList.add('hidden');
        uploadContainer.classList.add('hidden');

        if (method === 'draw') {
            drawContainer.classList.remove('hidden');
            useExistingInput.value = '0';
            setTimeout(() => resizeCanvas(), 100);
        } else if (method === 'existing') {
            existContainer.classList.remove('hidden');
            useExistingInput.value = '1';
        } else if (method === 'upload') {
            uploadContainer.classList.remove('hidden');
            useExistingInput.value = '0';
        }
    }

    function previewSignature(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('modal-sig-preview').src = e.target.result;
                document.getElementById('modal-preview-container').classList.remove('hidden');
                document.getElementById('modal-upload-label').classList.add('opacity-0');
            }
            reader.readAsDataURL(file);
        }
    }

    function clearPreview() {
        document.getElementById('modal_signature_file').value = '';
        document.getElementById('modal-preview-container').classList.add('hidden');
        document.getElementById('modal-upload-label').classList.remove('opacity-0');
    }

    // Set default method
    @if(Auth::user()->signature)
        document.addEventListener('DOMContentLoaded', () => switchMethod('existing'));
    @endif
</script>
@endsection
