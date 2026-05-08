@extends('layouts.admin')

@section('breadcrumb', 'กำหนดขั้นตอนการอนุมัติ')

@section('content')
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control { border-radius: 0.75rem !important; padding: 0.625rem 1rem !important; border-color: #f1f5f9 !important; background-color: #f8fafc !important; font-size: 0.875rem !important; }
    .ts-wrapper.focus .ts-control { ring: 2px; --tw-ring-color: #2563eb !important; border-color: #2563eb !important; }
</style>

<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Approval Step Configuration</h2>
            <p class="text-sm text-slate-400">กำหนดลำดับและรายชื่อผู้อนุมัติมาตรฐานสำหรับทุกคำร้อง</p>
        </div>
        <button onclick="openAddModal()" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-lg shadow-blue-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            เพิ่มขั้นตอน
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-4 font-bold w-20 text-center">ลำดับ</th>
                        <th class="px-8 py-4 font-bold">ชื่อขั้นตอน</th>
                        <th class="px-8 py-4 font-bold">ผู้อนุมัติ</th>
                        <th class="px-8 py-4 font-bold">สถานะ</th>
                        <th class="px-8 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($configs as $config)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-5 text-sm font-bold text-slate-700 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600">
                                {{ $config->step_order }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-sm font-medium text-slate-700">{{ $config->step_name }}</td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-medium text-slate-700">{{ $config->approver->fullname }}</div>
                            <div class="text-[10px] text-slate-400 uppercase font-bold">{{ $config->approver->department }}</div>
                        </td>
                        <td class="px-8 py-5">
                            @if($config->is_active)
                                <span class="px-2 py-0.5 rounded-full bg-green-50 text-green-600 text-[10px] font-bold uppercase border border-green-100">เปิดใช้งาน</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-400 text-[10px] font-bold uppercase">ปิดใช้งาน</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right space-x-2">
                            <button onclick="openEditModal({{ json_encode($config) }})" class="p-1.5 text-slate-400 hover:text-blue-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <form action="{{ route('backend.approval-configs.destroy', $config->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('ยืนยันการลบขั้นตอนนีหรือไม่?')" class="p-1.5 text-slate-400 hover:text-red-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
<div id="config-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
    <div class="bg-white w-full max-w-md rounded-2xl p-8 shadow-2xl">
        <h3 id="modal-title" class="text-xl font-bold text-slate-800">เพิ่มขั้นตอนการอนุมัติ</h3>
        <form id="config-form" method="POST" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">ลำดับขั้นตอน</label>
                <input type="number" name="step_order" id="step_order" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">ชื่อขั้นตอน (เช่น IT Manager อนุมัติ)</label>
                <input type="text" name="step_name" id="step_name" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="ระบุชื่อขั้นตอน...">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">ผู้อนุมัติ</label>
                <select name="approver_id" id="approver_id" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">เลือกผู้อนุมัติ</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->fullname }} ({{ $user->department }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-slate-600 font-medium">เปิดใช้งาน</span>
                </label>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition font-bold text-sm">ยกเลิก</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition text-sm">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    let approverSelect;

    document.addEventListener('DOMContentLoaded', function() {
        approverSelect = new TomSelect('#approver_id', {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: 'พิมพ์เพื่อค้นหาชื่อผู้อนุมัติ...',
        });
    });

    function openAddModal() {
        document.getElementById('modal-title').innerText = 'เพิ่มขั้นตอนการอนุมัติ';
        document.getElementById('config-form').action = '{{ route("backend.approval-configs.store") }}';
        document.getElementById('form-method').value = 'POST';
        document.getElementById('step_order').value = '{{ $configs->count() + 1 }}';
        document.getElementById('step_name').value = '';
        
        if (approverSelect) {
            approverSelect.clear();
        }
        
        document.getElementById('is_active').checked = true;
        
        document.getElementById('config-modal').classList.remove('hidden');
        document.getElementById('config-modal').classList.add('flex');
    }

    function openEditModal(config) {
        document.getElementById('modal-title').innerText = 'แก้ไขขั้นตอนการอนุมัติ';
        document.getElementById('config-form').action = `/backend/approval-configs/${config.id}`;
        document.getElementById('form-method').value = 'PUT';
        document.getElementById('step_order').value = config.step_order;
        document.getElementById('step_name').value = config.step_name;
        
        if (approverSelect) {
            approverSelect.setValue(config.approver_id);
        }
        
        document.getElementById('is_active').checked = config.is_active == 1;
        
        document.getElementById('config-modal').classList.remove('hidden');
        document.getElementById('config-modal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('config-modal').classList.add('hidden');
        document.getElementById('config-modal').classList.remove('flex');
    }
</script>
@endsection
