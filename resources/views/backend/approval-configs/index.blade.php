@extends('layouts.admin')

@section('breadcrumb', 'กำหนดขั้นตอนการอนุมัติ')

@section('content')
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            border-radius: 0.75rem !important;
            padding: 0.625rem 1rem !important;
            border-color: #f1f5f9 !important;
            background-color: #f8fafc !important;
            font-size: 0.875rem !important;
            cursor: pointer !important;
        }

        .ts-wrapper.focus .ts-control {
            ring: 2px;
            --tw-ring-color: #2563eb !important;
            border-color: #2563eb !important;
        }

        .ts-dropdown {
            z-index: 9999 !important;
            border-radius: 0.75rem !important;
            border: 1px solid #f1f5f9 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
    </style>

    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Approval Step Configuration</h2>
                <p class="text-sm text-slate-400">กำหนดลำดับและรายชื่อผู้อนุมัติมาตรฐานสำหรับทุกคำร้อง</p>
            </div>
            <button onclick="openAddModal()"
                class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                เพิ่มขั้นตอน
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead
                        class="bg-slate-50 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-4 font-bold w-20 text-center">ลำดับ</th>
                            <th class="px-8 py-4 font-bold">ชื่อขั้นตอน</th>
                            <th class="px-8 py-4 font-bold">ผู้อนุมัติ</th>
                            <th class="px-8 py-4 font-bold">สถานะ</th>
                            <th class="px-8 py-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <!-- Step 1 is always the Requester -->
                        <tr class="bg-blue-50/30">
                            <td class="px-8 py-5 text-sm font-bold text-blue-600 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
                                    1
                                </span>
                            </td>
                            <td class="px-8 py-5 text-sm font-bold text-slate-800 italic">ผู้ร้องขอ (Requester)</td>
                            <td class="px-8 py-5 text-xs text-slate-400 italic">ดำเนินการโดยผู้สร้างคำร้อง</td>
                            <td class="px-8 py-5">
                                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-bold uppercase border border-blue-200">System</span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="text-[10px] text-slate-300 italic font-medium">ขั้นตอนพื้นฐาน</span>
                            </td>
                        </tr>

                        @forelse($configs as $config)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-8 py-5 text-sm font-bold text-slate-700 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600">
                                        {{ $config->step_order }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm font-medium text-slate-700">{{ $config->step_name }}</td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-medium text-slate-700">{{ $config->approver->fullname }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold">
                                        {{ $config->approver->department }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    @if($config->is_active)
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-green-50 text-green-600 text-[10px] font-bold uppercase border border-green-100">เปิดใช้งาน</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-400 text-[10px] font-bold uppercase">ปิดใช้งาน</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right space-x-2">
                                    <button onclick="openEditModal({{ json_encode($config) }})"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('backend.approval-configs.destroy', $config->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('ยืนยันการลบขั้นตอนนีหรือไม่?')"
                                            class="p-1.5 text-slate-400 hover:text-red-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-slate-400">
                                    ยังไม่มีการกำหนดขั้นตอนการอนุมัติ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="config-modal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-md hidden items-center justify-center p-4 z-50 transition-all duration-300">
        <div class="bg-white w-full max-w-md rounded-3xl p-0 shadow-2xl transform transition-all scale-95 opacity-0 duration-300" id="modal-container">
            <!-- Modal Header -->
            <div class="bg-slate-50 px-8 py-6 border-b border-slate-100">
                <h3 id="modal-title" class="text-xl font-extrabold text-slate-800 tracking-tight">เพิ่มขั้นตอนการอนุมัติ</h3>
                <p class="text-xs text-slate-400 mt-1">กำหนดรายละเอียดและผู้อนุมัติสำหรับขั้นตอนนี้</p>
            </div>

            <form id="config-form" method="POST" class="p-8 space-y-5">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-4">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ลำดับ</label>
                        <input type="number" name="step_order" id="step_order" required 
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none">
                    </div>
                    <div class="col-span-8">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ชื่อขั้นตอน</label>
                        <input type="text" name="step_name" id="step_name" required 
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" 
                            placeholder="เช่น ICT Manager อนุมัติ">
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">1. เลือกแผนก (เพื่อกรองรายชื่อ)</label>
                        <select id="filter_dept_id" class="w-full">
                            <option value="">ทั้งหมดทุกแผนก</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">2. ระบุผู้อนุมัติ</label>
                        <select name="approver_id" id="approver_id" required class="w-full">
                            <!-- Options will be populated by JS -->
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between py-2 px-1">
                    <div class="flex items-center space-x-3">
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked 
                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-300 checked:right-0 checked:border-blue-600"/>
                            <label for="is_active" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer"></label>
                        </div>
                        <span class="text-sm text-slate-600 font-semibold italic">เปิดใช้งานขั้นตอนนี้</span>
                    </div>
                </div>

                <div class="flex space-x-3 pt-6 border-t border-slate-50">
                    <button type="button" onclick="closeModal()" 
                        class="flex-1 px-4 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-2xl transition font-bold text-sm tracking-wide">
                        ยกเลิก
                    </button>
                    <button type="submit" 
                        class="flex-1 px-4 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold transition text-sm tracking-wide shadow-xl shadow-blue-200">
                        บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .toggle-checkbox:checked { right: 0; border-color: #2563eb; }
        .toggle-checkbox:checked + .toggle-label { background-color: #2563eb; }
        .toggle-checkbox { right: 16px; transition: all 0.3s; }
        #modal-container.scale-100 { transform: scale(1); opacity: 1; }
    </style>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    let approverSelect, deptSelect;
    const allApproverOptions = @json($users->map(function($u) { 
        return [
            'id' => $u->id, 
            'name' => $u->fullname . ' [' . $u->emp_code . '] (' . ($u->department_rel->name ?? 'N/A') . ')', 
            'dept_id' => $u->dept_id 
        ]; 
    }));

    document.addEventListener('turbo:load', function() {
        // Initialize Dept Filter with Tom Select
        deptSelect = new TomSelect('#filter_dept_id', {
            create: false,
            placeholder: 'เลือกแผนก...',
            dropdownParent: 'body',
            maxOptions: null,
            onDropdownOpen: function() {
                // Ensure dropdown doesn't go too high
                this.dropdown.style.maxHeight = '250px';
            }
        });

        // Initialize Approver Select
        approverSelect = new TomSelect('#approver_id', {
            valueField: 'id',
            labelField: 'name',
            searchField: ['name'],
            options: allApproverOptions,
            placeholder: 'พิมพ์เพื่อค้นหาชื่อผู้อนุมัติ...',
            create: false,
            dropdownParent: 'body'
        });

        deptSelect.on('change', function(value) {
            filterApprovers(value);
        });
    });

    function filterApprovers(deptId, selectedValue = null) {
        approverSelect.clear();
        approverSelect.clearOptions();
        
        const filtered = deptId 
            ? allApproverOptions.filter(opt => opt.dept_id == deptId)
            : allApproverOptions;
            
        approverSelect.addOptions(filtered);
        
        if (selectedValue) {
            approverSelect.setValue(selectedValue);
        }
    }

    function openAddModal() {
        document.getElementById('modal-title').innerText = 'เพิ่มขั้นตอนการอนุมัติ';
        document.getElementById('config-form').action = '{{ route("backend.approval-configs.store") }}';
        document.getElementById('form-method').value = 'POST';
        document.getElementById('step_order').value = '{{ $configs->count() + 2 }}';
        document.getElementById('step_name').value = '';
        
        if (deptSelect) deptSelect.setValue('');
        filterApprovers('');
        
        document.getElementById('is_active').checked = true;
        
        const modal = document.getElementById('config-modal');
        const container = document.getElementById('modal-container');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animation
        setTimeout(() => {
            container.classList.add('scale-100', 'opacity-100');
            container.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function openEditModal(config) {
        document.getElementById('modal-title').innerText = 'แก้ไขขั้นตอนการอนุมัติ';
        document.getElementById('config-form').action = `/backend/approval-configs/${config.id}`;
        document.getElementById('form-method').value = 'PUT';
        document.getElementById('step_order').value = config.step_order;
        document.getElementById('step_name').value = config.step_name;
        
        // Find current approver to get their dept
        const currentApprover = allApproverOptions.find(u => u.id == config.approver_id);
        const deptId = currentApprover ? currentApprover.dept_id : '';
        
        if (deptSelect) deptSelect.setValue(deptId);
        filterApprovers(deptId, config.approver_id);
        
        document.getElementById('is_active').checked = config.is_active == 1;
        
        const modal = document.getElementById('config-modal');
        const container = document.getElementById('modal-container');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animation
        setTimeout(() => {
            container.classList.add('scale-100', 'opacity-100');
            container.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('config-modal');
        const container = document.getElementById('modal-container');
        
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
</script>
@endsection