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
                                                editCustomFields: '',
                                                editSort: 0,
                                                openEdit(opt) {
                                                    this.editId = opt.id;
                                                    this.editName = opt.name;
                                                    this.editKey = opt.key;
                                                    this.editHasSub = !!opt.has_sub_options;
                                                    this.editSubList = opt.sub_options ? opt.sub_options.join(', ') : '';
                                                    this.editSubType = opt.sub_option_type || 'radio';
                                                    this.editCustomFields = opt.custom_fields ? opt.custom_fields.join(', ') : '';
                                                    this.editSort = opt.sort_order;
                                                }
                                            }">
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-6 border-b border-slate-200">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">จัดการรายการเข้าถึง (Access Options)</h2>
                <p class="text-sm text-slate-500 mt-1">กำหนดรายการระบบและโปรแกรมที่อนุญาตให้ขอสิทธิเข้าถึงในแบบฟอร์ม</p>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Card 1: Systems --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-700">รายการเข้าถึงระบบ (Systems)</h3>
                    </div>
                    <button @click="showAddSystem = !showAddSystem"
                        class="text-xs font-bold text-blue-600 hover:text-blue-700 transition flex items-center gap-1">
                        <span x-text="showAddSystem ? 'ปิดฟอร์ม' : '+ เพิ่มรายการ'"></span>
                    </button>
                </div>

                {{-- Add Form Inline --}}
                <div x-show="showAddSystem" x-transition class="p-6 bg-slate-50 border-b border-slate-200">
                    <form action="{{ route('backend.access-options.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="category" value="system">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ชื่อรายการ</label>
                                <input type="text" name="name" required
                                    class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700 placeholder:text-slate-400"
                                    placeholder="ระบุชื่อรายการ...">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Key (ID)</label>
                                <input type="text" name="key" required
                                    class="w-full border-slate-300 rounded-lg text-sm font-mono px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700 placeholder:text-slate-400"
                                    placeholder="KEY_NAME">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ช่องกรอกข้อมูลเพิ่มเติม
                                (คั่นด้วย ,) เช่น Username, Password</label>
                            <input type="text" name="custom_fields_list"
                                class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700 placeholder:text-slate-400"
                                placeholder="เช่น USERNAME, PASSWORD">
                        </div>

                        <div x-data="{ hasSub: false }" class="bg-slate-100/50 p-4 rounded-lg border border-slate-200">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="has_sub_options" x-model="hasSub" value="1"
                                    class="rounded border-slate-300 text-blue-600">
                                <span class="text-xs font-bold text-slate-700">เพิ่มหัวข้อย่อย / สิทธิการใช้งาน
                                    (Sub-options)</span>
                            </label>

                            <div x-show="hasSub" x-transition
                                class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">รายการย่อย
                                        (เช่น Read, Write, Full)</label>
                                    <input type="text" name="sub_options_list"
                                        class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700 placeholder:text-slate-400"
                                        placeholder="เช่น READ, WRITE, FULL">
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ประเภทการเลือก</label>
                                    <select name="sub_option_type"
                                        class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700">
                                        <option value="radio">เลือกได้ 1 อย่าง (Radio)</option>
                                        <option value="checkbox">เลือกได้หลายอย่าง (Checkbox)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 py-2">
                            <button type="submit"
                                class="bg-slate-800 text-white px-6 py-2 rounded-lg text-xs font-bold hover:bg-slate-900 transition shadow-sm">บันทึกรายการ</button>
                            <button type="button" @click="showAddSystem = false"
                                class="text-xs text-slate-500 hover:text-slate-700">ยกเลิก</button>
                        </div>
                    </form>
                </div>

                <div class="flex-1">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3 w-16">#</th>
                                <th class="px-6 py-3">รายการ</th>
                                <th class="px-6 py-3 text-right">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($systemOptions as $opt)
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="px-6 py-4 text-xs font-bold text-slate-400">{{ $opt->sort_order }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 text-sm">{{ $opt->name }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-[9px] font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200 uppercase">{{ $opt->key }}</span>
                                            @if($opt->has_sub_options) <span
                                            class="text-[9px] font-bold text-blue-500 uppercase">Has Subs</span> @endif
                                            @if(!$opt->is_active) <span
                                            class="text-[9px] font-bold text-red-500 uppercase">Disabled</span> @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button @click='openEdit(@json($opt))'
                                                class="p-1.5 text-slate-400 hover:text-blue-600 transition" title="แก้ไข">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <form action="{{ route('backend.access-options.toggle', $opt->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1.5 {{ $opt->is_active ? 'text-green-500' : 'text-slate-300' }} hover:text-blue-600 transition">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-400 text-xs italic">ไม่มีข้อมูล
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Card 2: Programs --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-700">รายการเข้าถึงโปรแกรม (Programs)</h3>
                    </div>
                    <button @click="showAddProgram = !showAddProgram"
                        class="text-xs font-bold text-blue-600 hover:text-blue-700 transition flex items-center gap-1">
                        <span x-text="showAddProgram ? 'ปิดฟอร์ม' : '+ เพิ่มรายการ'"></span>
                    </button>
                </div>

                {{-- Add Form Inline --}}
                <div x-show="showAddProgram" x-transition class="p-6 bg-slate-50 border-b border-slate-200">
                    <form action="{{ route('backend.access-options.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="category" value="program">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ชื่อรายการ</label>
                                <input type="text" name="name" required
                                    class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700 placeholder:text-slate-400"
                                    placeholder="ระบุชื่อโปรแกรม...">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Key (ID)</label>
                                <input type="text" name="key" required
                                    class="w-full border-slate-300 rounded-lg text-sm font-mono px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700 placeholder:text-slate-400"
                                    placeholder="PROGRAM_KEY">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ช่องกรอกข้อมูลเพิ่มเติม
                                (คั่นด้วย ,) เช่น Username, Password</label>
                            <input type="text" name="custom_fields_list"
                                class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700 placeholder:text-slate-400"
                                placeholder="เช่น USERNAME, PASSWORD">
                        </div>

                        <div x-data="{ hasSub: false }" class="bg-slate-100/50 p-4 rounded-lg border border-slate-200">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="has_sub_options" x-model="hasSub" value="1"
                                    class="rounded border-slate-300 text-blue-600">
                                <span class="text-xs font-bold text-slate-700">เพิ่มหัวข้อย่อย / สิทธิการใช้งาน
                                    (Sub-options)</span>
                            </label>

                            <div x-show="hasSub" x-transition
                                class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">รายการย่อย
                                        (เช่น Read, Write, Full)</label>
                                    <input type="text" name="sub_options_list"
                                        class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700 placeholder:text-slate-400"
                                        placeholder="เช่น READ, WRITE, FULL">
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ประเภทการเลือก</label>
                                    <select name="sub_option_type"
                                        class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700">
                                        <option value="radio">เลือกได้ 1 อย่าง (Radio)</option>
                                        <option value="checkbox">เลือกได้หลายอย่าง (Checkbox)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 py-2">
                            <button type="submit"
                                class="bg-slate-800 text-white px-6 py-2 rounded-lg text-xs font-bold hover:bg-slate-900 transition shadow-sm">บันทึกรายการ</button>
                            <button type="button" @click="showAddProgram = false"
                                class="text-xs text-slate-500 hover:text-slate-700">ยกเลิก</button>
                        </div>
                    </form>
                </div>

                <div class="flex-1">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3 w-16">#</th>
                                <th class="px-6 py-3">รายการ</th>
                                <th class="px-6 py-3 text-right">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($programOptions as $opt)
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="px-6 py-4 text-xs font-bold text-slate-400">{{ $opt->sort_order }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 text-sm">{{ $opt->name }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-[9px] font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200 uppercase">{{ $opt->key }}</span>
                                            @if($opt->has_sub_options) <span
                                            class="text-[9px] font-bold text-blue-500 uppercase">Has Subs</span> @endif
                                            @if(!$opt->is_active) <span
                                            class="text-[9px] font-bold text-red-500 uppercase">Disabled</span> @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button @click='openEdit(@json($opt))'
                                                class="p-1.5 text-slate-400 hover:text-blue-600 transition" title="แก้ไข">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <form action="{{ route('backend.access-options.toggle', $opt->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1.5 {{ $opt->is_active ? 'text-green-500' : 'text-slate-300' }} hover:text-blue-600 transition">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-400 text-xs italic">ไม่มีข้อมูล
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal --}}
            <template x-if="editId !== null">
                <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="editId = null"></div>
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden relative animate-scale-in"
                        @click.stop>
                        <div class="px-6 py-4 bg-slate-800 text-white flex justify-between items-center">
                            <h3 class="font-bold">แก้ไขข้อมูลรายการ</h3>
                            <button @click="editId = null" class="text-slate-400 hover:text-white transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form :action="'/backend/access-options/' + editId" method="POST" class="p-6 space-y-4">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ชื่อรายการ</label>
                                    <input type="text" name="name" x-model="editName" required
                                        class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Key
                                        (ID)</label>
                                    <input type="text" name="key" x-model="editKey" required
                                        class="w-full border-slate-300 rounded-lg text-sm font-mono px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700">
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ลำดับ</label>
                                <input type="number" name="sort_order" x-model="editSort"
                                    class="w-24 border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700">
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ช่องกรอกข้อมูลเพิ่มเติม
                                    (คั่นด้วย ,) เช่น Username, Password</label>
                                <input type="text" name="custom_fields_list" x-model="editCustomFields"
                                    class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700">
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <label class="flex items-center gap-2 cursor-pointer mb-4">
                                    <input type="checkbox" name="has_sub_options" x-model="editHasSub" value="1"
                                        class="rounded border-slate-300 text-blue-600">
                                    <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">ตัวเลือกย่อย
                                        (Sub-options)</span>
                                </label>
                                <div x-show="editHasSub" x-transition class="space-y-4">
                                    <input type="text" name="sub_options_list" x-model="editSubList"
                                        placeholder="รายการตัวเลือก..."
                                        class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700">
                                    <select name="sub_option_type" x-model="editSubType"
                                        class="w-full border-slate-300 rounded-lg text-sm px-4 py-2 bg-slate-100/50 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all duration-200 text-slate-700">
                                        <option value="radio">Radio</option>
                                        <option value="checkbox">Checkbox</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 pt-4">
                                <button type="button" @click="editId = null"
                                    class="px-4 py-2 text-slate-500 font-bold text-sm">ยกเลิก</button>
                                <button type="submit"
                                    class="px-6 py-2 bg-slate-800 text-white rounded-lg font-bold text-sm hover:bg-slate-900 transition">บันทึกการแก้ไข</button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>

        <style>
            @keyframes slide-down {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes scale-in {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .animate-slide-down {
                animation: slide-down 0.2s ease-out;
            }

            .animate-scale-in {
                animation: scale-in 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            }
        </style>
@endsection