@extends('layouts.admin')

@section('breadcrumb', 'จัดการคำร้องขอสิทธิ')

@section('content')
    <div class="space-y-6" x-data="{ tab: 'all' }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">IT Access Approvals</h2>
                <p class="text-sm text-slate-400">จัดการรายการคำร้องขอสิทธิเข้าถึงข้อมูล</p>
            </div>

            @if($isAdmin)
                <div class="flex flex-wrap gap-3">
                    @if($newRequestsCount > 0)
                        <div
                            class="flex items-center gap-3 bg-amber-50 border border-amber-200 px-4 py-2 rounded-xl shadow-sm animate-pulse">
                            <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center text-white">
                                <i class="fa-solid fa-file-circle-plus"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-tight">คำร้องใหม่</p>
                                <p class="text-sm font-bold text-amber-900">{{ $newRequestsCount }} รายการค้างพิจารณา</p>
                            </div>
                        </div>
                    @endif

                    @if($itActionCount > 0)
                        <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 px-4 py-2 rounded-xl shadow-sm">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                                <i class="fa-solid fa-gears"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-tight">รอ IT ดำเนินการ</p>
                                <p class="text-sm font-bold text-blue-900">{{ $itActionCount }} รายการที่ต้องจัดการ</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        @if($isAdmin)
            <!-- Tabs -->
            <div class="flex space-x-1 bg-slate-100 p-1 rounded-xl w-fit">
                <button @click="tab = 'all'"
                    :class="tab === 'all' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700'"
                    class="px-6 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    <span>รายการทั้งหมด</span>
                    <span :class="tab === 'all' ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-500'"
                        class="px-2 py-0.5 rounded-md text-[10px] font-black transition-colors">
                        {{ $allRequests->count() }}
                    </span>
                </button>
                <button @click="tab = 'pending'"
                    :class="tab === 'pending' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700'"
                    class="px-6 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    <span>งานของฉัน</span>
                    <span
                        :class="tab === 'pending' ? 'bg-blue-600 text-white shadow-sm shadow-blue-200' : 'bg-slate-200 text-slate-500'"
                        class="px-2 py-0.5 rounded-md text-[10px] font-black transition-colors">
                        {{ $pendingApprovals->count() }}
                    </span>
                </button>
            </div>
        @endif

        @if($isAdmin)
            <!-- All Requests Table -->
            <div x-show="tab === 'all'" x-transition
                class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-8 py-4 border-b border-slate-100 bg-slate-50/30">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">รายการคำร้องทั้งหมดในระบบ</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead
                            class="bg-slate-50 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-4 font-bold">Request No</th>
                                <th class="px-8 py-4 font-bold">Requester</th>
                                <th class="px-8 py-4 font-bold">Status</th>
                                <th class="px-8 py-4 font-bold">Current Step</th>
                                <th class="px-8 py-4 font-bold text-right">Date / Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($allRequests as $req)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-8 py-5 text-sm font-bold text-slate-700">{{ $req->request_no }}</td>
                                    <td class="px-8 py-5">
                                        <div class="text-sm font-medium text-slate-700">{{ $req->firstname }} {{ $req->lastname }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 uppercase font-bold">{{ $req->department_name }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        @if($req->status === 'completed')
                                            @php
                                                $nda = $req->confidentialityAgreement;
                                                $isWaitingWitness = $nda && (!$nda->witness1_agreed_at || ($nda->witness2_user_id && !$nda->witness2_agreed_at));
                                            @endphp
                                            @if(!$nda)
                                                <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[10px] font-bold uppercase border border-red-200 shadow-sm">รอการบันทึก NDA</span>
                                            @elseif($isWaitingWitness)
                                                <span class="px-2 py-0.5 rounded-full bg-orange-100 text-orange-600 text-[10px] font-bold uppercase border border-orange-200 shadow-sm">กำลังรอพยาน</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full bg-green-600 text-white text-[10px] font-bold uppercase shadow-sm">เสร็จสมบูรณ์</span>
                                            @endif
                                        @elseif($req->status === 'approved' && $req->it_status == 'completed')
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-bold uppercase border border-blue-200">รอผู้ใช้งานยืนยัน</span>
                                        @elseif($req->status === 'approved')
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-bold uppercase">อนุมัติแล้ว</span>
                                        @elseif($req->status === 'rejected')
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[10px] font-bold uppercase">ปฏิเสธ</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-600 text-[10px] font-bold uppercase">รอดำเนินการ</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-sm text-slate-500">
                                        @if($req->status === 'completed')
                                            @php
                                                $nda = $req->confidentialityAgreement;
                                                $isWaitingWitness = $nda && (!$nda->witness1_agreed_at || ($nda->witness2_user_id && !$nda->witness2_agreed_at));
                                            @endphp
                                            @if(!$nda)
                                                <span class="inline-flex items-center gap-1 text-red-500 font-bold text-xs">
                                                    <i class="fa-solid fa-file-signature"></i>
                                                    รอการบันทึก NDA
                                                </span>
                                            @elseif($isWaitingWitness)
                                                <span class="inline-flex items-center gap-1 text-orange-500 font-bold text-xs">
                                                    <i class="fa-solid fa-users-viewfinder"></i>
                                                    กำลังรอพยาน
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-green-600 font-bold text-xs">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    เสร็จสมบูรณ์
                                                </span>
                                            @endif
                                        @elseif($req->status === 'approved' && $req->it_status == 'completed')
                                            <span
                                                class="inline-flex items-center gap-1 text-orange-500 font-bold text-xs animate-pulse">
                                                รอผู้ใช้งานยืนยัน
                                            </span>
                                        @elseif($req->status === 'approved')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-bold text-[11px] animate-pulse border border-blue-200 shadow-sm">
                                                <i class="fa-solid fa-clock-rotate-left"></i>
                                                เจ้าหน้าที่ IT ดำเนินการ
                                            </span>
                                        @elseif($req->status === 'rejected')
                                            <span class="text-red-400 text-xs font-medium">ถูกยกเลิก</span>
                                        @else
                                            {{ $req->steps->where('status', 'pending')->first()->step_name ?? '-' }}
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right flex justify-end items-center space-x-3">
                                        <a href="{{ route('manage.approvals.show', $req->id) }}"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 transition" title="ดูรายละเอียด">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <span
                                            class="text-xs text-slate-400 font-medium whitespace-nowrap">{{ $req->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-slate-400">
                                        <p>ไม่มีรายการคำร้องในระบบ</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Pending Table -->
        <div x-show="tab === 'pending'" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-8 py-4 border-b border-slate-100 bg-slate-50/30">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">รายการที่รอคุณอนุมัติ</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead
                        class="bg-slate-50 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-4 font-bold">Request No</th>
                            <th class="px-8 py-4 font-bold">Requester</th>
                            <th class="px-8 py-4 font-bold">Step</th>
                            <th class="px-8 py-4 font-bold">Details</th>
                            <th class="px-8 py-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pendingApprovals as $step)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-8 py-5 text-sm font-bold text-blue-600">{{ $step->requestForm->request_no }}</td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-medium text-slate-700">{{ $step->requestForm->firstname }}
                                        {{ $step->requestForm->lastname }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold">
                                        {{ $step->requestForm->department_name }}
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-600 text-[10px] font-bold uppercase border border-blue-100">
                                        {{ $step->step_name }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm text-slate-500 italic">
                                    "{{ Str::limit($step->requestForm->details, 40) }}"
                                </td>
                                <td class="px-8 py-5 text-right space-x-2 flex justify-end items-center">
                                    <a href="{{ route('manage.approvals.show', $step->requestForm->id) }}"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 transition" title="ดูรายละเอียด">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button onclick="openModal('approve', {{ $step->id }})"
                                        class="px-4 py-1.5 bg-green-500 text-white text-xs font-bold rounded-lg hover:bg-green-600 transition shadow-md shadow-green-100">
                                        อนุมัติ
                                    </button>
                                    <button onclick="openModal('reject', {{ $step->id }})"
                                        class="px-4 py-1.5 bg-white border border-slate-200 text-red-500 text-xs font-bold rounded-lg hover:bg-red-50 transition">
                                        ปฏิเสธ
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200 mb-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p>ไม่มีรายการค้างรออนุมัติในขณะนี้</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Approve/Reject Modal -->
    <div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="bg-white w-full max-w-md rounded-2xl p-8 shadow-2xl">
            <h3 id="modal-title" class="text-xl font-bold text-slate-800">ดำเนินการ</h3>
            <form id="modal-form" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label
                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">หมายเหตุการพิจารณา</label>
                    <textarea name="remark" id="remark-field" rows="3"
                        class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="ระบุเหตุผล..."></textarea>
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition font-bold text-sm">ยกเลิก</button>
                    <button type="submit" id="submit-btn"
                        class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition text-sm">ยืนยันการดำเนินการ</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(type, stepId) {
            const modal = document.getElementById('modal');
            const form = document.getElementById('modal-form');
            const title = document.getElementById('modal-title');
            const btn = document.getElementById('submit-btn');
            const remark = document.getElementById('remark-field');

            if (type === 'approve') {
                title.innerText = 'ยืนยันการอนุมัติคำร้อง';
                form.action = `/manage/approvals/${stepId}/approve`;
                btn.className = 'flex-1 px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold transition text-sm';
                remark.required = false;
            } else {
                title.innerText = 'ยืนยันการปฏิเสธคำร้อง';
                form.action = `/manage/approvals/${stepId}/reject`;
                btn.className = 'flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition text-sm';
                remark.required = true;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection