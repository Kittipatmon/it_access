@extends('layouts.admin')

@section('breadcrumb', 'จัดการรายการเข้าถึง')

@section('content')
    <div class="space-y-8" x-data="{ 
                                                showAddSystem: {{ $errors->hasAny(['name', 'key']) && old('category') === 'system' ? 'true' : 'false' }}, 
                                                showAddProgram: {{ $errors->hasAny(['name', 'key']) && old('category') === 'program' ? 'true' : 'false' }},
                                                showAddEquipment: {{ $errors->hasAny(['name', 'key']) && old('category') === 'equipment' ? 'true' : 'false' }},
                                                editId: null,
                                                editName: '',
                                                editKey: '',
                                                editHasSub: false,
                                                editSubList: '',
                                                editSubType: 'radio',
                                                editCustomFields: '',
                                                editSort: 0,
                                                showAddModal: false,
                                                addCategory: 'system',
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
            <button @click="showAddModal = true" 
                class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                สร้างรายการใหม่
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($groupedOptions as $categoryKey => $options)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center text-white">
                                @if($categoryKey === 'system')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                @elseif($categoryKey === 'program')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @elseif($categoryKey === 'equipment')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                @endif
                            </div>
                            <h3 class="font-bold text-slate-700">{{ $categoryLabels[$categoryKey] ?? ucfirst($categoryKey) }}</h3>
                        </div>
                        <form id="delete-category-form-{{ $categoryKey }}" action="{{ route('backend.access-options.destroy-category', $categoryKey) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDeleteCategory('{{ $categoryKey }}', '{{ $categoryLabels[$categoryKey] ?? ucfirst($categoryKey) }}')"
                                class="p-2 text-slate-300 hover:text-red-600 transition" title="ลบทั้งหมวดหมู่">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <div class="flex-1">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3 w-16">#</th>
                                    <th class="px-6 py-3">รายการ</th>
                                    <th class="px-6 py-3 text-right">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($options as $opt)
                                    <tr class="hover:bg-slate-50 transition group">
                                        <td class="px-6 py-4 text-xs font-bold text-slate-400">{{ $opt->sort_order }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="font-bold {{ $opt->is_active ? 'text-slate-700' : 'text-slate-400' }} text-sm">{{ $opt->name }}</div>
                                                @if(!$opt->is_active)
                                                    <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full border border-red-100">ปิดการใช้งาน</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button @click='openEdit(@json($opt))' class="p-1.5 text-slate-400 hover:text-blue-600 transition" title="แก้ไข">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                                <form action="{{ route('backend.access-options.toggle', $opt->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-1.5 {{ $opt->is_active ? 'text-green-500' : 'text-slate-300' }} hover:text-blue-600 transition" title="{{ $opt->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form id="delete-form-{{ $opt->id }}" action="{{ route('backend.access-options.destroy', $opt->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" 
                                                        onclick="confirmDelete({{ $opt->id }})"
                                                        class="p-1.5 text-slate-300 hover:text-red-600 transition" title="ลบ">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 text-xs italic">ไม่มีข้อมูล</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "คุณต้องการลบรายการนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                })
            }

            function confirmDeleteCategory(categoryKey, label) {
                Swal.fire({
                    title: `ลบหมวดหมู่ ${label}?`,
                    text: `คำเตือน! รายการทั้งหมดในหมวดหมู่นี้จะถูกลบออกทั้งหมด คุณต้องการดำเนินการต่อหรือไม่?`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'ยืนยัน ลบทั้งหมด!',
                    cancelButtonText: 'ยกเลิก',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-category-form-' + categoryKey).submit();
                    }
                })
            }
        </script>


            {{-- Modal: Add New Item --}}
        <template x-if="showAddModal">
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden relative animate-scale-in" @click.stop>
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">สร้างรายการใหม่</h3>
                                <p class="text-xs text-blue-100">เพิ่มระบบ โปรแกรม หรืออุปกรณ์ลงในแบบฟอร์ม</p>
                            </div>
                        </div>
                        <button @click="showAddModal = false" class="text-white/70 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('backend.access-options.store') }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">ประเภทรายการ</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach(['system' => 'ระบบ', 'program' => 'โปรแกรม', 'equipment' => 'อุปกรณ์'] as $val => $label)
                                    <label class="relative flex flex-col items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all"
                                        :class="addCategory === '{{ $val }}' ? 'border-blue-500 bg-blue-50/50 text-blue-700' : 'border-slate-100 bg-slate-50 text-slate-500 hover:border-slate-200'">
                                        <input type="radio" name="category" value="{{ $val }}" x-model="addCategory" class="sr-only">
                                        <span class="text-sm font-bold">{{ $label }}</span>
                                    </label>
                                @endforeach
                                <label class="relative flex flex-col items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all"
                                    :class="addCategory === 'new' ? 'border-blue-500 bg-blue-50/50 text-blue-700' : 'border-slate-100 bg-slate-50 text-slate-500 hover:border-slate-200'">
                                    <input type="radio" name="category" value="new" x-model="addCategory" class="sr-only">
                                    <span class="text-sm font-bold">อื่นๆ (หมวดใหม่)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="addCategory === 'new'" x-transition class="animate-slide-down">
                            <label class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2 block">ชื่อหมวดหมู่ใหม่</label>
                            <input type="text" name="category_new" :required="addCategory === 'new'"
                                class="w-full border-blue-200 rounded-xl text-sm px-4 py-3 bg-blue-50/30 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                placeholder="ระบุชื่อหมวดหมู่ใหม่ที่ต้องการ...">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">ชื่อรายการ</label>
                                <input type="text" name="name" required
                                    class="w-full border-slate-200 rounded-xl text-sm px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                    placeholder="เช่น SAP, Notebook, Email">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Key (ID)</label>
                                <input type="text" name="key" required
                                    class="w-full border-slate-200 rounded-xl text-sm font-mono px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                    placeholder="UNIQUE_KEY">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">ช่องข้อมูลเพิ่มเติม (คั่นด้วย ,)</label>
                            <input type="text" name="custom_fields_list"
                                class="w-full border-slate-200 rounded-xl text-sm px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                placeholder="เช่น Username, Password, Serial">
                        </div>

                        <div x-data="{ hasSub: false }" class="bg-slate-50 p-5 rounded-2xl border border-slate-100 space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="has_sub_options" x-model="hasSub" value="1"
                                    class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">เพิ่มหัวข้อย่อย (Sub-options)</span>
                            </label>

                            <div x-show="hasSub" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">รายการย่อย</label>
                                    <input type="text" name="sub_options_list"
                                        class="w-full border-slate-200 rounded-lg text-sm px-4 py-2 bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="READ, WRITE, FULL">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ประเภทการเลือก</label>
                                    <select name="sub_option_type"
                                        class="w-full border-slate-200 rounded-lg text-sm px-4 py-2 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="radio">เลือกได้ 1 (Radio)</option>
                                        <option value="checkbox">เลือกได้หลาย (Checkbox)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4">
                            <button type="button" @click="showAddModal = false"
                                class="px-6 py-3 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">ยกเลิก</button>
                            <button type="submit"
                                class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all active:scale-95">
                                บันทึกรายการใหม่
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- Modal: Edit Item --}}
        <template x-if="editId !== null">
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editId = null"></div>
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden relative animate-scale-in" @click.stop>
                    <div class="px-6 py-5 bg-gradient-to-r from-slate-700 to-slate-900 text-white flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">แก้ไขรายการ</h3>
                                <p class="text-xs text-slate-300">ปรับปรุงข้อมูลระบบ โปรแกรม หรืออุปกรณ์</p>
                            </div>
                        </div>
                        <button @click="editId = null" class="text-white/70 hover:text-white transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form :action="`{{ url('backend/access-options') }}/${editId}`" method="POST" class="p-8 space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">ชื่อรายการ</label>
                                <input type="text" name="name" x-model="editName" required
                                    class="w-full border-slate-200 rounded-xl text-sm px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Key (ID)</label>
                                <input type="text" name="key" x-model="editKey" required
                                    class="w-full border-slate-200 rounded-xl text-sm font-mono px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">ช่องข้อมูลเพิ่มเติม (คั่นด้วย ,)</label>
                            <input type="text" name="custom_fields_list" x-model="editCustomFields"
                                class="w-full border-slate-200 rounded-xl text-sm px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                placeholder="เช่น Username, Password">
                        </div>

                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="has_sub_options" x-model="editHasSub" value="1"
                                    class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">เพิ่มหัวข้อย่อย (Sub-options)</span>
                            </label>

                            <div x-show="editHasSub" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">รายการย่อย</label>
                                    <input type="text" name="sub_options_list" x-model="editSubList"
                                        class="w-full border-slate-200 rounded-lg text-sm px-4 py-2 bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                                        placeholder="READ, WRITE, FULL">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">ประเภทการเลือก</label>
                                    <select name="sub_option_type" x-model="editSubType"
                                        class="w-full border-slate-200 rounded-lg text-sm px-4 py-2 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="radio">เลือกได้ 1 (Radio)</option>
                                        <option value="checkbox">เลือกได้หลาย (Checkbox)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">ลำดับ (Sort Order)</label>
                            <input type="number" name="sort_order" x-model="editSort"
                                class="w-full border-slate-200 rounded-xl text-sm px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4">
                            <button type="button" @click="editId = null"
                                class="px-6 py-3 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">ยกเลิก</button>
                            <button type="submit"
                                class="px-8 py-3 bg-slate-800 text-white rounded-xl font-bold text-sm shadow-lg shadow-slate-200 hover:bg-slate-900 hover:-translate-y-0.5 transition-all active:scale-95">
                                บันทึกการแก้ไข
                            </button>
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