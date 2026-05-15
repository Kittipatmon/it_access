@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    document.addEventListener('turbo:load', () => {
        document.querySelectorAll('.tom-select').forEach(el => {
            if (el.tomselect) el.tomselect.destroy();
            new TomSelect(el, {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: el.getAttribute('placeholder') || 'ค้นหาชื่อ...',
                allowEmptyOption: true,
            });
        });
    });
</script>

<div class="max-w-4xl mx-auto px-4 py-12" x-data="ndaForm()">
    @php
        $isOwner = $requestForm->user_id === auth()->id();
        $isW1 = $existing && $existing->witness1_user_id === auth()->id();
        $isW2 = $existing && $existing->witness2_user_id === auth()->id();
        
        $canSignEmployee = !$existing && $isOwner;
        $canSignWitness1 = $existing && $isW1 && !$existing->witness1_agreed_at;
        $canSignWitness2 = $existing && $isW2 && !$existing->witness2_agreed_at;
        
        $canSignCompany = $existing && $isCompanyRep && !$existing->company_agreed_at && !$existing->is_auto_sign;
    @endphp
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
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                <span class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-slate-200">
                    <i class="fa-solid fa-file-contract text-xl"></i>
                </span>
                ข้อตกลงรักษาความลับ (NDA)
            </h1>
        </div>
        <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="text-right border-r border-slate-100 pr-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">เลขที่คำร้อง</p>
                <p class="text-sm font-bold text-blue-600 mt-1 leading-none">{{ $requestForm->request_no }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">วันที่สร้าง</p>
                <p class="text-sm font-bold text-slate-700 mt-1 leading-none">{{ now()->format('d/m/Y') }}</p>
            </div>
            @if($existing)
            <div class="pl-4 border-l border-slate-100">
                <a href="{{ route('request.nda.export', $requestForm->request_no) }}" target="_blank"
                    class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-xl transition shadow-lg shadow-slate-200">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">Export PDF</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Main Document -->
    <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <form action="{{ route('request.nda.store', $requestForm->request_no) }}" method="POST" id="nda-form">
            @csrf
            
            <div class="p-8 md:p-16 space-y-12">
                <!-- Page 1 Content -->
                <div class="text-center space-y-4 mb-12">
                    <div class="text-5xl font-bold text-[#E10023] mx-auto mb-16 tracking-tighter select-none" style="font-family: 'Outfit', 'Inter', sans-serif;">Kumwell</div>
                    <h2 class="text-2xl font-semibold text-slate-900 uppercase tracking-widest border-b-2 border-slate-900 inline-block pb-2 ">ข้อตกลงรักษาความลับ</h2>
                </div>

                <div class="prose prose-slate max-w-none text-slate-800 leading-relaxed space-y-6 text-sm md:text-base">
                    <p class="indent-12">
                        ข้อตกลงฉบับนี้ทำขึ้น เมื่อวันที่ {{ now()->day }} เดือน {{ $months[now()->format('m')] ?? now()->format('F') }} {{ now()->year + 543 }} 
                        ณ บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน) เลขที่ 358 ถนน เลี่ยงเมืองนนทบุรี ตำบลบางกระสอ อำเภอเมืองนนทบุรี จังหวัดนนทบุรี ระหว่าง
                    </p>

                    <p class="indent-12">
                        <strong>บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน)</strong> โดยคุณ{{ $manager->fullname ?? 'เกรียงศักดิ์ อำนวยโชค' }} ตำแหน่ง {{ $manager->department_name ?? 'ผู้จัดการแผนกเทคโนโลยีสารสนเทศและการสื่อสาร' }} ตัวแทนผู้มีอำนาจลงนาม เลขที่ 358 ถนน เลี่ยงเมืองนนทบุรี ตำบลบางกระสอ อำเภอเมืองนนทบุรี จังหวัดนนทบุรี 11000 ซึ่งต่อไปนี้ในข้อตกลงจะเรียกว่า <strong>"บริษัท"</strong> ฝ่ายหนึ่ง กับ
                    </p>

                    <!-- Employee Info Grid (Editable) -->
                    <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 not-prose space-y-6 my-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="md:col-span-2">
                                <div class="flex flex-col sm:flex-row gap-5">
                                    <div class="space-y-3">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">คำนำหน้าชื่อ</label>
                                        <div class="flex items-center gap-4 py-2">
                                            @foreach(['นาย', 'นาง', 'นางสาว'] as $prefix)
                                                <label class="relative flex items-center gap-2 cursor-pointer group">
                                                    <input type="radio" name="prefix" value="{{ $prefix }}" x-model="formData.prefix"
                                                        class="w-5 h-5 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500 transition cursor-pointer"
                                                        {{ (old('prefix', $existing->prefix ?? '') == $prefix) ? 'checked' : '' }}
                                                        {{ $existing ? 'disabled' : '' }}>
                                                    <span class="text-sm font-bold text-slate-600 group-hover:text-blue-600 transition">{{ $prefix }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <template x-if="showErrors && !formData.prefix">
                                            <p class="text-[10px] text-red-500 font-bold"><i class="fa-solid fa-circle-exclamation mr-1"></i> กรุณาเลือก</p>
                                        </template>
                                    </div>
                                    <div class="flex-grow space-y-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">ชื่อ-นามสกุล</label>
                                        <input type="text" name="full_name" x-model="formData.full_name"
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition shadow-sm" 
                                            :class="{'border-red-500 bg-red-50/30': showErrors && !formData.full_name}"
                                            {{ $existing ? 'readonly' : '' }}>
                                        <template x-if="showErrors && !formData.full_name">
                                            <p class="text-[10px] text-red-500 font-bold mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> กรุณากรอกชื่อ-นามสกุล</p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">อายุ (ปี)</label>
                                <input type="number" name="age" x-model="formData.age"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" 
                                    :class="{'border-red-500 bg-red-50/30': showErrors && !formData.age}"
                                    {{ $existing ? 'readonly' : '' }}>
                                <template x-if="showErrors && !formData.age">
                                    <p class="text-[10px] text-red-500 font-bold mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> ระบุอายุ</p>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">เลขประจำตัวประชาชน</label>
                                <input type="text" name="id_card_no" x-model="formData.id_card_no"
                                    placeholder="x-xxxx-xxxxx-xx-x"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" 
                                    :class="{'border-red-500 bg-red-50/30': showErrors && !formData.id_card_no}"
                                    {{ $existing ? 'readonly' : '' }}>
                                <template x-if="showErrors && !formData.id_card_no">
                                    <p class="text-[10px] text-red-500 font-bold mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> ระบุเลขบัตรประชาชน</p>
                                </template>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">หมายเลขติดต่อ</label>
                                <input type="text" name="contact_no" x-model="formData.contact_no"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" 
                                    :class="{'border-red-500 bg-red-50/30': showErrors && !formData.contact_no}"
                                    {{ $existing ? 'readonly' : '' }}>
                                <template x-if="showErrors && !formData.contact_no">
                                    <p class="text-[10px] text-red-500 font-bold mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> ระบุเบอร์โทรศัพท์</p>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">บ้านเลขที่</label>
                                <input type="text" name="address_no" x-model="formData.address_no"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" 
                                    :class="{'border-red-500 bg-red-50/30': showErrors && !formData.address_no}"
                                    {{ $existing ? 'readonly' : '' }}>
                                <template x-if="showErrors && !formData.address_no">
                                    <p class="text-[10px] text-red-500 font-bold mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> ระบุบ้านเลขที่</p>
                                </template>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ซอย</label>
                                <input type="text" name="soi" x-model="formData.soi"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" {{ $existing ? 'readonly' : '' }}>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ถนน</label>
                                <input type="text" name="road" x-model="formData.road"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" {{ $existing ? 'readonly' : '' }}>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ตำบล/แขวง</label>
                                <input type="text" name="tambon" x-model="formData.tambon" list="tambon-list"
                                    @change="onTambonChange"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" 
                                    :class="{'border-red-500 bg-red-50/30': showErrors && !formData.tambon}"
                                    {{ $existing ? 'readonly' : '' }}>
                                <datalist id="tambon-list">
                                    <template x-for="item in filteredTambons" :key="item.id">
                                        <option :value="item.name_th"></option>
                                    </template>
                                </datalist>
                                <template x-if="showErrors && !formData.tambon">
                                    <p class="text-[10px] text-red-500 font-bold mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> ระบุตำบล</p>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">อำเภอ/เขต</label>
                                <input type="text" name="amphoe" x-model="formData.amphoe" list="amphoe-list"
                                    @change="onAmphureChange"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" 
                                    :class="{'border-red-500 bg-red-50/30': showErrors && !formData.amphoe}"
                                    {{ $existing ? 'readonly' : '' }}>
                                <datalist id="amphoe-list">
                                    <template x-for="item in filteredAmphures" :key="item.id">
                                        <option :value="item.name_th"></option>
                                    </template>
                                </datalist>
                                <template x-if="showErrors && !formData.amphoe">
                                    <p class="text-[10px] text-red-500 font-bold mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> ระบุอำเภอ</p>
                                </template>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">จังหวัด</label>
                                <input type="text" name="province" x-model="formData.province" list="province-list"
                                    @change="onProvinceChange"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition" 
                                    :class="{'border-red-500 bg-red-50/30': showErrors && !formData.province}"
                                    {{ $existing ? 'readonly' : '' }}>
                                <datalist id="province-list">
                                    <template x-for="item in addressData.provinces" :key="item.id">
                                        <option :value="item.name_th"></option>
                                    </template>
                                </datalist>
                                <template x-if="showErrors && !formData.province">
                                    <p class="text-[10px] text-red-500 font-bold mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> ระบุจังหวัด</p>
                                </template>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 italic">ปรากฏตามสำเนาบัตรประชาชนที่รับรองสำเนาถูกต้องซึ่งต่อไปนี้ในข้อตกลงนี้จะเรียกว่า <strong>"พนักงาน"</strong> อีกฝ่ายหนึ่ง</p>
                    </div>

                    <p class="indent-12">
                        โดยที่บริษัทเป็นเจ้าของข้อมูลเกี่ยวกับการดำเนินธุรกิจทางการค้า และระบบการบริหารจัดการ ทั้งภายในและภายนอกของบริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน) ซึ่งต่อไปนี้จะเรียกว่า <strong>"ข้อมูลที่เป็นความลับ"</strong> มีความประสงค์ที่จะเปิดเผยข้อมูลดังกล่าวให้แก่พนักงาน และพนักงานมีความต้องการที่จะใช้ข้อมูลของบริษัท เพื่อการปฏิบัติงานตามตำแหน่งและหน้าที่ในฐานะพนักงานของบริษัท ซึ่งบริษัทประสงค์ที่คุ้มครองเรื่องดังกล่าวไว้เป็นข้อมูลที่เป็นความลับ
                    </p>

                    <p class="indent-12">
                        ด้วยเหตุผลสำคัญในการปฏิบัติงานของ พนักงาน ในฐานะ พนักงานของบริษัท ย่อมได้รับรู้ข้อมูลที่เป็นความลับ
                    </p>

                    <p class="indent-12">
                        ในการนี้ เพื่อรักษาความปลอดภัยของข้อมูลและความมั่นใจของ ทั้งสองฝ่ายจึงตกลงทำข้อตกลงนี้ขึ้น เพื่อการปฏิบัติตามข้อตกลงต่างๆ ในการไม่เปิดเผยข้อมูล โดยมีเงื่อนไขดังต่อไปนี้
                    </p>

                    <p><strong>ข้อ 1. ในข้อตกลงนี้ "ข้อมูลที่เป็นความลับ"</strong> หมายความถึง ข้อมูลกระบวนบริหารจัดการภายในของบริษัท และข้อมูลเกี่ยวกับธุรกิจ โครงสร้าง ลูกค้า ผู้รับจ้าง และกิจการค้าของบริษัท หรือข้อมูลอื่นๆ ที่เกี่ยวข้องกับธุรกิจและกิจการค้าของบริษัท รายงาน ผลการวิเคราะห์ ความคิดเห็น แบบแปลน เอกสารอื่นใดที่พนักงานได้จัดทำขึ้น หรือได้ทำร่วมกับผู้อื่น ไม่ว่าโดยทางตรงหรือโดยทางอ้อม และข้อมูลใดๆสิ่งที่สื่อความหมายให้รู้ข้อความ เรื่องราว ข้อเท็จจริง หรือสิ่งใด ไม่ว่าการสื่อความหมายนั้นจะผ่านวิธีการใด ๆ และไม่ว่าจะจัดทำไว้ในรูปใด ๆ และให้หมายความรวมถึงสูตร รูปแบบ แนวความคิด งานที่ได้รวบรวมหรือประกอบขึ้น โปรแกรม วิธีการ เทคนิค หรือกรรมวิธีประมวลผลด้วยวิธีการทางอิเล็กทรอนิกส์ ด้วย</p>

                    <p class="indent-12">
                        รวมทั้งข้อมูลของบุคคลภายในและภายนอกที่บริษัทได้เปิดเผยแก่พนักงาน หรือข้อมูลได้รับมาโดยหน้าที่ที่เกี่ยวข้องทั้งทางตรงหรือทางอ้อม และบริษัทประสงค์ให้พนักงานเก็บรักษาข้อมูลดังกล่าวไว้เป็นความลับ และความลับทางการค้าของบริษัท โดยข้อมูลดังกล่าวจะเกี่ยวข้องกับข้อมูลคู่ค้า ข้อมูลการเงินและบัญชี หรือแผนทางการตลาด หรือแผนธุรกิจต่างๆ ซึ่งรวมถึงแต่ไม่จำกัดเฉพาะกระบวนการ ขั้นตอนวิธี โปรแกรมคอมพิวเตอร์ แบบ ต้นแบบ ภาพวาด สูตร เทคนิค การพัฒนาผลิตภัณฑ์ ข้อมูลการทดลอง และข้อมูลอื่นใดที่เกี่ยวข้องกับข้อมูลที่เป็นความลับดังกล่าว ถือว่า มีความสำคัญยิ่ง และเป็นความลับที่มิอาจเปิดเผยต่อสาธารณะได้ และห้ามเปิดเผยแก่บุคคลภายนอก หรือองค์กรที่ 3 โดยเด็ดขาด พนักงานทราบดีว่า ข้อมูลที่เป็นความลับเป็นกรรมสิทธิ์ของบริษัทโดยแท้
                    </p>

                    <p><strong>ข้อ 2. พนักงานตกลงรักษาไว้ซึ่งข้อมูลที่เป็นความลับ</strong> ทั้งในรูปแบบข่าวสาร และข้อมูลใด ๆ ของบริษัท ตลอดถึงข้อมูลที่เกี่ยวกับเทคนิค เทคโนโลยี ข้อมูลลูกค้า ข้อมูลผู้รับจ้าง ข้อมูลทางการเงินหรือเรื่องอื่นใดที่พนักงานได้รับรู้รับทราบ เนื่องจากการปฏิบัติงานให้แก่บริษัทตลอดระยะเวลาที่ข้อมูลยังเป็นของบริษัท และไม่เปิดเผยข้อมูลที่เป็นความลับใด ๆ แก่บุคคลที่สามโดยไม่ได้รับความยินยอมเป็นลายลักษณ์อักษรล่วงหน้าจากบริษัท โดยพนักงานจะไม่นำเอาข้อมูลที่เป็นความลับไปใช้ ในประการที่อาจทำให้บริษัทเกิดความเสียหาย หรือเสียประโยชน์</p>

                    <p><strong>ข้อ 3. พนักงานตกลงจะดำเนินการตามขั้นตอนที่จำเป็นอย่างสุดความสามารถ</strong> เพื่อหลีกเลี่ยงมิให้ข้อมูลที่เป็นความลับถูกเปิดเผย และใช้ความระมัดระวังอย่างยิ่ง เพื่อป้องกันบุคคลที่เกี่ยวข้องเข้าถึงข้อมูลอันเป็นความลับนั้น</p>

                    <p><strong>ข้อ 4. ข้อจำกัดในการใช้ข้อมูล</strong> ข้อมูลที่บริษัทหรือในนามของบริษัทที่เปิดเผยแก่พนักงานให้ใช้เพื่อการปฏิบัติงานตามตำแหน่งและหน้าที่ในฐานะพนักงานของบริษัทเท่านั้น โดยห้ามมิให้ใช้เพื่อดังต่อไปนี้</p>
                    <ul class="list-none pl-12 space-y-2">
                        <li>4.1 ห้ามใช้เพื่อหาประโยชน์ส่วนตัว</li>
                        <li>4.2 ห้ามใช้เพื่อหาประโยชน์ในการร่วมมือกับบุคคลหรือองค์กรอื่นใด</li>
                        <li>4.3 ห้ามใช้เพื่อวัตถุประสงค์ในเชิงพาณิชย์</li>
                        <li>4.4 ห้ามใช้ หรือพยายามที่จะใช้ข้อมูลหรือสิ่งที่ได้มาจากข้อมูลเพื่อการอื่นใด โดยไม่ได้รับอนุญาตจากผู้ให้ข้อมูล</li>
                        <li>4.5 ห้ามอ้างถึงหรือรวมเข้าไปเป็นส่วนหนึ่งของการประดิษฐ์ใด ๆ นอกจากจะได้รับอนุญาตจากผู้ให้ข้อมูล หรือขอรับความคุ้มครองทรัพย์สินทางปัญญาใด ๆ ในนามของผู้รับข้อมูลหรือผู้อื่นผู้ใดยกเว้นผู้ให้ข้อมูล</li>
                    </ul>

                    <p><strong>ข้อ 5. พนักงานจะไม่กระทำ หรืองดเว้นกระทำ หรือยอมให้ผู้อื่น</strong> เพื่อการคัดลอก ดัดแปลง ทำซ้ำ แก้ไข เพิ่มเติม อัดหรือทำสำเนา ถ่ายภาพ หรือกระทำการอื่นใดกับเอกสารดังกล่าวของบริษัทโดยเด็ดขาด เว้นแต่จะได้รับอนุญาตเป็นลายลักษณ์อักษรจากทางบริษัทแล้วเท่านั้น</p>

                    <p><strong>ข้อ 6. ภายใต้การปฏิบัติงาน</strong> บริษัทมีสิทธิทำการตรวจสอบ เรียกดู ข้อมูลที่เป็นความลับตามที่ข้อ 2. ที่พนักงานเก็บรักษาไว้ และมีสิทธิเรียกร้องต่อพนักงานให้ดำเนินการแก้ไขหรือตามมาตรการที่กำหนด หากตรวจพบข้อมูลที่เป็นความลับที่ไม่ถูกต้อง นอกจากนี้บริษัทยังมีสิทธิเรียกร้องต่อพนักงานให้แก้ไขปรับปรุงนโยบายและมาตรการปฏิบัติงานของพนักงาน ในส่วนที่เกี่ยวข้องกับการเก็บรักษาข้อมูลของบริษัท</p>

                    <p><strong>ข้อ 7. เมื่อได้รับการร้องจากบริษัท หรือหน่วยงานตัวแทนของบริษัท การนั้น</strong> พนักงานมีหน้าที่ต้องส่งมอบทรัพย์สินและเอกสารทั้งหมด ซึ่งเป็นข้อมูลที่เป็นความลับ ที่เป็นของบริษัท และข้อมูลที่บริษัทเจ้าของกรรมสิทธิ์ ที่อยู่ในการครอบครองของพนักงานให้แก่บริษัท โดยพลันทันที</p>

                    <p><strong>ข้อ 8. ข้อตกลงรักษาความลับนี้มีผลบังคับตั้งแต่วันที่คู่สัญญาทั้งสองฝ่ายลงนาม</strong> โดยให้ข้อตกลงฉบับนี้มีผลตั้งแต่เริ่มเข้าทำงานในบริษัท และพนักงานจะเปิดเผยข้อมูลที่เป็นความลับได้ต่อเมื่อได้รับความยินยอมเป็นหนังสือจากฝ่ายผู้ให้ข้อมูลดังกล่าวก่อน หรือจนกว่าข้อมูลที่เป็นความลับนั้นกลายเป็นข้อมูลที่ไม่ใช่ความลับโดยชอบด้วยกฎหมาย</p>

                    <p><strong>ข้อ 9. การชดใช้ค่าเสียหาย</strong> หากพนักงานปฏิบัติ หรือละเว้นการปฏิบัติหน้าที่ หรือปฏิบัติผิดฝ่าฝืนข้อตกลงรักษาข้อมูลความลับตามข้อตกลงนี้ข้อหนึ่งข้อใด หรือกระทำการใดๆ เป็นเหตุให้เกิดความเสียหายแก่บริษัท พนักงานตกลงยินยอมชดใช้ค่าเสียหายแก่บริษัททั้งหมดภายในกำหนดเวลาที่บริษัทเรียกร้อง และ/หรือบุคคลที่ได้รับความเสียหายสำหรับความเสียหายเช่นว่านั้น และบริษัทมีสิทธิลงโทษทางวินัยแก่พนักงานตามระเบียบข้อบังคับของบริษัท</p>

                    <p><strong>ข้อ 10. ในกรณีที่ข้อตกลงนี้ข้อใดข้อหนึ่งหรือหลายข้อแห่งข้อตกลงนี้ตกเป็นอันไม่สมบูรณ์</strong> หรือตกเป็นโมฆะด้วยเหตุใด ๆ ก็ตาม ความไม่สมบูรณ์หรือความเป็นโมฆะของข้อตกลงเช่นว่านี้จะไม่กระทบกระเทือนถึงความสมบูรณ์ของข้อตกลงนี้ในส่วนอื่น ๆ</p>

                    <p class="indent-12 mt-12">
                        ข้อตกลงนี้ มีข้อ 1. ถึงข้อ 10. จำนวน 4 หน้า และถูกจัดทำขึ้น 1 ฉบับ คู่สัญญาได้อ่าน และเข้าใจข้อความในสัญญาโดยละเอียดแล้ว จึงได้ลงลายมือชื่อพร้อมทั้งประทับตรา (ถ้ามี) ไว้เป็นสำคัญ และต่างยึดถือไว้ฝ่ายละหนึ่งฉบับ
                    </p>
                </div>

                <!-- Signatures Section -->
                <div class="pt-12 border-t border-slate-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                        
                        <!-- Company Signature -->
                        <div class="space-y-6 text-center">
                            <div class="relative group">
                                <div class="h-32 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl relative overflow-hidden transition-all group-hover:border-blue-400"
                                    :class="{
                                        'ring-4 ring-blue-50 border-blue-500 bg-white': activePad === 'company',
                                    }">
                                    
                                    @php
                                        $shouldAutoSign = $existing ? $existing->is_auto_sign : $autoSign;
                                    @endphp
                                    
                                    @if($shouldAutoSign)
                                        @if($manager && $manager->signature_url)
                                            <img src="{{ $manager->signature_url }}" class="h-full mx-auto mix-blend-multiply" alt="Signature">
                                        @else
                                            <div class="h-full flex items-center justify-center text-3xl font-bold text-slate-700">
                                                {{ $manager ? $manager->fullname : 'เกรียงศักดิ์ อำนวยโชค' }}
                                            </div>
                                        @endif
                                    @elseif($existing && $existing->company_signature && str_starts_with($existing->company_signature, 'data:image'))
                                        <img src="{{ $existing->company_signature }}" class="h-full mx-auto">
                                    @elseif($existing && $existing->company_agreed_at)
                                        {{-- Moved text-based signature to dotted line below --}}
                                        <div class="h-full flex items-center justify-center text-slate-300 italic text-xs">บันทึกข้อมูลเรียบร้อยแล้ว</div>
                                    @else
                                        <div :class="{'opacity-0 pointer-events-none': isTyped.company}">
                                            <canvas id="signature-company" class="w-full h-full cursor-crosshair @if(!$canSignCompany) pointer-events-none opacity-20 @endif"></canvas>
                                        </div>
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20 group-hover:opacity-10" x-show="!activePad">
                                            <i class="fa-solid fa-pen-nib text-4xl"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($canSignCompany)
                                <div class="absolute -top-3 -right-3 flex gap-2">
                                    <input type="file" id="upload-company" accept="image/*" class="hidden" @change="uploadToPad('company', $event)">
                                    <button type="button" @click="document.getElementById('upload-company').click()" class="w-8 h-8 bg-emerald-600 text-white rounded-full shadow-md flex items-center justify-center hover:bg-emerald-700 transition" title="อัปโหลดรูปภาพลายเซ็น">
                                        <i class="fa-solid fa-image text-xs"></i>
                                    </button>
                                    @if(Auth::user()->signature)
                                    <button type="button" @click="useSystemSignature('company')" class="w-8 h-8 bg-blue-600 text-white rounded-full shadow-md flex items-center justify-center hover:bg-blue-700 transition" title="ใช้ลายเซ็นจากระบบ">
                                        <i class="fa-solid fa-file-signature text-xs"></i>
                                    </button>
                                    @endif
                                    <button type="button" @click="clearPad('company')" class="w-8 h-8 bg-white border border-slate-100 rounded-full shadow-md flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                                        <i class="fa-solid fa-eraser text-xs"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                            
                            <div class="space-y-3">
                                <div class="space-y-1">
                                    <div class="relative inline-block">
                                        <p class="text-sm font-bold text-slate-900">ลงชื่อ...................................................... บริษัท</p>
                                        <span x-show="isTyped.company" x-text="typedName.company" class="absolute left-1/2 -translate-x-1/2 bottom-[1px] text-2xl font-bold text-slate-800 whitespace-nowrap pointer-events-none"></span>
                                        @if($existing && $existing->company_agreed_at && !str_starts_with($existing->company_signature ?? '', 'data:image'))
                                            <span class="absolute left-1/2 -translate-x-1/2 bottom-[1px] text-2xl font-bold text-slate-800 whitespace-nowrap pointer-events-none">
                                                {{ $manager->fullname ?? 'เกรียงศักดิ์ อำนวยโชค' }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-bold text-slate-500">(คุณ{{ $manager->fullname ?? 'เกรียงศักดิ์ อำนวยโชค' }})</p>
                                    @if($existing && $existing->company_agreed_at)
                                        <p class="text-[10px] text-green-500 font-bold uppercase tracking-widest mt-1">รับรองแล้วเมื่อ {{ $existing->company_agreed_at->format('d/m/Y H:i') }}</p>
                                    @elseif($existing && !$existing->is_auto_sign)
                                        <p class="text-[10px] text-slate-400 italic mt-1">รอผู้รับมอบอำนาจลงนาม</p>
                                    @endif
                                </div>
                                
                                @if($canSignCompany)
                                    <button type="button" @click="agreeCompany()" class="mt-4 w-full py-2 bg-blue-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition transform hover:-translate-y-0.5 active:scale-95">
                                        ลงนามในนามบริษัท
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Employee Signature -->
                        <div class="space-y-6 text-center">
                            <div class="relative group">
                                <div class="h-32 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl relative overflow-hidden transition-all group-hover:border-blue-400"
                                    :class="{
                                        'ring-4 ring-blue-50 border-blue-500 bg-white': activePad === 'employee',
                                        'border-red-500 bg-red-50/30': showErrors && !isSigned.employee
                                    }">
                                    @if($existing && $existing->employee_signature && str_starts_with($existing->employee_signature, 'data:image'))
                                        <img src="{{ $existing->employee_signature }}" class="h-full mx-auto">
                                    @elseif($existing && $existing->employee_signature)
                                        {{-- Moved text-based signature to dotted line below --}}
                                        <div class="h-full flex items-center justify-center text-slate-300 italic text-xs">บันทึกข้อมูลเรียบร้อยแล้ว</div>
                                    @else
                                        <div :class="{'opacity-0 pointer-events-none': isTyped.employee}">
                                            <canvas id="signature-employee" class="w-full h-full cursor-crosshair @if(!$canSignEmployee) pointer-events-none opacity-20 @endif"></canvas>
                                        </div>
                                        <input type="hidden" name="employee_signature" id="employee_signature" value="{{ old('employee_signature') }}">
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20 group-hover:opacity-10" x-show="!activePad">
                                            <i class="fa-solid fa-pen-nib text-4xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <template x-if="showErrors && !isSigned.employee">
                                    <p class="text-[10px] text-red-500 font-bold mt-2"><i class="fa-solid fa-circle-exclamation mr-1"></i> กรุณาลงลายมือชื่อพนักงาน</p>
                                </template>
                                @if($canSignEmployee)
                                <div class="absolute -top-3 -right-3 flex gap-2">
                                    <input type="file" id="upload-employee" accept="image/*" class="hidden" @change="uploadToPad('employee', $event)">
                                    <button type="button" @click="document.getElementById('upload-employee').click()" class="w-8 h-8 bg-emerald-600 text-white rounded-full shadow-md flex items-center justify-center hover:bg-emerald-700 transition" title="อัปโหลดรูปภาพลายเซ็น">
                                        <i class="fa-solid fa-image text-xs"></i>
                                    </button>
                                    @if(Auth::user()->signature)
                                    <button type="button" @click="useSystemSignature('employee')" class="w-8 h-8 bg-blue-600 text-white rounded-full shadow-md flex items-center justify-center hover:bg-blue-700 transition" title="ใช้ลายเซ็นจากระบบ">
                                        <i class="fa-solid fa-file-signature text-xs"></i>
                                    </button>
                                    @endif
                                    <button type="button" @click="clearPad('employee')" class="w-8 h-8 bg-white border border-slate-100 rounded-full shadow-md flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                                        <i class="fa-solid fa-eraser text-xs"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                            <div class="space-y-3">
                                <div class="space-y-1">
                                    <div class="relative inline-block">
                                        <p class="text-sm font-bold text-slate-900">ลงชื่อ...................................................... พนักงาน</p>
                                        <span x-show="isTyped.employee" x-text="typedName.employee" class="absolute left-1/2 -translate-x-1/2 bottom-[1px] text-2xl font-bold text-slate-800 whitespace-nowrap pointer-events-none"></span>
                                        @if($existing && $existing->employee_signature && !str_starts_with($existing->employee_signature, 'data:image'))
                                            <span class="absolute left-1/2 -translate-x-1/2 bottom-[1px] text-2xl font-bold text-slate-800 whitespace-nowrap pointer-events-none">
                                                {{ $existing->prefix }}{{ $existing->full_name }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-bold text-slate-500">({{ $requestForm->firstname . ' ' . $requestForm->lastname }})</p>
                                </div>
                                @if($canSignEmployee)
                                <button type="button" @click="useNameAsSignature('employee', '{{ $requestForm->firstname . ' ' . $requestForm->lastname }}')" 
                                    class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest flex items-center gap-2 mx-auto transition hover:scale-105 active:scale-95">
                                    <i class="fa-solid fa-keyboard"></i>
                                    พิมพ์ชื่อแทนลายมือชื่อ
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Witness 1 -->
                        <div class="space-y-6 text-center">
                            <div class="relative group">
                                <div class="h-32 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl relative overflow-hidden transition-all"
                                    :class="{'ring-4 ring-blue-50 border-blue-500 bg-white': activePad === 'witness1'}">
                                    @if($existing && $existing->witness1_signature && str_starts_with($existing->witness1_signature, 'data:image'))
                                        <img src="{{ $existing->witness1_signature }}" class="h-full mx-auto">
                                    @elseif($existing && $existing->witness1_agreed_at)
                                        {{-- Moved text-based signature to dotted line below --}}
                                        <div class="h-full flex items-center justify-center text-slate-300 italic text-xs">บันทึกข้อมูลเรียบร้อยแล้ว</div>
                                    @else
                                        <div :class="{'opacity-0 pointer-events-none': isTyped.witness1}">
                                            <canvas id="signature-witness1" class="w-full h-full cursor-crosshair @if(!$canSignWitness1) pointer-events-none opacity-20 @endif"></canvas>
                                        </div>
                                        <input type="hidden" name="witness1_signature" id="witness1_signature" value="{{ old('witness1_signature') }}">
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20 group-hover:opacity-10" x-show="!activePad">
                                            <i class="fa-solid fa-pen-nib text-4xl"></i>
                                        </div>
                                    @endif
                                </div>
                                @if($canSignWitness1)
                                <div class="absolute -top-3 -right-3 flex gap-2">
                                    <input type="file" id="upload-witness1" accept="image/*" class="hidden" @change="uploadToPad('witness1', $event)">
                                    <button type="button" @click="document.getElementById('upload-witness1').click()" class="w-8 h-8 bg-emerald-600 text-white rounded-full shadow-md flex items-center justify-center hover:bg-emerald-700 transition" title="อัปโหลดรูปภาพลายเซ็น">
                                        <i class="fa-solid fa-image text-xs"></i>
                                    </button>
                                    @if(Auth::user()->signature)
                                    <button type="button" @click="useSystemSignature('witness1')" class="w-8 h-8 bg-blue-600 text-white rounded-full shadow-md flex items-center justify-center hover:bg-blue-700 transition" title="ใช้ลายเซ็นจากระบบ">
                                        <i class="fa-solid fa-file-signature text-xs"></i>
                                    </button>
                                    @endif
                                    <button type="button" @click="clearPad('witness1')" class="w-8 h-8 bg-white border border-slate-100 rounded-full shadow-md flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                                        <i class="fa-solid fa-eraser text-xs"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <div class="relative inline-block">
                                        <p class="text-sm font-bold text-slate-900">ลงชื่อ...................................................... พยาน</p>
                                        <span x-show="isTyped.witness1" x-text="typedName.witness1" class="absolute left-1/2 -translate-x-1/2 bottom-[1px] text-2xl font-bold text-slate-800 whitespace-nowrap pointer-events-none"></span>
                                        @if($existing && $existing->witness1_agreed_at && !str_starts_with($existing->witness1_signature ?? '', 'data:image'))
                                            <span class="absolute left-1/2 -translate-x-1/2 bottom-[1px] text-2xl font-bold text-slate-800 whitespace-nowrap pointer-events-none">
                                                {{ $existing->witness1_name }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($existing)
                                        <p class="text-xs font-bold text-slate-500">(<span x-text="witness1_data.fullname || '{{ $existing->witness1_name }}'"></span>)</p>
                                        @if($existing->witness1_agreed_at)
                                            <p class="text-[10px] text-green-500 font-bold uppercase tracking-widest mt-1">รับรองแล้วเมื่อ {{ $existing->witness1_agreed_at->format('d/m/Y H:i') }}</p>
                                        @elseif($existing->witness1_user_id !== auth()->id())
                                            <p class="text-[10px] text-slate-400 italic mt-1">รอการรับรองจากพยาน</p>
                                        @endif
                                    @else
                                        <div class="relative">
                                            <button type="button" @click="openWitnessModal(1)" 
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-blue-500 transition flex items-center justify-between hover:bg-slate-100 shadow-sm"
                                                :class="{
                                                    'border-blue-500 ring-2 ring-blue-100': witness1_data.id,
                                                    'border-red-500 bg-red-50/30': showErrors && !witness1_data.id
                                                }">
                                                <span x-text="witness1_data.fullname || 'เลือกพยาน 1'" :class="{'text-blue-600': witness1_data.id}"></span>
                                                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                                            </button>
                                            <input type="hidden" name="witness1_user_id" :value="witness1_data.id" required>
                                            <template x-if="witness1_data.department_name">
                                                <p class="text-[10px] text-slate-400 mt-1" x-text="witness1_data.department_name"></p>
                                            </template>
                                            <template x-if="showErrors && !witness1_data.id">
                                                <p class="text-[10px] text-red-500 font-bold mt-2 text-left"><i class="fa-solid fa-circle-exclamation mr-1"></i> กรุณาเลือกพยานคนที่ 1</p>
                                            </template>
                                        </div>
                                    @endif
                                </div>
                                @if($canSignWitness1)
                                <div class="mt-3">
                                    <button type="button" @click="useNameAsSignature('witness1', '{{ $existing->witness1_name ?? '' }}')" 
                                        class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest flex items-center gap-2 mx-auto transition hover:scale-105 active:scale-95">
                                        <i class="fa-solid fa-keyboard"></i>
                                        พิมพ์ชื่อแทนลายมือชื่อ
                                    </button>
                                </div>
                                @endif

                                @if($existing && $existing->witness1_user_id === auth()->id() && !$existing->witness1_agreed_at)
                                    <button type="button" @click="agreeWitness(1)" class="mt-4 w-full py-2 bg-blue-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition transform hover:-translate-y-0.5 active:scale-95">
                                        รับรองการเป็นพยาน
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Witness 2 -->
                        <div class="space-y-6 text-center">
                            <div class="relative group">
                                <div class="h-32 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl relative overflow-hidden transition-all"
                                    :class="{'ring-4 ring-blue-50 border-blue-500 bg-white': activePad === 'witness2'}">
                                    @if($existing && $existing->witness2_signature && str_starts_with($existing->witness2_signature, 'data:image'))
                                        <img src="{{ $existing->witness2_signature }}" class="h-full mx-auto">
                                    @elseif($existing && $existing->witness2_agreed_at)
                                        {{-- Moved text-based signature to dotted line below --}}
                                        <div class="h-full flex items-center justify-center text-slate-300 italic text-xs">บันทึกข้อมูลเรียบร้อยแล้ว</div>
                                    @else
                                        <div :class="{'opacity-0 pointer-events-none': isTyped.witness2}">
                                            <canvas id="signature-witness2" class="w-full h-full cursor-crosshair @if(!$canSignWitness2) pointer-events-none opacity-20 @endif"></canvas>
                                        </div>
                                        <input type="hidden" name="witness2_signature" id="witness2_signature" value="{{ old('witness2_signature') }}">
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20 group-hover:opacity-10" x-show="!activePad">
                                            <i class="fa-solid fa-pen-nib text-4xl"></i>
                                        </div>
                                    @endif
                                </div>
                                @if($canSignWitness2)
                                <div class="absolute -top-3 -right-3 flex gap-2">
                                    <input type="file" id="upload-witness2" accept="image/*" class="hidden" @change="uploadToPad('witness2', $event)">
                                    <button type="button" @click="document.getElementById('upload-witness2').click()" class="w-8 h-8 bg-emerald-600 text-white rounded-full shadow-md flex items-center justify-center hover:bg-emerald-700 transition" title="อัปโหลดรูปภาพลายเซ็น">
                                        <i class="fa-solid fa-image text-xs"></i>
                                    </button>
                                    @if(Auth::user()->signature)
                                    <button type="button" @click="useSystemSignature('witness2')" class="w-8 h-8 bg-blue-600 text-white rounded-full shadow-md flex items-center justify-center hover:bg-blue-700 transition" title="ใช้ลายเซ็นจากระบบ">
                                        <i class="fa-solid fa-file-signature text-xs"></i>
                                    </button>
                                    @endif
                                    <button type="button" @click="clearPad('witness2')" class="w-8 h-8 bg-white border border-slate-100 rounded-full shadow-md flex items-center justify-center text-red-500 hover:bg-red-50 transition">
                                        <i class="fa-solid fa-eraser text-xs"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <div class="relative inline-block">
                                        <p class="text-sm font-bold text-slate-900">ลงชื่อ...................................................... พยาน</p>
                                        <span x-show="isTyped.witness2" x-text="typedName.witness2" class="absolute left-1/2 -translate-x-1/2 bottom-[1px] text-2xl font-bold text-slate-800 whitespace-nowrap pointer-events-none"></span>
                                        @if($existing && $existing->witness2_agreed_at && !str_starts_with($existing->witness2_signature ?? '', 'data:image'))
                                            <span class="absolute left-1/2 -translate-x-1/2 bottom-[1px] text-2xl font-bold text-slate-800 whitespace-nowrap pointer-events-none">
                                                {{ $existing->witness2_name }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($existing)
                                        @if($existing->witness2_user_id)
                                            <p class="text-xs font-bold text-slate-500">(<span x-text="witness2_data.fullname || '{{ $existing->witness2_name }}'"></span>)</p>
                                            @if($existing->witness2_agreed_at)
                                                <p class="text-[10px] text-green-500 font-bold uppercase tracking-widest mt-1">รับรองแล้วเมื่อ {{ $existing->witness2_agreed_at->format('d/m/Y H:i') }}</p>
                                            @elseif($existing->witness2_user_id !== auth()->id())
                                                <p class="text-[10px] text-slate-400 italic mt-1">รอการรับรองจากพยาน</p>
                                            @endif
                                        @else
                                            <p class="text-xs font-bold text-slate-300 italic">(ไม่มีพยานคนที่ 2)</p>
                                        @endif
                                    @else
                                        <div class="relative">
                                            <button type="button" @click="openWitnessModal(2)" 
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-blue-500 transition flex items-center justify-between hover:bg-slate-100 shadow-sm"
                                                :class="{
                                                    'border-blue-500 ring-2 ring-blue-100': witness2_data.id
                                                }">
                                                <span x-text="witness2_data.fullname || 'เลือกพยาน 2 (ไม่บังคับ)'" :class="{'text-blue-600': witness2_data.id}"></span>
                                                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                                            </button>
                                            <input type="hidden" name="witness2_user_id" :value="witness2_data.id">
                                            <template x-if="witness2_data.department_name">
                                                <p class="text-[10px] text-slate-400 mt-1" x-text="witness2_data.department_name"></p>
                                            </template>
                                        </div>
                                    @endif
                                </div>
                                @if($canSignWitness2)
                                <div class="mt-3">
                                    <button type="button" @click="useNameAsSignature('witness2', '{{ $existing->witness2_name ?? '' }}')" 
                                        class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest flex items-center gap-2 mx-auto transition hover:scale-105 active:scale-95">
                                        <i class="fa-solid fa-keyboard"></i>
                                        พิมพ์ชื่อแทนลายมือชื่อ
                                    </button>
                                </div>
                                @endif

                                @if($existing && $existing->witness2_user_id === auth()->id() && !$existing->witness2_agreed_at)
                                    <button type="button" @click="agreeWitness(2)" class="mt-4 w-full py-2 bg-blue-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition transform hover:-translate-y-0.5 active:scale-95">
                                        รับรองการเป็นพยาน
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Agreement Footer -->
                <div class="flex justify-between items-center text-[10px] font-bold text-slate-300 uppercase tracking-widest pt-8">
                    <div>Kumwell Corporation Public Company Limited</div>
                    <div>หน้าที่ 1-4</div>
                </div>
            </div>

            <!-- Action Footer -->
            <div class="bg-slate-900 p-8 md:p-12 flex flex-col md:flex-row justify-between items-center gap-6">
                <a href="{{ route('tracking.show', $requestForm->request_no) }}" class="group flex items-center gap-2 text-slate-400 hover:text-white transition font-bold text-sm">
                    <i class="fa-solid fa-arrow-left transition group-hover:-translate-x-1"></i>
                    กลับไปหน้าติดตามสถานะ
                </a>
                
                @if(!$existing)
                <button type="button" @click="submitForm" 
                    class="w-full md:w-auto px-12 py-5 bg-blue-600 text-white rounded-[2rem] font-bold uppercase tracking-widest shadow-2xl shadow-blue-500/40 hover:bg-blue-500 transition transform hover:-translate-y-1 active:scale-95">
                    ยืนยันบันทึกข้อตกลงรักษาความลับ
                </button>
                @else
                    @if($existing->witness1_agreed_at && (!$existing->witness2_user_id || $existing->witness2_agreed_at))
                        <div class="px-8 py-4 bg-green-500/10 border border-green-500/20 rounded-2xl flex items-center gap-3 text-green-600">
                            <i class="fa-solid fa-circle-check"></i>
                            <span class="text-sm font-bold uppercase tracking-widest">บันทึกข้อตกลงเสร็จสมบูรณ์แล้ว</span>
                        </div>
                    @else
                        <div class="px-8 py-4 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex flex-col gap-1 text-amber-600">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-clock"></i>
                                <span class="text-sm font-bold uppercase tracking-widest">อยู่ระหว่างรอพยานรับรอง</span>
                            </div>
                            <p class="text-[10px] opacity-70 italic font-medium ml-7">NDA จะเสร็จสมบูรณ์เมื่อพยานที่ระบุลงนามรับรองแล้ว</p>
                        </div>
                    @endif
                @endif
            </div>
        </form>
    </div>

    <!-- Witness Selection Modal -->
    <div x-show="modalOpen" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" 
        x-cloak>
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
            x-show="modalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="modalOpen = false"></div>

        <!-- Modal Content -->
        <div class="relative bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-2xl border border-slate-100 z-[101]"
            x-show="modalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 uppercase tracking-tight">เลือกพยาน <span x-text="selectedWitnessType"></span></h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ค้นหาพยานด้วยแผนกหรือชื่อ-นามสกุล</p>
                </div>
                <button @click="modalOpen = false" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-200 transition text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">กรองตามแผนก</label>
                        <select x-model="selectedDept" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">ทุกแผนก</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ค้นหาชื่อ-นามสกุล</label>
                        <div class="relative">
                            <input type="text" x-model="witnessSearch" placeholder="ระบุชื่อเพื่อค้นหา..." 
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-10 pr-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between px-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">รายชื่อพนักงาน (<span x-text="filteredUsers.length"></span>)</span>
                    </div>
                    
                    <div class="max-h-[350px] overflow-y-auto pr-2 custom-scrollbar space-y-2">
                        <template x-for="user in filteredUsers" :key="user.id">
                            <button type="button" @click="selectWitness(user)" 
                                class="w-full flex items-center p-4 bg-slate-50 hover:bg-blue-50 border border-slate-100 hover:border-blue-200 rounded-2xl transition group text-left">
                                <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-blue-600 font-bold text-xs shadow-sm mr-4 group-hover:scale-110 transition-transform" x-text="user.fullname.charAt(0)"></div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-slate-700 group-hover:text-blue-700" x-text="user.fullname"></p>
                                    <p class="text-[10px] text-slate-400 font-medium" x-text="user.department"></p>
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-circle-check text-blue-500"></i>
                                </div>
                            </button>
                        </template>
                        
                        <template x-if="filteredUsers.length === 0">
                            <div class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-user-slash text-4xl mb-4 opacity-20"></i>
                                <p class="text-sm font-medium">ไม่พบรายชื่อพนักงานที่ระบุ</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .prose { max-width: none !important; }
    .indent-12 { text-indent: 3rem; }
    canvas { touch-action: none; }
</style>

<script>



function ndaForm() {
    return {
        activePad: null,
        pads: {},
        modalOpen: false,
        selectedWitnessType: 1,
        selectedDept: '',
        witnessSearch: '',
        witness1_data: { id: '', fullname: '', department_name: '' },
        witness2_data: { id: '', fullname: '', department_name: '' },
        allUsers: @json($users),
        
        // Address Logic
        addressData: { provinces: [] },
        filteredAmphures: [],
        filteredTambons: [],
        
        
        isSigned: { employee: false, company: false, witness1: false, witness2: false },
        isTyped: { employee: false, company: false, witness1: false, witness2: false },
        typedName: { employee: '', company: '', witness1: '', witness2: '' },
        showErrors: {{ $errors->any() ? 'true' : 'false' }},
        formData: {
            prefix: '{{ old('prefix', $existing->prefix ?? '') }}',
            full_name: '{{ old('full_name', $existing->full_name ?? ($requestForm->firstname . ' ' . $requestForm->lastname)) }}',
            age: '{{ old('age', $existing->age ?? '') }}',
            id_card_no: '{{ old('id_card_no', $existing->id_card_no ?? '') }}',
            contact_no: '{{ old('contact_no', $existing->contact_no ?? $requestForm->tel) }}',
            address_no: '{{ old('address_no', $existing->address_no ?? '') }}',
            soi: '{{ old('soi', $existing->soi ?? '') }}',
            road: '{{ old('road', $existing->road ?? '') }}',
            tambon: '{{ old('tambon', $existing->tambon ?? '') }}',
            amphoe: '{{ old('amphoe', $existing->amphoe ?? '') }}',
            province: '{{ old('province', $existing->province ?? '') }}'
        },

        get filteredUsers() {
            return this.allUsers.filter(user => {
                const matchDept = !this.selectedDept || user.department_name === this.selectedDept;
                const matchSearch = !this.witnessSearch || 
                    user.fullname.toLowerCase().includes(this.witnessSearch.toLowerCase()) ||
                    (user.employee_code && user.employee_code.toLowerCase().includes(this.witnessSearch.toLowerCase()));
                return matchDept && matchSearch;
            });
        },

        openWitnessModal(type) {
            this.selectedWitnessType = type;
            this.modalOpen = true;
            this.witnessSearch = '';
        },

        selectWitness(user) {
            if (this.selectedWitnessType === 1) {
                if (this.witness2_data.id === user.id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'พยานซ้ำ',
                        text: 'พยานคนที่ 1 และคนที่ 2 ต้องไม่เป็นบุคคลเดียวกัน',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }
                this.witness1_data = { id: user.id, fullname: user.fullname, department_name: user.department_name };
            } else {
                if (this.witness1_data.id === user.id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'พยานซ้ำ',
                        text: 'พยานคนที่ 1 และคนที่ 2 ต้องไม่เป็นบุคคลเดียวกัน',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }
                this.witness2_data = { id: user.id, fullname: user.fullname, department_name: user.department_name };
            }
            this.modalOpen = false;
        },
        
        async init() {
            this.$nextTick(async () => {
                this.initPad('employee', 'signature-employee');
                this.initPad('company', 'signature-company');
                this.initPad('witness1', 'signature-witness1');
                this.initPad('witness2', 'signature-witness2');

                window.addEventListener('resize', () => {
                    this.initPad('employee', 'signature-employee');
                    this.initPad('company', 'signature-company');
                    this.initPad('witness1', 'signature-witness1');
                    this.initPad('witness2', 'signature-witness2');
                });

                // Restore Witness Selection from old()
                const oldW1 = '{{ old('witness1_user_id') }}';
                const oldW2 = '{{ old('witness2_user_id') }}';
                if (oldW1) {
                    const user = this.allUsers.find(u => u.id == oldW1);
                    if (user) this.witness1_data = { id: user.id, fullname: user.fullname, department_name: user.department_name };
                }
                if (oldW2) {
                    const user = this.allUsers.find(u => u.id == oldW2);
                    if (user) this.witness2_data = { id: user.id, fullname: user.fullname, department_name: user.department_name };
                }

                // Restore signatures from old() or auto-load system signature
                ['employee', 'company', 'witness1', 'witness2'].forEach(name => {
                    const input = document.getElementById(name + '_signature');
                    if (input && input.value) {
                        this.pads[name].fromDataURL(input.value);
                        this.isSigned[name] = true;
                    } else if (! @json($existing) && name === 'employee') {
                        // Auto-load employee system signature for new NDA
                        this.useSystemSignature('employee');
                    } else {
                        // Check if it's a witness/company turn and they have a system signature
                        const canSignWitness1 = {{ $canSignWitness1 ? 'true' : 'false' }};
                        const canSignWitness2 = {{ $canSignWitness2 ? 'true' : 'false' }};
                        const canSignCompany = {{ $canSignCompany ? 'true' : 'false' }};
                        
                        if (name === 'witness1' && canSignWitness1) this.useSystemSignature('witness1');
                        if (name === 'witness2' && canSignWitness2) this.useSystemSignature('witness2');
                        if (name === 'company' && canSignCompany) this.useSystemSignature('company');
                    }
                });

                // Fetch Address Data
                try {
                    const response = await fetch('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_province_with_amphure_tambon.json');
                    this.addressData.provinces = await response.json();
                    
                    // If existing data, trigger filters
                    if (this.formData.province) {
                        const province = this.addressData.provinces.find(p => p.name_th === this.formData.province);
                        if (province) {
                            this.filteredAmphures = province.amphure;
                            if (this.formData.amphoe) {
                                const amphure = this.filteredAmphures.find(a => a.name_th === this.formData.amphoe);
                                if (amphure) {
                                    this.filteredTambons = amphure.tambon;
                                }
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error loading address data:', error);
                }
            });
        },

        onProvinceChange() {
            const province = this.addressData.provinces.find(p => p.name_th === this.formData.province);
            this.filteredAmphures = province ? province.amphure : [];
            this.filteredTambons = [];
            this.formData.amphoe = '';
            this.formData.tambon = '';
        },

        onAmphureChange() {
            const amphure = this.filteredAmphures.find(a => a.name_th === this.formData.amphoe);
            this.filteredTambons = amphure ? amphure.tambon : [];
            this.formData.tambon = '';
        },

        onTambonChange() {
            const tambon = this.filteredTambons.find(t => t.name_th === this.formData.tambon);
            if (tambon && tambon.zip_code) {
                // If there's a zip code field, you could populate it here
            }
        },
        
        initPad(name, id) {
            const canvas = document.getElementById(id);
            if (!canvas) return;
            
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const offsetWidth = canvas.offsetWidth;
            const offsetHeight = canvas.offsetHeight;

            if (offsetWidth === 0 || offsetHeight === 0) {
                // If not visible yet, try again shortly
                setTimeout(() => this.initPad(name, id), 100);
                return;
            }

            canvas.width = offsetWidth * ratio;
            canvas.height = offsetHeight * ratio;
            canvas.getContext("2d").setTransform(1, 0, 0, 1, 0, 0); // Reset transform before scaling
            canvas.getContext("2d").scale(ratio, ratio);
            
            if (this.pads[name]) {
                const data = this.pads[name].toData();
                this.pads[name].clear();
                this.pads[name].fromData(data);
            } else {
                this.pads[name] = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(15, 23, 42)'
                });
                
                this.pads[name].addEventListener('beginStroke', () => { 
                    this.activePad = name; 
                    this.isSigned[name] = true; 
                });
            }
        },
        
        clearPad(name) {
            if (this.pads[name]) {
                this.pads[name].clear();
                this.isSigned[name] = false;
                this.isTyped[name] = false;
                this.typedName[name] = '';
                if (this.activePad === name) this.activePad = null;
            }
        },
        
        uploadToPad(name, event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.pads[name].fromDataURL(e.target.result, {
                    ratio: 1,
                    width: this.pads[name].canvas.width / window.devicePixelRatio,
                    height: this.pads[name].canvas.height / window.devicePixelRatio
                });
                this.isSigned[name] = true;
                this.activePad = name;
            };
            reader.readAsDataURL(file);
        },

        async useSystemSignature(name) {
            const signatureFile = "{{ Auth::user()->signature }}";
            if (!signatureFile) return;

            // Try local storage first, fallback to central AppKum server
            const localUrl = `{{ asset('storage/signatures/') }}/${encodeURIComponent(signatureFile)}`;
            const fallbackUrl = `https://appkum.kumwell.com/storage/signatures/${encodeURIComponent(signatureFile)}`;

            try {
                let response = await fetch(localUrl);
                let urlToUse = localUrl;

                if (!response.ok) {
                    console.log('Local signature not found, trying fallback...');
                    response = await fetch(fallbackUrl);
                    if (response.ok) {
                        urlToUse = fallbackUrl;
                    } else {
                        throw new Error('Signature not found in both local and fallback');
                    }
                }

                const blob = await response.blob();
                const reader = new FileReader();
                reader.onloadend = () => {
                    this.pads[name].fromDataURL(reader.result, {
                        ratio: 1,
                        width: this.pads[name].canvas.width / window.devicePixelRatio,
                        height: this.pads[name].canvas.height / window.devicePixelRatio
                    });
                    this.isSigned[name] = true;
                    this.activePad = name;
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'ดึงลายเซ็นสำเร็จ',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                };
                reader.readAsDataURL(blob);
            } catch (error) {
                console.error('Signature fetch error:', error);
                Swal.fire('ไม่พบไฟล์ลายเซ็น', 'กรุณาอัปโหลดรูปภาพลายเซ็น หรือลงลายมือชื่อใหม่ด้วยตนเอง', 'info');
            }
        },

        useNameAsSignature(name, fullname) {
            const pad = this.pads[name];
            if (!pad || !fullname) return;
            
            const canvas = pad.canvas;
            const ctx = canvas.getContext('2d');
            const ratio = window.devicePixelRatio || 1;
            
            pad.clear();
            
            // Reset transform to draw at physical pixel coordinates
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            
            // Set styles
            ctx.font = `700 ${24 * ratio}px 'Sarabun', sans-serif`;
            ctx.fillStyle = "rgb(15, 23, 42)";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            
            // Draw text at the exact physical center (Keep drawing so pad.toDataURL() works for backend)
            ctx.fillText(fullname, canvas.width / 2, canvas.height / 2);
            
            // Restore scale for future SignaturePad operations
            ctx.scale(ratio, ratio);
            
            this.isSigned[name] = true;
            this.isTyped[name] = true;
            this.typedName[name] = fullname;
            this.activePad = name;
        },

        async agreeCompany() {
            if (!this.isSigned.company) {
                Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: 'กรุณาลงลายมือชื่อ' });
                return;
            }

            const { value: confirm } = await Swal.fire({
                title: 'ยืนยันการลงนาม',
                text: "คุณยืนยันที่จะลงนามในนามบริษัทใช่หรือไม่?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            });

            if (confirm) {
                try {
                    const response = await fetch('{{ route('request.nda.company.agree', $requestForm->request_no) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ signature: this.pads.company.toDataURL() })
                    });
                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'ลงนามเรียบร้อยแล้ว' })
                            .then(() => window.location.reload());
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: 'เกิดข้อผิดพลาดในการบันทึก' });
                }
            }
        },

        async agreeWitness(witnessNo) {
            const padName = 'witness' + witnessNo;
            if (!this.isSigned[padName]) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาลงลายมือชื่อ',
                    text: 'คุณต้องลงลายมือชื่อเพื่อรับรองการเป็นพยาน',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            const result = await Swal.fire({
                title: 'ยืนยันการรับรอง?',
                text: "คุณยืนยันการเป็นพยานในข้อตกลงรักษาความลับนี้",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ยืนยันรับรอง',
                cancelButtonText: 'ยกเลิก'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`{{ route('request.nda.witness.agree', [$requestForm->request_no, ':witnessNo']) }}`.replace(':witnessNo', witnessNo), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            signature: this.pads[padName].toDataURL()
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'รับรองเรียบร้อยแล้ว',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                }
            }
        },
        
        submitForm() {
            // Validate
            const isWitnessMode = ! @json($existing);
            
            // Personal Info Validation
            const personalFields = ['prefix', 'full_name', 'age', 'id_card_no', 'contact_no', 'address_no', 'tambon', 'amphoe', 'province'];
            let isPersonalValid = true;
            personalFields.forEach(field => {
                if (!this.formData[field]) isPersonalValid = false;
            });

            const hasEmployeeSign = this.isSigned.employee;
            const hasWitness1 = isWitnessMode ? !!this.witness1_data.id : true;
            const hasWitness2 = true; // Witness 2 is now optional

            if (!isPersonalValid || !hasEmployeeSign || !hasWitness1 || !hasWitness2) {
                this.showErrors = true;
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อมูลไม่ครบถ้วน!',
                    text: 'กรุณาตรวจสอบและระบุข้อมูลในช่องที่มีสีแดงให้ครบถ้วน',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'ตกลง',
                    customClass: {
                        popup: 'rounded-[2rem]',
                        confirmButton: 'rounded-xl font-bold px-8'
                    }
                });
                return;
            }
            
            this.showErrors = false;
            
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
                text: "ข้อมูลนี้จะถูกเก็บรักษาไว้เป็นหลักฐานสำคัญตามระเบียบขององค์กร",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ยืนยันและส่งข้อมูล',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl font-bold',
                    cancelButton: 'rounded-xl font-bold'
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
