@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12" x-data="ndaForm()">
    <!-- Document Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <a href="{{ route('tracking.index') }}" class="hover:text-blue-600 transition">ติดตามสถานะ</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 mx-2"></i>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">ข้อตกลงรักษาความลับ</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <span class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-slate-200">
                    <i class="fa-solid fa-file-contract text-xl"></i>
                </span>
                ข้อตกลงรักษาความลับ (NDA)
            </h1>
        </div>
        <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="text-right border-r border-slate-100 pr-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">เลขที่คำร้อง</p>
                <p class="text-sm font-black text-blue-600 mt-1 leading-none">{{ $requestForm->request_no }}</p>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">วันที่สร้าง</p>
                <p class="text-sm font-bold text-slate-700 mt-1 leading-none">{{ now()->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Main Document -->
    <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <form action="{{ route('request.nda.store', $requestForm->request_no) }}" method="POST" id="nda-form">
            @csrf
            
            <div class="p-8 md:p-12 space-y-12">
                <!-- Agreement Preamble -->
                <div class="relative">
                    <div class="absolute -left-6 top-0 bottom-0 w-1 bg-blue-600 rounded-full opacity-20"></div>
                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-base italic">
                        <p>
                            บันทึกข้อตกลงฉบับนี้ทำขึ้น เมื่อวันที่ {{ now()->day }} เดือน {{ $months[now()->format('m')] ?? now()->format('F') }} {{ now()->year + 543 }} 
                            ณ บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน) โดยระบุรายละเอียดและเงื่อนไขการรักษาความลับข้อมูลทางธุรกิจและเทคโนโลยีสารสนเทศ ดังต่อไปนี้
                        </p>
                    </div>
                </div>

                <!-- Section 1: Parties -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="flex-none w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-black text-sm">1</span>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">ข้อมูลคู่สัญญา (Party Information)</h2>
                        <div class="flex-grow h-px bg-slate-100"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-building text-blue-500"></i>
                                ฝ่ายบริษัท (The Company)
                            </h3>
                            <p class="text-sm font-bold text-slate-800">บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน)</p>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">เลขที่ 358 ถนน เลี่ยงเมืองนนทบุรี ตำบลบางกระสอ อำเภอเมืองนนทบุรี จังหวัดนนทบุรี</p>
                        </div>

                        <div class="p-6 bg-blue-50/30 rounded-3xl border border-blue-100/50">
                            <h3 class="text-xs font-black text-blue-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-user-tie text-blue-500"></i>
                                ฝ่ายพนักงาน (The Employee)
                            </h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ชื่อ-นามสกุล</label>
                                    <input type="text" name="full_name" value="{{ old('full_name', $existing->full_name ?? ($requestForm->firstname . ' ' . $requestForm->lastname)) }}"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" required {{ $existing ? 'readonly' : '' }}>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">อายุ (ปี)</label>
                                        <input type="number" name="age" value="{{ old('age', $existing->age ?? '') }}"
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" required {{ $existing ? 'readonly' : '' }}>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">เบอร์ติดต่อ</label>
                                        <input type="text" name="contact_no" value="{{ old('contact_no', $existing->contact_no ?? $requestForm->tel) }}"
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" required {{ $existing ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">เลขประจำตัวประชาชน</label>
                                <input type="text" name="id_card_no" value="{{ old('id_card_no', $existing->id_card_no ?? '') }}"
                                    placeholder="x-xxxx-xxxxx-xx-x"
                                    class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 transition shadow-sm" required {{ $existing ? 'readonly' : '' }}>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">บ้านเลขที่</label>
                                    <input type="text" name="address_no" value="{{ old('address_no', $existing->address_no ?? '') }}"
                                        class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 transition shadow-sm" required {{ $existing ? 'readonly' : '' }}>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">ซอย</label>
                                    <input type="text" name="soi" value="{{ old('soi', $existing->soi ?? '') }}"
                                        class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 transition shadow-sm" {{ $existing ? 'readonly' : '' }}>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">ถนน</label>
                                    <input type="text" name="road" value="{{ old('road', $existing->road ?? '') }}"
                                        class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 transition shadow-sm" {{ $existing ? 'readonly' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">ตำบล/แขวง</label>
                                <input type="text" name="tambon" value="{{ old('tambon', $existing->tambon ?? '') }}"
                                    class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 transition shadow-sm" required {{ $existing ? 'readonly' : '' }}>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">อำเภอ/เขต</label>
                                <input type="text" name="amphoe" value="{{ old('amphoe', $existing->amphoe ?? '') }}"
                                    class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 transition shadow-sm" required {{ $existing ? 'readonly' : '' }}>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">จังหวัด</label>
                                <input type="text" name="province" value="{{ old('province', $existing->province ?? '') }}"
                                    class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 transition shadow-sm" required {{ $existing ? 'readonly' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Terms & Conditions -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <span class="flex-none w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-black text-sm">2</span>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">เงื่อนไขการรักษาความลับ (Terms of Confidentiality)</h2>
                        <div class="flex-grow h-px bg-slate-100"></div>
                    </div>

                    <div class="relative group">
                        <div class="absolute inset-0 bg-slate-900/5 rounded-3xl blur transition group-hover:bg-slate-900/10"></div>
                        <div class="relative bg-white border border-slate-100 rounded-3xl p-8 max-h-[32rem] overflow-y-auto custom-scrollbar shadow-inner">
                            <div class="prose prose-slate prose-sm max-w-none text-slate-600 space-y-6 leading-relaxed">
                                <p class="text-slate-900 font-bold">พนักงานตกลงปฏิบัติตามข้อตกลง ดังนี้:</p>
                                <p><span class="font-bold text-slate-800">ข้อ 1. ข้อมูลอันเป็นความลับ:</span> หมายความรวมถึงข้อมูลทุกประเภทที่เกี่ยวข้องกับธุรกิจ กระบวนการ ขั้นตอนบริหารจัดการ แผนงานทางการตลาด ข้อมูลลูกค้า คู่ค้า งบการเงิน บัญชี โปรแกรมคอมพิวเตอร์ หรือข้อมูลทางเทคนิคใดๆ ที่บริษัทได้เปิดเผยแก่พนักงาน ไม่ว่าจะเป็นในรูปแบบเอกสาร วาจา หรือข้อมูลทางอิเล็กทรอนิกส์</p>
                                <p><span class="font-bold text-slate-800">ข้อ 2. การรักษาความลับ:</span> พนักงานตกลงรักษาข้อมูลอันเป็นความลับไว้เป็นความลับอย่างเคร่งครัด และจะไม่เปิดเผยข้อมูลดังกล่าวแก่บุคคลที่สามไม่ว่าในกรณีใดๆ โดยไม่ได้รับความยินยอมเป็นลายลักษณ์อักษรจากบริษัท</p>
                                <p><span class="font-bold text-slate-800">ข้อ 3. การใช้ข้อมูล:</span> พนักงานจะใช้ข้อมูลอันเป็นความลับเพื่อวัตถุประสงค์ในการปฏิบัติงานตามตำแหน่งหน้าที่ให้แก่บริษัทเท่านั้น ห้ามมิให้นำไปใช้เพื่อประโยชน์ส่วนตัว หรือเพื่อประโยชน์ของบุคคลอื่น</p>
                                <p><span class="font-bold text-slate-800">ข้อ 4. การสำเนาและดัดแปลง:</span> ห้ามมิให้มีการคัดลอก ทำซ้ำ ดัดแปลง หรือถ่ายภาพเอกสารและข้อมูลอันเป็นความลับ เว้นแต่จะได้รับอนุญาตเพื่อการปฏิบัติงานตามหน้าที่</p>
                                <p><span class="font-bold text-slate-800">ข้อ 5. การส่งมอบคืน:</span> เมื่อสิ้นสุดสภาพการจ้าง หรือเมื่อบริษัทร้องขอ พนักงานจะต้องส่งมอบข้อมูลและทรัพย์สินทั้งหมดที่เป็นของบริษัทคืนให้แก่บริษัทโดยทันที</p>
                                <p><span class="font-bold text-slate-800">ข้อ 6. บทลงโทษ:</span> หากพนักงานฝ่าฝืนข้อตกลงนี้ พนักงานตกลงยินยอมรับผิดชอบชดใช้ค่าเสียหายที่เกิดขึ้นแก่บริษัททั้งหมด และบริษัทมีสิทธิลงโทษทางวินัยตามระเบียบข้อบังคับการทำงานถึงขั้นสูงสุด</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-6 bg-blue-600 rounded-3xl shadow-xl shadow-blue-200 transition-transform hover:scale-[1.01]">
                        <div class="flex-none w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">
                            <input type="checkbox" id="accept-terms" x-model="accepted" 
                                class="w-6 h-6 rounded-lg border-white/30 bg-white/10 text-white focus:ring-white transition" 
                                {{ $existing ? 'disabled checked' : '' }}>
                        </div>
                        <label for="accept-terms" class="text-sm font-bold text-white leading-tight cursor-pointer">
                            ข้าพเจ้าได้อ่านและทำความเข้าใจข้อความในสัญญาโดยละเอียดแล้ว จึงได้ลงลายมือชื่อไว้เป็นสำคัญ
                        </label>
                    </div>
                </div>

                <!-- Section 3: Signatures -->
                <div class="space-y-10">
                    <div class="flex items-center gap-4">
                        <span class="flex-none w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-black text-sm">3</span>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">การลงนาม (Execution)</h2>
                        <div class="flex-grow h-px bg-slate-100"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Employee Signature -->
                        <div class="space-y-6">
                            <div class="flex justify-between items-end">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ลงชื่อ พนักงาน (Signature)</label>
                                @if(!$existing)
                                <button type="button" @click="clearPad('employee')" class="text-[10px] font-bold text-red-500 hover:text-red-600 flex items-center gap-1 transition">
                                    <i class="fa-solid fa-eraser"></i> ล้างลายเซ็น
                                </button>
                                @endif
                            </div>
                            <div class="relative bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] p-6 group transition-all"
                                :class="{'bg-white border-blue-400 ring-4 ring-blue-50': activePad === 'employee'}">
                                @if($existing && $existing->employee_signature)
                                    <img src="{{ $existing->employee_signature }}" class="h-48 w-auto mx-auto grayscale group-hover:grayscale-0 transition-all duration-500">
                                    <div class="absolute bottom-4 left-0 right-0 text-center">
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">{{ $existing->full_name }}</p>
                                    </div>
                                @else
                                    <canvas id="signature-employee" class="w-full h-48 cursor-crosshair"></canvas>
                                    <input type="hidden" name="employee_signature" id="employee_signature">
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20 group-hover:opacity-10 transition-opacity" x-show="!activePad || activePad !== 'employee'">
                                        <i class="fa-solid fa-pen-nib text-6xl text-slate-300"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Witnesses -->
                        <div class="space-y-8">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">พยาน 1 (Witness 1)</label>
                                    <input type="text" name="witness1_name" value="{{ $existing->witness1_name ?? '' }}" placeholder="ชื่อพยาน"
                                        class="bg-transparent border-b border-slate-200 px-2 py-1 text-xs font-bold focus:border-blue-500 transition outline-none" {{ $existing ? 'readonly' : '' }}>
                                </div>
                                <div class="relative bg-slate-50 border border-slate-100 rounded-2xl p-4 h-24 group">
                                    @if($existing && $existing->witness1_signature)
                                        <img src="{{ $existing->witness1_signature }}" class="h-16 w-auto mx-auto grayscale opacity-50">
                                    @else
                                        <canvas id="signature-witness1" class="w-full h-16 cursor-crosshair"></canvas>
                                        <input type="hidden" name="witness1_signature" id="witness1_signature">
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">พยาน 2 (Witness 2)</label>
                                    <input type="text" name="witness2_name" value="{{ $existing->witness2_name ?? '' }}" placeholder="ชื่อพยาน"
                                        class="bg-transparent border-b border-slate-200 px-2 py-1 text-xs font-bold focus:border-blue-500 transition outline-none" {{ $existing ? 'readonly' : '' }}>
                                </div>
                                <div class="relative bg-slate-50 border border-slate-100 rounded-2xl p-4 h-24 group">
                                    @if($existing && $existing->witness2_signature)
                                        <img src="{{ $existing->witness2_signature }}" class="h-16 w-auto mx-auto grayscale opacity-50">
                                    @else
                                        <canvas id="signature-witness2" class="w-full h-16 cursor-crosshair"></canvas>
                                        <input type="hidden" name="witness2_signature" id="witness2_signature">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Footer -->
            <div class="bg-slate-900 p-8 md:p-12 flex flex-col md:flex-row justify-between items-center gap-6">
                <a href="{{ route('tracking.show', $requestForm->request_no) }}" class="group flex items-center gap-2 text-slate-400 hover:text-white transition font-bold text-sm">
                    <i class="fa-solid fa-arrow-left transition group-hover:-translate-x-1"></i>
                    กลับไปหน้าติดตามสถานะ
                </a>
                
                @if(!$existing)
                <button type="button" @click="submitForm" :disabled="!accepted" 
                    class="w-full md:w-auto px-12 py-5 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-widest shadow-2xl shadow-blue-500/40 hover:bg-blue-500 disabled:opacity-30 disabled:cursor-not-allowed transition transform hover:-translate-y-1 active:scale-95">
                    ยืนยันบันทึกข้อตกลง
                </button>
                @else
                <div class="px-8 py-4 bg-green-500/10 border border-green-500/20 rounded-2xl flex items-center gap-3 text-green-400">
                    <i class="fa-solid fa-circle-check"></i>
                    <span class="text-sm font-bold uppercase tracking-widest">บันทึกข้อมูลแล้ว</span>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
