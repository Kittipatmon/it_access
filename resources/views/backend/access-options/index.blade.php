@extends('layouts.admin')

@section('breadcrumb', 'จัดการรายการเข้าถึง')

@section('content')
<div class="space-y-8" x-data="{ 
    showAddSystem: {{ $errors->hasAny(['name', 'key']) && old('category') === 'system' ? 'true' : 'false' }}, 
    showAddProgram: {{ $errors->hasAny(['name', 'key']) && old('category') === 'program' ? 'true' : 'false' }},
    editId: null,
    editName: '',
    editKey: '',
    editHasSub: false,
    editSubList: '',
    editSubType: 'radio',
    editSort: 0,
    openEdit(opt) {
        this.editId = opt.id;
        this.editName = opt.name;
        this.editKey = opt.key;
        this.editHasSub = !!opt.has_sub_options;
        this.editSubList = opt.sub_options ? opt.sub_options.join(', ') : '';
        this.editSubType = opt.sub_option_type || 'radio';
        this.editSort = opt.sort_order;
    }
}">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">จัดการรายการเข้าถึง (ส่วนที่ 2)</h2>
            <p class="text-sm text-slate-400">เพิ่ม ลบ แก้ไข ตัวเลือกการเข้าถึงระบบและโปรแกรมในแบบฟอร์มร้องขอ</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm animate-fade-in">
        <svg class="h-5 w-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- ===== การเข้าถึงระบบ ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-blue-50/50 to-white">
                <h3 class="text-base font-bold text-slate-700 flex items-center gap-2">
                    <span class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    การเข้าถึงระบบ
                </h3>
                <button @click="showAddSystem = !showAddSystem" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition shadow-sm active:scale-95">
                    + เพิ่มรายการ
                </button>
            </div>

            {{-- Add Form --}}
            <div x-show="showAddSystem" x-transition class="p-5 bg-blue-50/30 border-b border-slate-100">
                <form action="{{ route('backend.access-options.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="category" value="system">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1.5 block">ชื่อรายการ</label>
                            <input type="text" name="name" value="{{ old('category') === 'system' ? old('name') : '' }}" required placeholder="เช่น VPN Access" 
                                class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                            @error('name') <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1.5 block">Key (Identifier)</label>
                            <input type="text" name="key" value="{{ old('category') === 'system' ? old('key') : '' }}" required placeholder="เช่น vpn_access" 
                                class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('key') border-red-500 @enderror">
                            @error('key') <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div x-data="{ hasSub: {{ old('has_sub_options') ? 'true' : 'false' }} }">
                        <label class="inline-flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="has_sub_options" x-model="hasSub" value="1" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition">มีตัวเลือกย่อย (Sub-options)</span>
                        </label>
                        <div x-show="hasSub" x-transition class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4 animate-slide-down">
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-1 block uppercase">ตัวเลือกย่อย (คั่นด้วย ,)</label>
                                <input type="text" name="sub_options_list" value="{{ old('sub_options_list') }}" placeholder="Admin, User, Viewer" 
                                    class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-1 block uppercase">ลักษณะการเลือก</label>
                                <select name="sub_option_type" class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="radio" {{ old('sub_option_type') === 'radio' ? 'selected' : '' }}>เลือกได้ 1 (Radio)</option>
                                    <option value="checkbox" {{ old('sub_option_type') === 'checkbox' ? 'selected' : '' }}>เลือกได้หลายตัว (Checkbox)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-sm">บันทึกรายการ</button>
                        <button type="button" @click="showAddSystem = false" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition">ยกเลิก</button>
                    </div>
                </form>
            </div>

            {{-- List --}}
            <div class="divide-y divide-slate-100">
                @forelse($systemOptions as $opt)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 transition group">
                    <div class="flex items-center space-x-4 flex-1">
                        <span class="w-8 h-8 bg-slate-50 text-slate-400 border border-slate-100 rounded-xl flex items-center justify-center text-xs font-black">{{ $opt->sort_order }}</span>
                        <div>
                            <p class="text-sm font-bold text-slate-700">{{ $opt->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] text-slate-400 font-mono bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100 tracking-tighter">{{ $opt->key }}</span>
                                @if($opt->has_sub_options && $opt->sub_options)
                                    <span class="text-[10px] text-blue-500 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100 font-medium">
                                        {{ implode(', ', $opt->sub_options) }}
                                    </span>
                                @endif
                                @if(!$opt->is_active)
                                    <span class="text-[10px] text-red-500 bg-red-50 px-2 py-0.5 rounded-lg border border-red-100 font-black uppercase">Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition duration-200">
                        <button @click="openEdit({{ $opt->toJson() }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition" title="แก้ไข">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <form action="{{ route('backend.access-options.toggle', $opt->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 {{ $opt->is_active ? 'text-green-500 hover:text-orange-500' : 'text-slate-300 hover:text-green-500' }} hover:bg-slate-50 rounded-xl transition" title="{{ $opt->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $opt->is_active ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/></svg>
                            </button>
                        </form>
                        <form action="{{ route('backend.access-options.destroy', $opt->id) }}" method="POST" class="inline" onsubmit="return confirm('ต้องการลบรายการนี้? ข้อมูลในคำร้องเก่าอาจแสดงผลผิดพลาด')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition" title="ลบ">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="bg-slate-50 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 4-8-4"/></svg>
                    </div>
                    <p class="text-sm text-slate-400 font-medium">ยังไม่มีรายการเข้าถึงระบบ</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ===== การเข้าถึงโปรแกรม ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-orange-50/50 to-white">
                <h3 class="text-base font-bold text-slate-700 flex items-center gap-2">
                    <span class="p-1.5 bg-orange-100 text-orange-600 rounded-lg">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    การเข้าถึงโปรแกรม
                </h3>
                <button @click="showAddProgram = !showAddProgram" class="px-3 py-1.5 bg-orange-500 text-white text-xs font-bold rounded-lg hover:bg-orange-600 transition shadow-sm active:scale-95">
                    + เพิ่มรายการ
                </button>
            </div>

            {{-- Add Form --}}
            <div x-show="showAddProgram" x-transition class="p-5 bg-orange-50/30 border-b border-slate-100">
                <form action="{{ route('backend.access-options.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="category" value="program">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1.5 block">ชื่อรายการ</label>
                            <input type="text" name="name" value="{{ old('category') === 'program' ? old('name') : '' }}" required placeholder="เช่น Power BI" 
                                class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                            @error('name') <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1.5 block">Key (Identifier)</label>
                            <input type="text" name="key" value="{{ old('category') === 'program' ? old('key') : '' }}" required placeholder="เช่น power_bi" 
                                class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('key') border-red-500 @enderror">
                            @error('key') <p class="mt-1 text-[10px] text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div x-data="{ hasSub: {{ old('has_sub_options') ? 'true' : 'false' }} }">
                        <label class="inline-flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="has_sub_options" x-model="hasSub" value="1" class="w-4 h-4 rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition">มีตัวเลือกย่อย (Sub-options)</span>
                        </label>
                        <div x-show="hasSub" x-transition class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4 animate-slide-down">
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-1 block uppercase">ตัวเลือกย่อย (คั่นด้วย ,)</label>
                                <input type="text" name="sub_options_list" value="{{ old('sub_options_list') }}" placeholder="Pro, Logi, Fin" 
                                    class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-1 block uppercase">ลักษณะการเลือก</label>
                                <select name="sub_option_type" class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500">
                                    <option value="radio">เลือกได้ 1 (Radio)</option>
                                    <option value="checkbox">เลือกได้หลายตัว (Checkbox)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="px-5 py-2 bg-orange-500 text-white text-sm font-bold rounded-xl hover:bg-orange-600 transition shadow-sm">บันทึกรายการ</button>
                        <button type="button" @click="showAddProgram = false" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition">ยกเลิก</button>
                    </div>
                </form>
            </div>

            {{-- List --}}
            <div class="divide-y divide-slate-100">
                @forelse($programOptions as $opt)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 transition group">
                    <div class="flex items-center space-x-4 flex-1">
                        <span class="w-8 h-8 bg-slate-50 text-slate-400 border border-slate-100 rounded-xl flex items-center justify-center text-xs font-black">{{ $opt->sort_order }}</span>
                        <div>
                            <p class="text-sm font-bold text-slate-700">{{ $opt->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] text-slate-400 font-mono bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100 tracking-tighter">{{ $opt->key }}</span>
                                @if($opt->has_sub_options && $opt->sub_options)
                                    <span class="text-[10px] text-orange-500 bg-orange-50 px-2 py-0.5 rounded-lg border border-orange-100 font-medium">
                                        {{ implode(', ', $opt->sub_options) }}
                                    </span>
                                @endif
                                @if(!$opt->is_active)
                                    <span class="text-[10px] text-red-500 bg-red-50 px-2 py-0.5 rounded-lg border border-red-100 font-black uppercase">Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition duration-200">
                        <button @click="openEdit({{ $opt->toJson() }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition" title="แก้ไข">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <form action="{{ route('backend.access-options.toggle', $opt->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 {{ $opt->is_active ? 'text-green-500 hover:text-orange-500' : 'text-slate-300 hover:text-green-500' }} hover:bg-slate-50 rounded-xl transition" title="{{ $opt->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $opt->is_active ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/></svg>
                            </button>
                        </form>
                        <form action="{{ route('backend.access-options.destroy', $opt->id) }}" method="POST" class="inline" onsubmit="return confirm('ต้องการลบรายการนี้? ข้อมูลในคำร้องเก่าอาจแสดงผลผิดพลาด')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition" title="ลบ">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="bg-slate-50 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 4-8-4"/></svg>
                    </div>
                    <p class="text-sm text-slate-400 font-medium">ยังไม่มีรายการเข้าถึงโปรแกรม</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== Edit Modal ===== --}}
    <template x-if="editId !== null">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="editId = null"></div>
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 relative animate-scale-in" @click.stop>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-slate-800">แก้ไขข้อมูลรายการ</h3>
                    <button @click="editId = null" class="p-2 hover:bg-slate-100 rounded-full transition text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form :action="'/backend/access-options/' + editId" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1.5 block uppercase tracking-wider">ชื่อรายการ</label>
                            <input type="text" name="name" x-model="editName" required 
                                class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1.5 block uppercase tracking-wider">Key (Identifier)</label>
                            <input type="text" name="key" x-model="editKey" required 
                                class="w-full border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block uppercase tracking-wider">ลำดับการแสดงผล</label>
                        <input type="number" name="sort_order" x-model="editSort" min="1" 
                            class="w-32 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <label class="inline-flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox" name="has_sub_options" x-model="editHasSub" value="1" class="w-4 h-4 rounded border-slate-300 text-blue-600">
                            <span class="text-sm font-bold text-slate-700">เปิดใช้งานตัวเลือกย่อย</span>
                        </label>
                        
                        <div x-show="editHasSub" x-transition class="mt-4 grid grid-cols-1 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-1 block uppercase">รายการย่อย (คั่นด้วยจุลภาค)</label>
                                <input type="text" name="sub_options_list" x-model="editSubList" placeholder="เช่น Low, Medium, High" 
                                    class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 mb-1 block uppercase">ลักษณะการเลือก</label>
                                <select name="sub_option_type" x-model="editSubType" class="w-full border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="radio">เลือกได้ 1 (Radio)</option>
                                    <option value="checkbox">เลือกได้หลายตัว (Checkbox)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="editId = null" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition">ยกเลิก</button>
                        <button type="submit" class="px-8 py-2.5 bg-slate-900 text-white text-sm font-black rounded-xl hover:bg-slate-800 transition shadow-lg shadow-slate-200">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<style>
    @keyframes slide-down {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-slide-down { animation: slide-down 0.2s ease-out; }
    .animate-scale-in { animation: scale-in 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
</style>
@endsection
