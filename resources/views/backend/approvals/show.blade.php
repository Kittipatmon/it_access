@extends('layouts.admin')

@section('breadcrumb', 'รายละเอียดคำร้องขอสิทธิ')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="mb-4 no-print flex justify-between items-center">
            <a href="{{ route('backend.approvals.index') }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-700 font-bold text-xs uppercase tracking-widest transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                กลับไปที่รายการคำร้อง
            </a>
            <div class="flex gap-2">
                 <button onclick="window.print()" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold text-xs hover:scale-105 transition-all">
                    พิมพ์ใบคำร้อง (PDF)
                 </button>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-3xl overflow-hidden border border-slate-200 printable-content">
            <!-- Header -->
            <div class="p-8 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <img src="https://www.kumwell.com/wp-content/uploads/2021/04/Logo-Kumwell-PNG.png" class="h-12 w-auto" alt="Kumwell Logo">
                    <div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">แบบฟอร์มการร้องขอสิทธิใช้งานเทคโนโลยีสารสนเทศ</h2>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">QF-IT-08: Rev: 02 (06-07-20)</p>
                    </div>
                </div>
                <div class="text-right flex flex-col items-end gap-2">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Request No:</p>
                        <p class="text-lg font-black text-blue-600 leading-none">{{ $request->request_no }}</p>
                    </div>
                    @if($request->status == 'pending')
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-[10px] font-bold uppercase border border-yellow-200">รออนุมัติ</span>
                    @elseif($request->status == 'completed')
                        <span class="px-3 py-1 rounded-full bg-green-600 text-white text-[10px] font-bold uppercase border border-green-600 shadow-sm">เสร็จสมบูรณ์</span>
                    @elseif($request->status == 'approved' && $request->it_status == 'completed')
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold uppercase border border-blue-200">รอผู้ใช้งานยืนยัน</span>
                    @elseif($request->status == 'approved')
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold uppercase border border-blue-200">อนุมัติแล้ว</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-[10px] font-bold uppercase border border-red-200">ถูกปฏิเสธ</span>
                    @endif
                </div>
            </div>

            <div class="p-8 space-y-12">
                {{-- ส่วนที่ 1: ผู้ร้องขอ --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-blue-600 uppercase tracking-widest border-l-4 border-blue-500 pl-4">ส่วนที่ 1 ผู้ร้องขอ</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 bg-slate-50/50 p-8 rounded-3xl border border-slate-100">
                        <div class="md:col-span-3">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">ประเภทคำร้อง</label>
                             <p class="font-black text-slate-800 text-lg">
                                @php $types = ['new_employee' => 'พนักงานใหม่', 'resign' => 'ลาออก', 'position_change' => 'ปรับตำแหน่ง', 'transfer' => 'โอนย้าย', 'add_remove_access' => 'เพิ่มสิทธิ์/ลบสิทธิ์']; @endphp
                                {{ $types[$request->request_type] ?? $request->request_type }}
                             </p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">ชื่อ-นามสกุล</label>
                            <p class="text-base text-slate-700 font-bold">{{ $request->firstname }} {{ $request->lastname }} ({{ $request->nickname_th ?: '-' }})</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">รหัสพนักงาน</label>
                            <p class="text-base text-slate-700 font-black">{{ $request->emp_code }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">แผนก/ฝ่าย</label>
                            <p class="text-base text-slate-700 font-bold">{{ $request->department_name }} {{ $request->division_name ? '/ ' . $request->division_name : '' }}</p>
                        </div>
                    </div>
                </div>

                {{-- ส่วนที่ 2: การเข้าถึง --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-blue-600 uppercase tracking-widest border-l-4 border-blue-500 pl-4">ส่วนที่ 2 การเข้าถึง (ตามความประสงค์)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border-2 border-slate-100 rounded-2xl p-6 bg-white">
                            <h4 class="text-xs font-black text-slate-400 mb-4 uppercase tracking-widest border-b pb-2">ระบบที่ต้องการ</h4>
                            <ul class="text-sm space-y-2 text-slate-700">
                                @php 
                                    $hasSystem = false; 
                                    $sysAcc = is_array($request->system_access) ? $request->system_access : json_decode($request->system_access, true) ?? [];
                                @endphp
                                @foreach($sysAcc as $key => $val)
                                    @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                        <li class="flex items-center gap-2">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </li>
                                        @php $hasSystem = true; @endphp
                                    @endif
                                @endforeach
                                @if(!$hasSystem) <li class="text-slate-300 italic">-</li> @endif
                            </ul>
                        </div>
                        <div class="border-2 border-slate-100 rounded-2xl p-6 bg-white">
                            <h4 class="text-xs font-black text-slate-400 mb-4 uppercase tracking-widest border-b pb-2">โปรแกรมที่ต้องการ</h4>
                            <ul class="text-sm space-y-2 text-slate-700">
                                @php 
                                    $hasProgram = false; 
                                    $progAcc = is_array($request->program_access) ? $request->program_access : json_decode($request->program_access, true) ?? [];
                                @endphp
                                @foreach($progAcc as $key => $val)
                                    @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                        <li class="flex items-center gap-2">
                                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </li>
                                        @php $hasProgram = true; @endphp
                                    @endif
                                @endforeach
                                @if(!$hasProgram) <li class="text-slate-300 italic">-</li> @endif
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- ลำดับการอนุมัติ (Integrated Steps) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-8 border-t border-slate-100">
                    <div class="flex flex-col items-center p-8 bg-slate-50/50 rounded-3xl border border-slate-100">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">ลายมือชื่อผู้ร้องขอ</label>
                        @if($request->signature_path)
                            <img src="{{ asset('storage/' . $request->signature_path) }}" alt="Signature" class="h-20 w-auto mb-4 opacity-80">
                        @else
                            <div class="h-20 flex items-center text-slate-300 italic text-sm">ไม่พบข้อมูลลายมือชื่อ</div>
                        @endif
                        <div class="w-48 h-px bg-slate-200 mb-2"></div>
                        <p class="text-xs font-black text-slate-700 uppercase tracking-tighter">{{ $request->firstname }} {{ $request->lastname }}</p>
                        <p class="text-[9px] text-slate-400 font-bold">{{ $request->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">ลำดับการอนุมัติ</label>
                        @foreach($request->steps->sortBy('step_order') as $step)
                            @if($step->status !== 'pending' || $step->step_order == $request->current_step || $request->status == 'rejected')
                                <div class="flex items-center justify-between p-4 rounded-2xl border-2 {{ $step->status == 'approved' ? 'bg-green-50 border-green-100' : ($step->status == 'rejected' ? 'bg-red-50 border-red-100' : 'bg-white border-slate-100') }}">
                                    <div class="flex items-center gap-4">
                                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-black {{ $step->status == 'approved' ? 'bg-green-500 text-white' : ($step->status == 'rejected' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-400') }}">
                                            {{ $step->step_order }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-700 uppercase tracking-tight">{{ $step->step_name }}</p>
                                            @if($step->approver)
                                                <p class="text-[10px] text-slate-500 font-bold">{{ $step->approver->fullname }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] font-black uppercase tracking-widest {{ $step->status == 'approved' ? 'text-green-600' : ($step->status == 'rejected' ? 'text-red-600' : 'text-slate-400') }}">
                                            {{ $step->status == 'pending' ? 'รออนุมัติ' : ($step->status == 'approved' ? 'อนุมัติแล้ว' : 'ปฏิเสธ') }}
                                        </span>
                                        @if($step->status == 'approved' && $step->approved_at)
                                            <p class="text-[9px] text-slate-400 mt-1 font-bold">{{ $step->approved_at->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- ส่วนที่ 3: สำหรับเจ้าหน้าที่ IT --}}
                <div class="space-y-8 pt-12 border-t-2 border-slate-200">
                    <div class="flex items-center justify-between bg-slate-900 text-white py-3 px-6 rounded-2xl">
                         <h3 class="text-xs font-black uppercase tracking-[0.2em]">ส่วนที่ 3 สำหรับเจ้าหน้าที่เทคโนโลยีสารสนเทศ</h3>
                         @if($request->it_status == 'completed')
                            <span class="text-[10px] font-black bg-green-500 text-white px-3 py-1 rounded-full border border-green-400">EXECUTED</span>
                         @endif
                    </div>
                    
                    @if($request->status == 'approved' || $request->status == 'completed')
                        @if($request->it_status == 'completed')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-2 border-slate-300 rounded-3xl overflow-hidden shadow-2xl shadow-slate-100">
                                {{-- Left Box: System Access Results --}}
                                <div class="p-8 border-r-2 border-slate-300 flex flex-col bg-white">
                                    <h4 class="text-[11px] font-black text-slate-400 border-b pb-4 mb-6 uppercase tracking-widest">การเข้าถึงระบบ</h4>
                                    <div class="space-y-4 text-xs flex-grow">
                                        @foreach($request->it_system_config as $configKey => $configVal)
                                            @if(Str::endsWith($configKey, '_check'))
                                                @php 
                                                    $itemKey = str_replace('_check', '', $configKey);
                                                    $opt = $accessOptions->where('key', $itemKey)->first();
                                                @endphp
                                                <div class="border-b border-slate-50 pb-3">
                                                    <p class="font-bold text-slate-400 uppercase tracking-tighter mb-1">{{ $opt ? $opt->name : ucwords(str_replace('_', ' ', $itemKey)) }}:</p>
                                                    <div class="pl-4 space-y-1">
                                                        @if($opt && $opt->custom_fields)
                                                            @foreach($opt->custom_fields as $field)
                                                                @php $val = $request->it_system_config[$itemKey . '_' . Str::snake($field)] ?? '-'; @endphp
                                                                <div class="flex justify-between">
                                                                    <span class="text-slate-400">{{ $field }}:</span>
                                                                    <span class="font-black text-slate-800">{{ $val }}</span>
                                                                </div>
                                                            @endforeach
                                                        @elseif(in_array($itemKey, ['login_computer', 'email']))
                                                            <div class="flex justify-between">
                                                                <span class="text-slate-400">User/Pass:</span>
                                                                <span class="font-black text-slate-800">{{ $request->it_system_config[$itemKey.'_user'] ?? '-' }} / {{ $request->it_system_config[$itemKey.'_pass'] ?? '********' }}</span>
                                                            </div>
                                                        @elseif($itemKey === 'file_server')
                                                            <span class="font-black text-green-600">ALLOWED</span>
                                                        @else
                                                            <span class="font-black text-slate-800">{{ is_string($configVal) ? $configVal : 'CHECKED' }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="mt-8 pt-6 border-t border-slate-100 space-y-2">
                                        <div class="text-[10px] flex justify-between"><span class="font-black text-slate-400 uppercase">สถานะ:</span> <span class="{{ ($request->it_system_config['status'] ?? '') == 'completed' ? 'text-green-600' : 'text-yellow-600' }} font-black uppercase tracking-widest">{{ $request->it_system_config['status'] ?? 'Pending' }}</span></div>
                                        <div class="text-[10px] flex justify-between"><span class="font-black text-slate-400 uppercase">ผู้ดำเนินการ:</span> <span class="font-bold text-slate-700">{{ $request->itStaff->fullname ?? '-' }}</span></div>
                                        <div class="text-[9px] text-slate-400 italic text-right mt-2">วันที่: {{ $request->it_configured_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                                {{-- Right Box: Program Access Results --}}
                                <div class="p-8 flex flex-col bg-white">
                                    <h4 class="text-[11px] font-black text-slate-400 border-b pb-4 mb-6 uppercase tracking-widest">การเข้าถึงโปรแกรม</h4>
                                    <div class="space-y-4 text-xs flex-grow">
                                        @foreach($request->it_program_config as $configKey => $configVal)
                                            @if(Str::endsWith($configKey, '_check'))
                                                @php 
                                                    $itemKey = str_replace('_check', '', $configKey);
                                                    $opt = $accessOptions->where('key', $itemKey)->first();
                                                @endphp
                                                <div class="border-b border-slate-50 pb-3">
                                                    <p class="font-bold text-slate-400 uppercase tracking-tighter mb-1">{{ $opt ? $opt->name : ucwords(str_replace('_', ' ', $itemKey)) }}:</p>
                                                    <div class="pl-4 space-y-1">
                                                        @if($opt && $opt->custom_fields)
                                                            @foreach($opt->custom_fields as $field)
                                                                @php $val = $request->it_program_config[$itemKey . '_' . Str::snake($field)] ?? '-'; @endphp
                                                                <div class="flex justify-between">
                                                                    <span class="text-slate-400">{{ $field }}:</span>
                                                                    <span class="font-black text-slate-800">{{ $val }}</span>
                                                                </div>
                                                            @endforeach
                                                        @elseif(in_array($itemKey, ['sap_b1', 'rapid_payroll']))
                                                            <div class="flex justify-between">
                                                                <span class="text-slate-400">User:</span>
                                                                <span class="font-black text-slate-800">{{ $request->it_program_config[$itemKey.'_user'] ?? '-' }}</span>
                                                            </div>
                                                            @if($itemKey === 'sap_b1' && isset($request->it_program_config['sap_b1_level']))
                                                                <div class="flex justify-between">
                                                                    <span class="text-slate-400">Level:</span>
                                                                    <span class="font-black text-slate-800">{{ $request->it_program_config['sap_b1_level'] }}</span>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <span class="font-black text-slate-800">{{ is_string($configVal) ? $configVal : 'CHECKED' }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="mt-8 pt-6 border-t border-slate-100 space-y-2">
                                        <div class="text-[10px] flex justify-between"><span class="font-black text-slate-400 uppercase">สถานะ:</span> <span class="{{ ($request->it_program_config['status'] ?? '') == 'completed' ? 'text-green-600' : 'text-yellow-600' }} font-black uppercase tracking-widest">{{ $request->it_program_config['status'] ?? 'Pending' }}</span></div>
                                        <div class="text-[10px] flex justify-between"><span class="font-black text-slate-400 uppercase">ผู้ดำเนินการ:</span> <span class="font-bold text-slate-700">{{ $request->itStaff->fullname ?? '-' }}</span></div>
                                        <div class="text-[9px] text-slate-400 italic text-right mt-2">วันที่: {{ $request->it_configured_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- IT Configuration Form (SAME AS FRONTEND) --}}
                            <div class="no-print">
                                @if(Auth::user()->role === 'admin' || Auth::user()->dept_id == 16)
                                    <form action="{{ route('backend.approvals.complete', $request->id) }}" method="POST" class="bg-white p-0 rounded-[2rem] border-2 border-slate-300 overflow-hidden shadow-2xl">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                                            {{-- System Access Inputs --}}
                                            <div class="p-10 border-r-2 border-slate-300 bg-slate-50/30 flex flex-col h-full">
                                                <div class="flex-grow space-y-8">
                                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">การเข้าถึงระบบ</h4>
                                                    <div class="space-y-6">
                                                        @foreach($sysAcc as $key => $val)
                                                            @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                                                @php $opt = $accessOptions->where('key', $key)->first(); @endphp
                                                                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                                                    <label class="flex items-center gap-3 text-xs font-black text-slate-600 {{ $opt && $opt->custom_fields ? 'mb-4' : '' }} cursor-pointer uppercase tracking-tight">
                                                                        <input type="checkbox" name="it_system_config[{{ $key }}_check]" value="1" checked class="w-5 h-5 rounded border-slate-300 text-blue-600"> 
                                                                        {{ $opt ? $opt->name : ucwords(str_replace('_', ' ', $key)) }}
                                                                    </label>
                                                                    
                                                                    @if($opt && $opt->custom_fields)
                                                                        <div class="grid grid-cols-1 gap-3 pl-8">
                                                                            @foreach($opt->custom_fields as $field)
                                                                                <input type="text" name="it_system_config[{{ $key }}_{{ Str::snake($field) }}]" placeholder="{{ $field }}" class="w-full text-sm py-3 px-4 rounded-xl border-slate-200 bg-slate-50">
                                                                            @endforeach
                                                                        </div>
                                                                    @elseif(in_array($key, ['login_computer', 'email']))
                                                                        {{-- Fallback for legacy hardcoded keys if not in AccessOptions --}}
                                                                        <div class="grid grid-cols-1 gap-3 pl-8">
                                                                            <input type="text" name="it_system_config[{{ $key }}_user]" placeholder="User name" class="w-full text-sm py-3 px-4 rounded-xl border-slate-200 bg-slate-50">
                                                                            <input type="text" name="it_system_config[{{ $key }}_pass]" placeholder="Password" class="w-full text-sm py-3 px-4 rounded-xl border-slate-200 bg-slate-50">
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="pt-8 mt-10 border-t border-slate-200 flex items-center justify-between">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">สถานะ:</span>
                                                    <div class="flex gap-6">
                                                        <label class="flex items-center gap-2 text-[11px] font-black text-green-600 cursor-pointer uppercase"><input type="radio" name="it_system_config[status]" value="completed" checked> Complete</label>
                                                        <label class="flex items-center gap-2 text-[11px] font-black text-yellow-600 cursor-pointer uppercase"><input type="radio" name="it_system_config[status]" value="pending"> Pending</label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Program Access Inputs --}}
                                            <div class="p-10 bg-slate-50/30 flex flex-col h-full">
                                                <div class="flex-grow space-y-8">
                                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">การเข้าถึงโปรแกรม</h4>
                                                    <div class="space-y-6">
                                                        @foreach($progAcc as $key => $val)
                                                            @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                                                @php $opt = $accessOptions->where('key', $key)->first(); @endphp
                                                                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                                                    <label class="flex items-center gap-3 text-xs font-black text-slate-600 {{ ($opt && $opt->custom_fields) || in_array($key, ['sap_b1', 'rapid_payroll']) ? 'mb-4' : '' }} cursor-pointer uppercase tracking-tight">
                                                                        <input type="checkbox" name="it_program_config[{{ $key }}_check]" value="1" checked class="w-5 h-5 rounded border-slate-300 text-indigo-600"> 
                                                                        {{ $opt ? $opt->name : ucwords(str_replace('_', ' ', $key)) }}
                                                                    </label>
                                                                    
                                                                    @if($opt && $opt->custom_fields)
                                                                        <div class="grid grid-cols-1 gap-3 pl-8">
                                                                            @foreach($opt->custom_fields as $field)
                                                                                <input type="text" name="it_program_config[{{ $key }}_{{ Str::snake($field) }}]" placeholder="{{ $field }}" class="w-full text-sm py-3 px-4 rounded-xl border-slate-200 bg-slate-50">
                                                                            @endforeach
                                                                        </div>
                                                                    @elseif(in_array($key, ['sap_b1', 'rapid_payroll']))
                                                                        <div class="grid grid-cols-1 gap-3 pl-8">
                                                                            <input type="text" name="it_program_config[{{ $key }}_user]" placeholder="User name" class="w-full text-sm py-3 px-4 rounded-xl border-slate-200 bg-slate-50 mb-2">
                                                                            @if($key === 'sap_b1')
                                                                                <div class="flex flex-wrap gap-2 text-[9px] font-black text-slate-400 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                                                    <span class="w-full mb-1 text-slate-500 uppercase tracking-widest">Level:</span>
                                                                                    <label class="flex items-center gap-1.5 cursor-pointer hover:text-indigo-600"><input type="radio" name="it_program_config[sap_b1_level]" value="Pro"> PRO</label>
                                                                                    <label class="flex items-center gap-1.5 cursor-pointer hover:text-indigo-600"><input type="radio" name="it_program_config[sap_b1_level]" value="CRM"> CRM</label>
                                                                                    <label class="flex items-center gap-1.5 cursor-pointer hover:text-indigo-600"><input type="radio" name="it_program_config[sap_b1_level]" value="Logistics"> LOGIS</label>
                                                                                    <label class="flex items-center gap-1.5 cursor-pointer hover:text-indigo-600"><input type="radio" name="it_program_config[sap_b1_level]" value="Financials"> FIN</label>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="pt-8 mt-10 border-t border-slate-200 flex items-center justify-between">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">สถานะ:</span>
                                                    <div class="flex gap-6">
                                                        <label class="flex items-center gap-2 text-[11px] font-black text-green-600 cursor-pointer uppercase"><input type="radio" name="it_program_config[status]" value="completed" checked> Complete</label>
                                                        <label class="flex items-center gap-2 text-[11px] font-black text-yellow-600 cursor-pointer uppercase"><input type="radio" name="it_program_config[status]" value="pending"> Pending</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="p-8 bg-slate-100 border-t-2 border-slate-300 flex justify-center">
                                            <button type="submit" class="w-full max-w-sm py-5 bg-blue-600 text-white rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-100 transform hover:-translate-y-1 active:scale-95">
                                                ยืนยันการดำเนินงาน IT
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @else
                         <div class="p-16 text-center bg-slate-50 rounded-[2.5rem] border-4 border-dashed border-slate-200">
                            <p class="text-sm font-black text-slate-400 italic uppercase tracking-widest">รอการอนุมัติให้ครบทุกลำดับก่อนดำเนินการขั้นตอนนี้</p>
                        </div>
                    @endif
                </div>

                {{-- ส่วนที่ 4: สำหรับผู้ใช้งาน --}}
                <div class="space-y-8 pt-12 border-t-2 border-slate-200">
                     <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest bg-slate-100 py-3 px-6 rounded-2xl">ส่วนที่ 4 สำหรับผู้ใช้งาน</h3>
                     <p class="text-xs text-slate-600 leading-relaxed font-medium">
                        ข้าพเจ้าได้รับข้อมูลผู้ใช้งานและรหัสผ่านเป็นที่เรียบร้อยแล้ว และได้ทำการเปลี่ยนแปลงแก้ไขรหัสผ่านในครั้งแรกที่เข้าใช้งานและจะเก็บข้อมูลดังกล่าวเป็นความลับ
                     </p>
                     
                     <div class="flex justify-end pt-6">
                        <div class="text-center w-72 border-t border-slate-200 pt-4">
                             <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">ผู้ใช้งาน (User)</p>
                             <div class="h-12 flex items-center justify-center">
                                 @if($request->user_acknowledged_at)
                                     <p class="text-slate-800 font-bold text-xs">ยืนยันแล้ว</p>
                                 @else
                                     <p class="text-slate-300 italic text-[10px]">....................................................................</p>
                                 @endif
                             </div>
                             <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">วันที่ {{ $request->user_acknowledged_at ? $request->user_acknowledged_at->format('d/m/Y') : '........./........./.........' }}</p>
                        </div>
                     </div>
                </div>
            </div>
        </div>

        {{-- Interaction สำหรับผู้อนุมัติ (IF STILL PENDING) --}}
        @php
            $currentStep = $request->steps->where('step_order', $request->current_step)->where('status', 'pending')->first();
            $isAdmin = Auth::user()->role === 'admin';
            $isMyTurn = $currentStep && ($currentStep->approver_id == Auth::id() || $isAdmin);
        @endphp

        @if($isMyTurn)
            <div class="mt-12 no-print animate-fade-in" x-data="{ showForm: false, actionType: '', useExisting: {{ Auth::user()->signature ? 'true' : 'false' }} }">
                <div x-show="!showForm" class="bg-blue-600 rounded-[2.5rem] p-12 text-center shadow-2xl shadow-blue-200 border border-blue-500">
                    <h3 class="text-2xl font-black text-white mb-3 uppercase tracking-tight">{{ $isAdmin && $currentStep->approver_id != Auth::id() ? 'Administrator Action' : 'กรุณาดำเนินการอนุมัติ' }}</h3>
                    <p class="text-blue-100 text-sm font-bold uppercase tracking-widest mb-8">ลำดับที่ {{ $currentStep->step_order }}: {{ $currentStep->step_name }}</p>
                    <div class="flex items-center justify-center gap-6">
                        <button @click="showForm = true; actionType = 'approve'" class="px-12 py-4 bg-white text-blue-600 rounded-2xl font-black text-sm uppercase tracking-widest hover:scale-105 transition shadow-xl shadow-blue-800/20">อนุมัติคำร้อง</button>
                        <button @click="showForm = true; actionType = 'reject'" class="px-12 py-4 bg-red-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-red-600 hover:scale-105 transition shadow-xl shadow-red-900/20">ปฏิเสธ</button>
                    </div>
                </div>

                <div x-show="showForm" class="bg-white rounded-[2.5rem] p-12 shadow-2xl border-2 border-slate-100">
                    <form :action="actionType === 'approve' ? '{{ route('backend.approvals.approve', $currentStep->id ?? 0) }}' : '{{ route('backend.approvals.reject', $currentStep->id ?? 0) }}'" method="POST" id="approval-form">
                        @csrf
                        <div class="space-y-8">
                            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight" x-text="actionType === 'approve' ? 'ยืนยันการอนุมัติ' : 'ยืนยันการปฏิเสธ'"></h3>
                            <textarea name="remark" rows="4" class="w-full rounded-[1.5rem] border-2 border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all p-6 text-sm font-medium" placeholder="ระบุหมายเหตุการตัดสินใจ (ถ้ามี)..."></textarea>
                            
                            <div x-show="actionType === 'approve'" class="space-y-6">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">ลงนามลายมือชื่อ</label>
                                @if(Auth::user()->signature)
                                    <label class="flex items-center gap-3 cursor-pointer bg-slate-50 p-4 rounded-2xl border-2 border-slate-100 hover:border-blue-500 transition-all group">
                                        <input type="checkbox" name="use_existing" value="1" x-model="useExisting" class="w-5 h-5 rounded border-slate-300 text-blue-600">
                                        <span class="text-xs text-slate-600 font-black uppercase tracking-tight group-hover:text-blue-600">ใช้ลายเซ็นที่บันทึกในระบบ (Digital Signature)</span>
                                    </label>
                                    <div x-show="useExisting" class="p-8 bg-slate-50/50 rounded-[2rem] border-4 border-dashed border-slate-100 text-center">
                                        <img src="{{ asset('storage/signatures/' . Auth::user()->signature) }}" class="h-24 w-auto mx-auto grayscale opacity-40">
                                    </div>
                                @endif
                                <div x-show="!useExisting" class="bg-slate-50 border-2 border-slate-100 rounded-[2rem] overflow-hidden shadow-inner">
                                    <canvas id="approval-signature-pad" class="w-full h-56 touch-none"></canvas>
                                    <input type="hidden" name="signature" id="approval-signature-input">
                                </div>
                            </div>
                            
                            <div class="flex gap-4 pt-4">
                                <button type="button" @click="showForm = false" class="flex-1 py-5 bg-slate-100 text-slate-500 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-slate-200 transition">ยกเลิก</button>
                                <button type="submit" class="flex-[2] py-5 rounded-2xl font-black text-sm uppercase tracking-widest text-white shadow-2xl transition" :class="actionType === 'approve' ? 'bg-blue-600 shadow-blue-200' : 'bg-red-500 shadow-red-200'">
                                    ยืนยันดำเนินการ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('approval-signature-pad');
            if (canvas) {
                const signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgba(0,0,0,0)', penColor: 'rgb(30,41,59)' });
                function resize() {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                    signaturePad.clear();
                }
                window.onresize = resize; resize();
                document.getElementById('approval-form').addEventListener('submit', (e) => {
                    const alpine = document.querySelector('[x-data]').__x.$data;
                    if (alpine.actionType === 'approve' && !alpine.useExisting) {
                        if (signaturePad.isEmpty()) { e.preventDefault(); alert('กรุณาลงนามลายมือชื่อ'); return; }
                        document.getElementById('approval-signature-input').value = signaturePad.toDataURL();
                    }
                });
            }
        });
    </script>
@endsection

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; font-family: 'Sarabun', sans-serif; font-size: 10px; }
        .max-w-5xl { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .printable-content { border: 2px solid #000 !important; border-radius: 0 !important; box-shadow: none !important; }
        .rounded-3xl, .rounded-2xl, .rounded-xl { border-radius: 0 !important; border: 1px solid #000 !important; }
        .bg-slate-50, .bg-blue-50, .bg-green-50, .bg-slate-100 { background: white !important; border: 1px solid #000 !important; }
        h2 { font-size: 18px !important; }
        h3 { background: #f0f0f0 !important; border: 1px solid #000 !important; color: black !important; padding: 5px !important; }
        .border-l-4 { border-left-width: 1px !important; }
    }
</style>