<script>
function ndaForm() {
    return {
        accepted: {{ $existing ? 'true' : 'false' }},
        activePad: null,
        pads: {},
        
        init() {
            this.$nextTick(() => {
                this.initPad('employee', 'signature-employee');
                this.initPad('witness1', 'signature-witness1');
                this.initPad('witness2', 'signature-witness2');
            });
        },
        
        initPad(name, id) {
            const canvas = document.getElementById(id);
            if (!canvas) return;
            
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            
            this.pads[name] = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgb(15, 23, 42)'
            });
            
            canvas.addEventListener('mousedown', () => this.activePad = name);
            canvas.addEventListener('touchstart', () => this.activePad = name);
        },
        
        clearPad(name) {
            if (this.pads[name]) {
                this.pads[name].clear();
                if (this.activePad === name) this.activePad = null;
            }
        },
        
        submitForm() {
            if (this.pads.employee.isEmpty()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาลงลายมือชื่อ',
                    text: 'คุณต้องลงลายมือชื่อพนักงานเพื่อยืนยันข้อตกลง',
                    confirmButtonColor: '#3b82f6',
                    customClass: {
                        popup: 'rounded-[2rem]',
                        confirmButton: 'rounded-xl font-bold uppercase tracking-widest px-8 py-3'
                    }
                });
                return;
            }
            
            // Set base64 data
            document.getElementById('employee_signature').value = this.pads.employee.toDataURL();
            
            if (!this.pads.witness1.isEmpty()) {
                document.getElementById('witness1_signature').value = this.pads.witness1.toDataURL();
            }
            
            if (!this.pads.witness2.isEmpty()) {
                document.getElementById('witness2_signature').value = this.pads.witness2.toDataURL();
            }
            
            Swal.fire({
                title: 'ยืนยันการบันทึกข้อตกลง?',
                text: "ข้อมูลนี้จะถูกเก็บรักษาไว้เป็นหลักฐานตามระเบียบของบริษัท",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ยืนยันและส่งข้อมูล',
                cancelButtonText: 'ตรวจสอบอีกครั้ง',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl font-bold uppercase tracking-widest px-8 py-3',
                    cancelButton: 'rounded-xl font-bold uppercase tracking-widest px-8 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('nda-form').submit();
                }
            });
        }
    }
}
</script>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
<script>
function ndaForm() {
    return {
        accepted: false,
        activePad: null,
        pads: {},
        
        init() {
            this.$nextTick(() => {
                this.initPad('employee', 'signature-employee');
                this.initPad('witness1', 'signature-witness1');
                this.initPad('witness2', 'signature-witness2');
            });
        },
        
        initPad(name, id) {
            const canvas = document.getElementById(id);
            if (!canvas) return;
            
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            
            this.pads[name] = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgb(30, 41, 59)'
            });
            
            canvas.addEventListener('mousedown', () => this.activePad = name);
        },
        
        clearPad(name) {
            if (this.pads[name]) {
                this.pads[name].clear();
            }
        },
        
        submitForm() {
            if (this.pads.employee.isEmpty()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาลงลายมือชื่อ',
                    text: 'คุณต้องลงลายมือชื่อพนักงานก่อนบันทึกข้อมูล',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            
            // Set base64 data
            document.getElementById('employee_signature').value = this.pads.employee.toDataURL();
            
            if (!this.pads.witness1.isEmpty()) {
                document.getElementById('witness1_signature').value = this.pads.witness1.toDataURL();
            }
            
            if (!this.pads.witness2.isEmpty()) {
                document.getElementById('witness2_signature').value = this.pads.witness2.toDataURL();
            }
            
            Swal.fire({
                title: 'ยืนยันการบันทึก?',
                text: "ข้อมูลข้อตกลงรักษาความลับจะถูกบันทึกเข้าสู่ระบบ",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ยืนยันการบันทึก',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('nda-form').submit();
                }
            });
        }
    }
}
</script>
@endsection
