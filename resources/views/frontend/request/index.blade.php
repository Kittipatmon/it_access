@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200">
            <!-- Header -->
            <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="flex items-center gap-4">
                        <div class="text-3xl font-bold text-[#E10023] tracking-tighter select-none mr-2" style="font-family: 'Outfit', 'Inter', sans-serif;">Kumwell</div>
                        <div>
                            <h2 class="text-lg md:text-xl font-semibold text-slate-800">แบบฟอร์มการร้องขอสิทธิใช้งานเทคโนโลยีสารสนเทศ</h2>
                            <p class="text-[10px] md:text-sm text-slate-500 mt-1">QF-IT-08: Rev: 02</p>
                        </div>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-[10px] md:text-xs text-slate-400">Req.No. จะถูกสร้างอัตโนมัติ</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('request.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8" x-data="formData()" @submit="handleSubmit($event)">
                @csrf

                {{-- ===== ส่วนที่ 1: ผู้ร้องขอ ===== --}}
                <div class="space-y-5">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-l-4 border-blue-500 pl-3">
                        ส่วนที่ 1 ผู้ร้องขอ
                    </h3>

                    <!-- ประเภทคำร้อง -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">ประเภทคำร้อง</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach([
                                    'new_employee' => 'พนักงานใหม่',
                                    'resign' => 'ลาออก',
                                    'position_change' => 'ปรับตำแหน่ง',
                                    'transfer' => 'โอนย้าย',
                                    'add_remove_access' => 'เพิ่มสิทธิ์/ลบสิทธิ์',
                                ] as $value => $label)
                                <label class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 cursor-pointer hover:border-blue-300 transition"
                                    :class="{
                                        'border-blue-500 bg-blue-50 text-blue-700 ring-1 ring-blue-500': request_type === '{{ $value }}',
                                        'border-red-500 bg-red-50/30 text-red-600': showErrors && !request_type,
                                        'bg-white text-slate-600': request_type !== '{{ $value }}' && !(showErrors && !request_type)
                                    }">
                                    <input type="radio" name="request_type" value="{{ $value }}" x-model="request_type" class="sr-only" {{ old('request_type') == $value ? 'checked' : '' }}>
                                    <span class="text-sm font-medium">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('request_type') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                        <template x-if="showErrors && !request_type">
                            <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณาเลือกประเภทคำร้อง</p>
                        </template>
                    </div>

                    <!-- ข้อมูลส่วนตัว (ภาษาไทย) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ชื่อ (ภาษาไทย)</label>
                            <input type="text" name="firstname" x-model="firstname"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('firstname') border-red-500 @enderror"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !firstname}">
                            @error('firstname') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            <template x-if="showErrors && !firstname">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกชื่อ (ภาษาไทย)</p>
                            </template>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">นามสกุล (ภาษาไทย)</label>
                            <input type="text" name="lastname" x-model="lastname"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 font-medium focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('lastname') border-red-500 @enderror"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !lastname}">
                            @error('lastname') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            <template x-if="showErrors && !lastname">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกนามสกุล (ภาษาไทย)</p>
                            </template>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ชื่อเล่น</label>
                            <input type="text" name="nickname_th" x-model="nickname_th"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !nickname_th}">
                            <template x-if="showErrors && !nickname_th">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกชื่อเล่น (ภาษาไทย)</p>
                            </template>
                        </div>
                    </div>

                    <!-- ข้อมูลส่วนตัว (ภาษาอังกฤษ) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Name (English)</label>
                            <input type="text" name="firstname_en" x-model="firstname_en"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('firstname_en') border-red-500 @enderror"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !firstname_en}">
                            @error('firstname_en') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            <template x-if="showErrors && !firstname_en">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกชื่อ (English)</p>
                            </template>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Last Name (English)</label>
                            <input type="text" name="lastname_en" x-model="lastname_en"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('lastname_en') border-red-500 @enderror"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !lastname_en}">
                            @error('lastname_en') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            <template x-if="showErrors && !lastname_en">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกนามสกุล (English)</p>
                            </template>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nick Name (English)</label>
                            <input type="text" name="nickname_en" x-model="nickname_en"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !nickname_en}">
                            <template x-if="showErrors && !nickname_en">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกชื่อเล่น (English)</p>
                            </template>
                        </div>
                    </div>

                    <!-- โทรศัพท์ + รหัสพนักงาน -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">โทรศัพท์</label>
                            <input type="text" name="phone" x-model="phone"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !phone}">
                            <template x-if="showErrors && !phone">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกเบอร์โทรศัพท์</p>
                            </template>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ภายใน (Ext.)</label>
                            <input type="text" name="phone_ext" value="{{ old('phone_ext') }}"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">รหัสพนักงาน</label>
                            <input type="text" name="emp_code" x-model="emp_code"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('emp_code') border-red-500 @enderror"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !emp_code}">
                            @error('emp_code') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            <template x-if="showErrors && !emp_code">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกรหัสพนักงาน</p>
                            </template>
                        </div>
                    </div>

                    <!-- ระดับ -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">ระดับ</label>
                        <div class="flex flex-wrap gap-4 items-center">
                            @foreach([
                                    'executive' => 'ผู้บริหาร',
                                    'department_head' => 'หัวหน้าแผนก',
                                    'employee' => 'พนักงานทั่วไป',
                                    'other' => 'อื่น',
                                ] as $value => $label)
                                <label class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 cursor-pointer hover:border-blue-300 transition"
                                    :class="{
                                        'border-blue-500 bg-blue-50 text-blue-700 ring-1 ring-blue-500': position_level === '{{ $value }}',
                                        'border-red-500 bg-red-50/30 text-red-600': showErrors && !position_level,
                                        'bg-white text-slate-600': position_level !== '{{ $value }}' && !(showErrors && !position_level)
                                    }">
                                    <input type="radio" name="position_level" value="{{ $value }}" x-model="position_level" class="sr-only" {{ old('position_level') == $value ? 'checked' : '' }}>
                                    <span class="text-sm font-medium">{{ $label }}</span>
                                </label>
                            @endforeach
                            <div x-show="position_level === 'other'" x-transition class="flex-1 min-w-[180px]">
                                <input type="text" name="position_level_other" value="{{ old('position_level_other') }}" placeholder="กรุณาระบุระดับ..."
                                    class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('position_level_other') border-red-500 @enderror">
                                @error('position_level_other') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                                <template x-if="showErrors && position_level === 'other' && !position_level_other">
                                    <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณาระบุระดับ</p>
                                </template>
                            </div>
                        </div>
                        @error('position_level') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                        <template x-if="showErrors && !position_level">
                            <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณาเลือกระดับพนักงาน</p>
                        </template>
                    </div>

                    <!-- ตำแหน่ง + แผนก + ฝ่าย -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ตำแหน่ง</label>
                            <input type="text" name="position_name" x-model="position_name"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('position_name') border-red-500 @enderror"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !position_name}">
                            @error('position_name') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            <template x-if="showErrors && !position_name">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกตำแหน่ง</p>
                            </template>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">แผนก</label>
                            <input type="text" name="department_name" x-model="department_name"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('department_name') border-red-500 @enderror"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !department_name}">
                            @error('department_name') <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            <template x-if="showErrors && !department_name">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกแผนก</p>
                            </template>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">ฝ่าย</label>
                            <input type="text" name="division_name" x-model="division_name"
                                class="block w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                :class="{'border-red-500 bg-red-50/30': showErrors && !division_name}">
                            <template x-if="showErrors && !division_name">
                                <p class="text-[10px] text-red-500 font-bold mt-1.5 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> กรุณากรอกฝ่าย</p>
                            </template>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                {{-- ===== ส่วนที่ 2: การเข้าถึง (Dynamic Grid Layout) ===== --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-l-4 border-blue-500 pl-3">
                        ส่วนที่ 2 การเข้าถึง
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($groupedOptions as $categoryKey => $options)
                            <div class="bg-slate-50/50 rounded-2xl border border-slate-100 p-5 space-y-4 flex flex-col">
                                <h4 class="text-sm font-bold text-slate-700 pb-3 border-b border-slate-200 flex items-center gap-2">
                                    <div class="text-blue-600">
                                        @if($categoryKey === 'system')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        @elseif($categoryKey === 'program')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @elseif($categoryKey === 'equipment')
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        @endif
                                    </div>
                                    {{ $categoryLabels[$categoryKey] ?? ucfirst($categoryKey) }}
                                </h4>

                                <div class="space-y-3 flex-1">
                                    @foreach($options as $item)
                                        <div>
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <div class="relative flex items-center">
                                                    <input type="checkbox" name="{{ $categoryKey }}_access[]" value="{{ $item->key }}" 
                                                        x-model="access_selections['{{ $categoryKey }}']"
                                                        class="w-4.5 h-4.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all">
                                                </div>
                                                <div class="flex-1">
                                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition font-medium">{{ $item->name }}</span>
                                                    
                                                    {{-- Sub Options (Show if exists) --}}
                                                    @if($item->has_sub_options && !empty($item->sub_options))
                                                        <div class="mt-2 flex flex-wrap gap-1.5" x-show="access_selections['{{ $categoryKey }}'].includes('{{ $item->key }}')" x-transition>
                                                            @foreach($item->sub_options as $sub)
                                                                <label class="cursor-pointer">
                                                                    <input type="{{ $item->sub_option_type ?? 'radio' }}" 
                                                                        name="sub_options[{{ $item->key }}]{{ ($item->sub_option_type ?? 'radio') === 'checkbox' ? '[]' : '' }}" 
                                                                        value="{{ $sub }}" class="sr-only peer">
                                                                    <span class="px-2 py-1 rounded border border-slate-100 bg-white text-[10px] font-bold text-slate-500 peer-checked:border-blue-500 peer-checked:bg-blue-600 peer-checked:text-white transition-all block">
                                                                        {{ $sub }}
                                                                    </span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                    
                                    {{-- Other Option (Standardized) --}}
                                    <div x-data="{ isOther: false }">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" x-model="isOther" class="w-4.5 h-4.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all">
                                            <span class="text-sm text-slate-500 group-hover:text-slate-700 transition font-medium italic">Other</span>
                                        </label>
                                        <div class="mt-2" x-show="isOther" x-transition>
                                            <input type="text" name="{{ $categoryKey }}_access_other" 
                                                class="w-full border-slate-200 rounded-lg text-xs px-3 py-2 focus:ring-1 focus:ring-blue-500 bg-white shadow-sm"
                                                placeholder="ระบุรายการอื่นๆ...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-slate-100">

                {{-- ===== รายละเอียดเพิ่มเติม ===== --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-l-4 border-blue-500 pl-3">รายละเอียดเพิ่มเติม</h3>
                    <textarea id="details" name="details" rows="3"
                        class="block w-full bg-slate-50 border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                        placeholder="ระบุรายละเอียดเพิ่มเติม (ถ้ามี)...">{{ old('details') }}</textarea>
                </div>

                {{-- ===== ลายเซ็นผู้ร้องขอ ===== --}}
                <div class="space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest border-l-4 border-blue-500 pl-3">ลายมือชื่อผู้ร้องขอ</h3>
                        
                        <div class="inline-flex p-1 bg-slate-100 rounded-2xl border border-slate-200">
                            <button type="button" @click="signature_method = 'none'" 
                                :class="signature_method === 'none' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>ไม่ระบุ</span>
                            </button>
                            @if(Auth::user()->signature)
                            <button type="button" @click="signature_method = 'existing'" 
                                :class="signature_method === 'existing' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>ใช้ของเดิม</span>
                            </button>
                            @endif
                            <button type="button" @click="signature_method = 'draw'; $nextTick(() => { window.resizeCanvas(); })" 
                                :class="signature_method === 'draw' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <span>วาดใหม่</span>
                            </button>
                            <button type="button" @click="signature_method = 'upload'" 
                                :class="signature_method === 'upload' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/xl" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span>อัปโหลดรูป</span>
                            </button>
                        </div>
                    </div>

                    <!-- Method: None -->
                    <div x-show="signature_method === 'none'" x-transition class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center italic text-slate-400 text-sm">
                        ไม่มีการระบุลายมือชื่อ (สามารถส่งคำร้องได้)
                    </div>

                    <!-- Method: Draw -->
                    <div x-show="signature_method === 'draw'" x-transition class="relative bg-slate-50 border-2 border-slate-200 rounded-3xl overflow-hidden mb-2">
                        <canvas id="signature-pad" class="w-full h-48 touch-none cursor-crosshair" style="min-height: 192px;"></canvas>
                        <button type="button" id="clear-signature" class="absolute top-3 right-3 p-2 bg-white shadow-md border border-slate-200 rounded-xl text-slate-400 hover:text-red-500 transition active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                        <input type="hidden" name="signature" id="signature-input">
                        <p class="text-xs text-slate-400 italic text-center mt-2">กรุณาลงลายมือชื่อผู้ร้องขอในช่องว่างด้านบน</p>
                    </div>

                    <!-- Method: Existing -->
                    @if(Auth::user()->signature)
                    <div x-show="signature_method === 'existing'" x-transition>
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 flex flex-col items-center">
                            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm inline-block">
                                <img src="{{ asset('storage/signatures/' . Auth::user()->signature) }}" alt="Existing Signature" class="h-24 w-auto">
                            </div>
                            <input type="hidden" name="existing_signature" value="{{ Auth::user()->signature }}" :disabled="signature_method !== 'existing'">
                            <p class="text-xs text-slate-400 mt-4 italic">ใช้ลายเซ็นที่บันทึกไว้ในระบบ</p>
                        </div>
                    </div>
                    @endif

                    <!-- Method: Upload -->
                    <div x-show="signature_method === 'upload'" x-transition x-data="{ fileName: '', previewUrl: null }">
                        <div class="relative group">
                            <input type="file" name="signature_file" id="signature_file" accept="image/*" class="hidden"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        fileName = file.name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => previewUrl = e.target.result;
                                        reader.readAsDataURL(file);
                                    }
                                ">
                            <label for="signature_file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-slate-300 rounded-2xl bg-slate-50 hover:bg-slate-100 hover:border-blue-400 transition-all cursor-pointer">
                                <template x-if="!previewUrl">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-slate-400 group-hover:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-slate-500"><span class="font-semibold text-blue-600">คลิกเพื่ออัปโหลด</span> หรือลากรูปมาวาง</p>
                                        <p class="text-xs text-slate-400">PNG, JPG หรือ JPEG (แนะนำขนาดไม่เกิน 2MB)</p>
                                    </div>
                                </template>
                                <template x-if="previewUrl">
                                    <div class="relative p-4 flex flex-col items-center">
                                        <button type="button" @click.stop.prevent="previewUrl = null; fileName = ''; document.getElementById('signature_file').value = ''" 
                                            class="absolute -top-2 -right-2 p-1.5 bg-red-500 text-white rounded-full shadow-lg hover:bg-red-600 transition z-20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <img :src="previewUrl" class="h-32 w-auto object-contain mb-2 rounded-lg">
                                        <p class="text-[10px] text-blue-500 font-bold" x-text="fileName"></p>
                                    </div>
                                </template>
                            </label>
                        </div>
                    </div>

                    @error('signature') <p class="text-red-500 text-xs mt-1 text-center font-bold">{{ $message }}</p> @enderror
                    @error('signature_file') <p class="text-red-500 text-xs mt-1 text-center font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit" :disabled="loading"
                        class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-blue-200 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">ส่งคำร้องขอสิทธิ</span>
                        <span x-show="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            กำลังดำเนินการ...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        let signaturePad;
        
        function formData() {
            return {
                loading: false,
                showErrors: false,
                request_type: '{{ old('request_type', '') }}',
                firstname: '{{ old('firstname', $user->firstname) }}',
                lastname: '{{ old('lastname', $user->lastname) }}',
                nickname_th: '{{ old('nickname_th', $user->nickname_th) }}',
                firstname_en: '{{ old('firstname_en', $user->first_name_en) }}',
                lastname_en: '{{ old('lastname_en', $user->last_name_en) }}',
                nickname_en: '{{ old('nickname_en', $user->nickname_en) }}',
                phone: '{{ old('phone', $user->phone) }}',
                emp_code: '{{ old('emp_code', $user->emp_code) }}',
                position_level: '{{ old('position_level', '') }}',
                position_name: '{{ old('position_name', '') }}',
                department_name: '{{ old('department_name', $user->department_rel->name ?? '') }}',
                division_name: '{{ old('division_name', '') }}',
                access_selections: {
                    @foreach($groupedOptions as $cat => $items)
                    '{{ $cat }}': [],
                    @endforeach
                },
                use_existing_signature: {{ Auth::user()->signature ? 'true' : 'false' }},
                signature_method: '{{ old('signature_method', (Auth::user()->signature ? 'existing' : 'none')) }}',

                init() {
                    const canvas = document.getElementById('signature-pad');
                    if (canvas) {
                        signaturePad = new SignaturePad(canvas, {
                            backgroundColor: 'rgba(255, 255, 255, 0)',
                            penColor: 'rgb(30, 41, 59)'
                        });
                        
                        window.resizeCanvas = () => {
                            const ratio = Math.max(window.devicePixelRatio || 1, 1);
                            const width = canvas.offsetWidth;
                            const height = canvas.offsetHeight;

                            if (width > 0 && height > 0) {
                                // Save current signature
                                const data = signaturePad.toData();
                                
                                canvas.width = width * ratio;
                                canvas.height = height * ratio;
                                const ctx = canvas.getContext("2d");
                                ctx.setTransform(1, 0, 0, 1, 0, 0);
                                ctx.scale(ratio, ratio);
                                signaturePad.clear();
                                
                                // Restore signature
                                signaturePad.fromData(data);
                            }
                        };

                        window.addEventListener("resize", window.resizeCanvas);
                        setTimeout(window.resizeCanvas, 100);
                        setTimeout(window.resizeCanvas, 500);
                        setTimeout(window.resizeCanvas, 1000);

                        document.getElementById('clear-signature').addEventListener('click', () => {
                            signaturePad.clear();
                        });
                    }
                },

                handleSubmit(e) {
                    this.showErrors = true;
                    
                    // Basic validation check
                    const required = [
                        this.request_type, this.firstname, this.lastname, this.nickname_th,
                        this.firstname_en, this.lastname_en, this.nickname_en,
                        this.phone, this.emp_code,
                        this.position_level, this.position_name, this.department_name, this.division_name
                    ];
                    
                    if (required.some(val => !val)) {
                        e.preventDefault();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        Swal.fire({
                            icon: 'error',
                            title: 'ข้อมูลส่วนตัวไม่ครบถ้วน',
                            text: 'กรุณากรอกข้อมูลในช่องที่ระบุสีแดงให้ครบถ้วน',
                            confirmButtonColor: '#3b82f6',
                        });
                        return false;
                    }

                    // Dynamic validation
                    const hasSelection = Object.values(this.access_selections).some(arr => arr.length > 0);
                    if (!hasSelection) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'ยังไม่ได้เลือกการเข้าถึง',
                            text: 'กรุณาเลือกอย่างน้อย 1 รายการในหมวดหมู่ต่างๆ',
                            confirmButtonColor: '#3b82f6',
                        });
                        return false;
                    }

                    if (this.signature_method === 'draw') {
                        if (signaturePad.isEmpty()) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'กรุณาลงลายมือชื่อ',
                                text: 'กรุณาวาดลายมือชื่อของท่านในช่องที่กำหนด',
                                confirmButtonColor: '#3b82f6',
                            });
                            return false;
                        }
                        document.getElementById('signature-input').value = signaturePad.toDataURL();
                    }
                    
                    this.loading = true;
                    return true;
                }
            }
        }
    </script>
@endsection
