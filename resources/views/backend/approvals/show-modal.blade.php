<div class="space-y-6">
    <!-- Header: Status & Info -->
    <div class="flex justify-between items-start">
        <div>
            <h4 class="text-lg font-bold text-slate-800">{{ $request->request_no }}</h4>
            <p class="text-xs text-slate-400">สร้างเมื่อ: {{ $request->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            @if($request->status == 'pending')
                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-600 text-[10px] font-bold uppercase">รอดำเนินการ</span>
            @elseif($request->status == 'approved')
                <span class="px-3 py-1 rounded-full bg-green-100 text-green-600 text-[10px] font-bold uppercase">สำเร็จ</span>
            @else
                <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-[10px] font-bold uppercase">ปฏิเสธ</span>
            @endif
        </div>
    </div>

    <!-- Section 1: User Info -->
    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
        <h5 class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-3">ข้อมูลผู้ร้องขอ</h5>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] text-slate-400 block">ชื่อ-นามสกุล</label>
                <p class="text-sm font-medium text-slate-700">{{ $request->firstname }} {{ $request->lastname }}</p>
            </div>
            <div>
                <label class="text-[10px] text-slate-400 block">รหัสพนักงาน</label>
                <p class="text-sm font-medium text-slate-700">{{ $request->emp_code }}</p>
            </div>
            <div>
                <label class="text-[10px] text-slate-400 block">แผนก</label>
                <p class="text-sm font-medium text-slate-700">{{ $request->department_name }}</p>
            </div>
            <div>
                <label class="text-[10px] text-slate-400 block">ประเภทคำร้อง</label>
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
        </div>
    </div>

    <!-- Section 2: Access Details -->
    <div class="space-y-4">
        <div>
            <h5 class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2">สิทธิ์การเข้าถึงที่ร้องขอ</h5>
            <div class="flex flex-wrap gap-2">
                @php
                    $systemAccess = is_array($request->system_access) ? $request->system_access : json_decode($request->system_access, true) ?? [];
                    $programAccess = is_array($request->program_access) ? $request->program_access : json_decode($request->program_access, true) ?? [];
                @endphp

                @foreach($systemAccess as $key => $val)
                    @if($val == "1" && $key !== 'other_text')
                        <span class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-[10px] text-slate-600 font-medium capitalize">{{ str_replace('_', ' ', $key) }}</span>
                    @endif
                @endforeach
                @foreach($programAccess as $key => $val)
                    @if($val == "1" && $key !== 'other_text')
                        <span class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-[10px] text-slate-600 font-medium capitalize">{{ str_replace('_', ' ', $key) }}</span>
                    @endif
                @endforeach
            </div>
        </div>

        @if($request->details)
        <div>
            <label class="text-[10px] text-slate-400 block uppercase font-bold tracking-widest">รายละเอียดเพิ่มเติม</label>
            <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 mt-1 italic">
                "{{ $request->details }}"
            </p>
        </div>
        @endif
    </div>

    <!-- Section 3: Approval Steps -->
    <div>
        <h5 class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-3">สถานะการอนุมัติ</h5>
        <div class="space-y-2">
            @foreach($request->steps as $step)
            <div class="flex items-center justify-between p-3 rounded-xl border {{ $step->status == 'approved' ? 'bg-green-50/50 border-green-100' : ($step->status == 'rejected' ? 'bg-red-50/50 border-red-100' : 'bg-white border-slate-100') }}">
                <div class="flex items-center space-x-3">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold {{ $step->status == 'approved' ? 'bg-green-500 text-white' : ($step->status == 'rejected' ? 'bg-red-500 text-white' : 'bg-slate-200 text-slate-500') }}">
                        {{ $step->step_order }}
                    </span>
                    <div>
                        <p class="text-xs font-bold text-slate-700">{{ $step->step_name }}</p>
                        <p class="text-[10px] text-slate-400">{{ $step->approver->name }}</p>
                    </div>
                </div>
                <div>
                    @if($step->status == 'approved')
                        <span class="text-green-500 font-bold text-[10px] uppercase">อนุมัติแล้ว</span>
                    @elseif($step->status == 'rejected')
                        <span class="text-red-500 font-bold text-[10px] uppercase">ปฏิเสธ</span>
                    @else
                        <span class="text-slate-300 font-bold text-[10px] uppercase">รอการพิจารณา</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
