@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 ">
            <div class="mb-4 no-print flex flex-col sm:flex-row gap-4 justify-between items-center px-2">
                <a href="{{ route('tracking.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-700 font-bold text-sm transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    กลับไปที่รายการคำร้อง
                </a>
                <div class="flex gap-2 w-full sm:w-auto">
                     @if($request->user_id === Auth::id() && $request->status === 'pending')
                        <form action="{{ route('tracking.destroy', $request->request_no) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิกคำร้องนี้?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold text-sm hover:bg-red-600 hover:text-white transition-all">
                                ยกเลิกคำร้อง
                            </button>
                        </form>
                     @endif
                     <a href="{{ route('tracking.print', $request->request_no) }}" class="flex-1 sm:flex-none text-center px-6 py-2 bg-slate-900 text-white rounded-xl font-bold text-sm hover:scale-105 transition-all">
                        พิมพ์ใบคำร้อง (PDF)
                     </a>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200 printable-content">
                <!-- Header -->
                <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="text-3xl font-bold text-[#E10023] tracking-tighter select-none mr-2" style="font-family: 'Outfit', 'Inter', sans-serif;">Kumwell</div>
                        <div>
                            <h2 class="text-lg md:text-xl font-semibold text-slate-800 leading-tight">แบบฟอร์มการร้องขอสิทธิใช้งานเทคโนโลยีสารสนเทศ</h2>
                            <p class="text-[9px] md:text-[10px] text-slate-500">QF-IT-08: Rev: 02 (06-07-20)</p>
                        </div>
                    </div>
                    <div class="text-left md:text-right flex flex-col items-start md:items-end gap-2 w-full md:w-auto">
                        <p class="text-xs text-slate-400">Request No: <span class="text-blue-600 font-bold">{{ $request->request_no }}</span></p>
                        @if($request->status == 'pending')
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-[10px] font-bold uppercase border border-yellow-200">รออนุมัติ</span>
                        @elseif($request->status == 'completed')
                            <span class="px-3 py-1 rounded-full bg-green-600 text-white text-[10px] font-bold uppercase border border-green-600 shadow-sm">เสร็จสมบูรณ์</span>
                        @elseif($request->status == 'approved' && $request->it_status == 'completed')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold uppercase border border-blue-200">รอคุณยืนยัน</span>
                        @elseif($request->status == 'approved')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold uppercase border border-blue-200">อนุมัติแล้ว</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-[10px] font-bold uppercase border border-red-200">ถูกปฏิเสธ</span>
                        @endif
                    </div>
                </div>

                <div class="p-6 space-y-10">
                    {{-- ส่วนที่ 1: ผู้ร้องขอ --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg">ส่วนที่ 1 ผู้ร้องขอ</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                            <div class="md:col-span-3">
                                 <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">ประเภทคำร้อง</label>
                                 <p class="font-bold text-slate-800">
                                    @php $types = ['new_employee' => 'พนักงานใหม่', 'resign' => 'ลาออก', 'position_change' => 'ปรับตำแหน่ง', 'transfer' => 'โอนย้าย', 'add_remove_access' => 'เพิ่มสิทธิ์/ลบสิทธิ์']; @endphp
                                    {{ $types[$request->request_type] ?? $request->request_type }}
                                 </p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">ชื่อ-นามสกุล</label>
                                <p class="text-sm text-slate-700 font-medium">{{ $request->firstname }} {{ $request->lastname }} ({{ $request->nickname_th ?: '-' }})</p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">รหัสพนักงาน</label>
                                <p class="text-sm text-slate-700 font-bold">{{ $request->emp_code }}</p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">แผนก/ฝ่าย</label>
                                <p class="text-sm text-slate-700 font-medium">{{ $request->department_name }} {{ $request->division_name ? '/ ' . $request->division_name : '' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- ส่วนที่ 2: การเข้าถึง --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg">ส่วนที่ 2 การเข้าถึง (ตามความประสงค์)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border border-slate-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-slate-500 mb-3 underline italic uppercase">ระบบที่ต้องการเข้าถึง</h4>
                                <ul class="text-xs space-y-1 text-slate-700">
                                    @php $hasSystem = false; @endphp
                                    @foreach($request->system_access ?? [] as $key => $val)
                                        @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                            <li>• {{ ucwords(str_replace('_', ' ', $key)) }}
                                                @if(isset($request->system_access[$key.'_sub']))
                                                    <span class="text-[10px] text-blue-500">({{ is_array($request->system_access[$key.'_sub']) ? implode(', ', $request->system_access[$key.'_sub']) : $request->system_access[$key.'_sub'] }})</span>
                                                @endif
                                            </li>
                                            @php $hasSystem = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(isset($request->system_access['other_check']) && $request->system_access['other_check'])
                                        <li>• Other: {{ $request->system_access['other_text'] ?? '-' }}</li>
                                        @php $hasSystem = true; @endphp
                                    @endif
                                    @if(!$hasSystem) <li>-</li> @endif
                                </ul>
                            </div>
                            <div class="border border-slate-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-slate-500 mb-3 underline italic uppercase">โปรแกรมที่ต้องการ</h4>
                                <ul class="text-xs space-y-1 text-slate-700">
                                    @php $hasProgram = false; @endphp
                                    @foreach($request->program_access ?? [] as $key => $val)
                                        @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                            <li>• {{ ucwords(str_replace('_', ' ', $key)) }}
                                                @if(isset($request->program_access[$key.'_sub']))
                                                    <span class="text-[10px] text-orange-500">({{ is_array($request->program_access[$key.'_sub']) ? implode(', ', $request->program_access[$key.'_sub']) : $request->program_access[$key.'_sub'] }})</span>
                                                @endif
                                            </li>
                                            @php $hasProgram = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(isset($request->program_access['other_check']) && $request->program_access['other_check'])
                                        <li>• Other: {{ $request->program_access['other_text'] ?? '-' }}</li>
                                        @php $hasProgram = true; @endphp
                                    @endif
                                    @if(!$hasProgram) <li>-</li> @endif
                                </ul>
                            </div>
                            <div class="border border-slate-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-slate-500 mb-3 underline italic uppercase">อุปกรณ์การใช้งาน</h4>
                                <ul class="text-xs space-y-1 text-slate-700">
                                    @php $hasEquip = false; @endphp
                                    @foreach($request->equipment_access ?? [] as $key => $val)
                                        @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                            <li>• {{ ucwords(str_replace('_', ' ', $key)) }}
                                                @if(isset($request->equipment_access[$key.'_sub']))
                                                    <span class="text-[10px] text-green-600">({{ is_array($request->equipment_access[$key.'_sub']) ? implode(', ', $request->equipment_access[$key.'_sub']) : $request->equipment_access[$key.'_sub'] }})</span>
                                                @endif
                                            </li>
                                            @php $hasEquip = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(isset($request->equipment_access['other_check']) && $request->equipment_access['other_check'])
                                        <li>• Other: {{ $request->equipment_access['other_text'] ?? '-' }}</li>
                                        @php $hasEquip = true; @endphp
                                    @endif
                                    @if(!$hasEquip) <li>-</li> @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- ลำดับการอนุมัติ --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-100">
                        <div class="flex flex-col items-center p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">ลายมือชื่อผู้ร้องขอ</label>
                            @if($request->signature_path)
                                <img src="{{ asset('storage/' . $request->signature_path) }}" alt="Signature" class="h-16 w-auto mb-2">
                            @else
                                <div class="h-16 flex items-center text-slate-300 italic text-xs">ไม่พบข้อมูล</div>
                            @endif
                            <div class="w-32 h-px bg-slate-200 mb-1"></div>
                            <p class="text-[10px] font-bold text-slate-700">{{ $request->firstname }} {{ $request->lastname }}</p>
                            <p class="text-[8px] text-slate-400">{{ $request->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ลำดับการอนุมัติ</label>
                            @foreach($request->steps as $step)
                                <div class="flex items-center justify-between p-3 rounded-xl border {{ $step->status == 'approved' ? 'bg-green-50 border-green-100' : ($step->status == 'rejected' ? 'bg-red-50 border-red-100' : 'bg-white border-slate-100') }}">
                                    <div class="flex items-center gap-3">
                                        <div class="h-6 w-6 rounded-full flex items-center justify-center text-[10px] font-bold {{ $step->status == 'approved' ? 'bg-green-500 text-white' : ($step->status == 'rejected' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-400') }}">
                                            {{ $step->step_order }}
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold text-slate-700">{{ $step->step_name }}</p>
                                            @if($step->approver)
                                                <p class="text-[9px] text-slate-500">{{ $step->approver->fullname }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[9px] font-bold uppercase {{ $step->status == 'approved' ? 'text-green-600' : ($step->status == 'rejected' ? 'text-red-600' : 'text-slate-400') }}">
                                            {{ $step->status == 'pending' ? 'รออนุมัติ' : ($step->status == 'approved' ? 'อนุมัติแล้ว' : 'ปฏิเสธ') }}
                                        </span>
                                        @if($step->status == 'approved' && $step->approved_at)
                                            <p class="text-[8px] text-slate-400 mt-0.5">{{ $step->approved_at->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ส่วนที่ 3: สำหรับเจ้าหน้าที่ --}}
                    <div class="space-y-6 pt-10 border-t-2 border-slate-200">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg">ส่วนที่ 3 สำหรับเจ้าหน้าที่</h3>

                        @if($request->status == 'approved' || $request->status == 'completed')
                            @if($request->it_status == 'completed')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-2 border-slate-300 rounded-xl overflow-hidden">
                                    {{-- Left Box: System Access Results --}}
                                    <div class="p-6 border-r-2 border-slate-300 flex flex-col">
                                        <h4 class="text-xs font-bold text-slate-600 border-b pb-2 mb-4">การเข้าถึงระบบ</h4>
                                        <div class="space-y-3 text-[11px] flex-grow">
                                            @if(isset($request->it_system_config['login_computer_check']))
                                                <div class="flex justify-between border-b border-slate-100 pb-1">
                                                    <span class="font-bold text-slate-500">Computer Access:</span>
                                                    <span>{{ $request->it_system_config['login_computer_user'] ?? '-' }} / {{ $request->it_system_config['login_computer_pass'] ?? '********' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($request->it_system_config['email_check']))
                                                <div class="flex justify-between border-b border-slate-100 pb-1">
                                                    <span class="font-bold text-slate-500">Email Address:</span>
                                                    <span>{{ $request->it_system_config['email_user'] ?? '-' }} / {{ $request->it_system_config['email_pass'] ?? '********' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($request->it_system_config['file_server_check']))
                                                <div class="flex justify-between border-b border-slate-100 pb-1">
                                                    <span class="font-bold text-slate-500">File Server:</span>
                                                    <span>อนุญาตแล้ว</span>
                                                </div>
                                            @endif

                                            {{-- Dynamic Generic Systems --}}
                                            @foreach($request->it_system_config['generic'] ?? [] as $gName => $gVal)
                                                <div class="flex justify-between border-b border-slate-100 pb-1">
                                                    <span class="font-bold text-slate-500">{{ $gName }}:</span>
                                                    <span>{{ $gVal['user'] ?? '-' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-6 pt-4 border-t border-slate-200 space-y-1">
                                            <div class="text-[10px] flex justify-between"><span class="font-bold">สถานะ:</span> <span class="{{ ($request->it_system_config['status'] ?? '') == 'completed' ? 'text-green-600' : 'text-yellow-600' }} font-bold uppercase">{{ $request->it_system_config['status'] ?? 'Pending' }}</span></div>
                                            <div class="text-[10px] flex justify-between"><span class="font-bold">ผู้ดำเนินการ:</span> {{ $request->itStaff->fullname ?? '-' }}</div>
                                            <div class="text-[9px] text-slate-400 italic text-right">วันที่: {{ $request->it_configured_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                    {{-- Right Box: Program Access Results --}}
                                    <div class="p-6 flex flex-col">
                                        <h4 class="text-xs font-bold text-slate-600 border-b pb-2 mb-4">การเข้าถึงโปรแกรม</h4>
                                        <div class="space-y-3 text-[11px] flex-grow">
                                            @if(isset($request->it_program_config['sap_b1_check']))
                                                <div class="flex justify-between border-b border-slate-100 pb-1">
                                                    <span class="font-bold text-slate-500">SAP B1:</span>
                                                    <span>{{ $request->it_program_config['sap_b1_user'] ?? '-' }} ({{ $request->it_program_config['sap_b1_level'] ?? '-' }})</span>
                                                </div>
                                            @endif
                                            @if(isset($request->it_program_config['rapid_payroll_check']))
                                                <div class="flex justify-between border-b border-slate-100 pb-1">
                                                    <span class="font-bold text-slate-500">Rapid Payroll:</span>
                                                    <span>{{ $request->it_program_config['rapid_payroll_user'] ?? '-' }}</span>
                                                </div>
                                            @endif

                                            {{-- Dynamic Generic Programs --}}
                                            @foreach($request->it_program_config['generic'] ?? [] as $gName => $gVal)
                                                <div class="flex justify-between border-b border-slate-100 pb-1">
                                                    <span class="font-bold text-slate-500">{{ $gName }}:</span>
                                                    <span>{{ $gVal['user'] ?? '-' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-6 pt-4 border-t border-slate-200 space-y-1">
                                            <div class="text-[10px] flex justify-between"><span class="font-bold">สถานะ:</span> <span class="{{ ($request->it_program_config['status'] ?? '') == 'completed' ? 'text-green-600' : 'text-yellow-600' }} font-bold uppercase">{{ $request->it_program_config['status'] ?? 'Pending' }}</span></div>
                                            <div class="text-[10px] flex justify-between"><span class="font-bold">ผู้ดำเนินการ:</span> {{ $request->itStaff->fullname ?? '-' }}</div>
                                            <div class="text-[9px] text-slate-400 italic text-right">วันที่: {{ $request->it_configured_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- IT ยังไม่ดำเนินการ - แสดงสถานะรอ สำหรับทุกคน --}}
                                <div class="p-16 text-center bg-gradient-to-b from-blue-50 to-white rounded-3xl border-2 border-blue-100">
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-6">
                                        <svg class="w-10 h-10 text-blue-500 animate-spin" style="animation-duration: 3s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-blue-600 mb-2">กำลังดำเนินการ</h4>
                                    <p class="text-sm text-slate-500 font-medium">รอเจ้าหน้าที่เทคโนโลยีสารสนเทศกำลังดำเนินการตั้งค่าระบบ</p>
                                    <p class="text-xs text-slate-400 mt-2">กรุณารอสักครู่ ระบบจะอัปเดตสถานะเมื่อดำเนินการเสร็จสิ้น</p>
                                </div>
                            @endif
                        @else
                             <div class="p-10 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                                <p class="text-sm font-bold text-slate-400 italic">รอการอนุมัติให้ครบทุกลำดับก่อนดำเนินการขั้นตอนนี้</p>
                            </div>
                        @endif
                    </div>

                    {{-- ส่วนที่ 4: สำหรับผู้ใช้งาน --}}
                    <div class="space-y-6 pt-10 border-t-2 border-slate-200">
                         <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg">ส่วนที่ 4 สำหรับผู้ใช้งาน</h3>

                         <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                            <p class="text-[13px] text-slate-700 leading-relaxed text-center font-medium">
                               "ข้าพเจ้าได้รับข้อมูลผู้ใช้งานและรหัสผ่านเป็นที่เรียบร้อยแล้ว และได้ทำการเปลี่ยนแปลงแก้ไขรหัสผ่านในครั้งแรกที่เข้าใช้งานและจะเก็บข้อมูลดังกล่าวเป็นความลับ"
                            </p>

                            @if($request->user_acknowledged_at)
                                <div class="bg-green-50 rounded-xl p-4 border border-green-100 flex items-center justify-center gap-3 text-green-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="font-bold uppercase tracking-widest text-xs">ยืนยันรับทราบข้อมูลผู้ใช้งานแล้ว</span>
                                </div>
                            @else
                                <div class="p-4 bg-blue-50/30 rounded-xl border border-dashed border-blue-200 text-center no-print">
                                    <p class="text-[10px] text-blue-400 italic">กรุณาตรวจสอบข้อมูลและกดยืนยันที่ด้านล่างสุดของเอกสาร</p>
                                </div>
                            @endif
                         </div>
                         </div>

                         <div class="flex justify-end pt-4 print-block">
                            <div class="text-center w-64 border-t border-slate-200 pt-2">
                                 <p class="text-[10px] font-bold text-slate-700">ผู้ใช้งาน (User)</p>
                                 <p class="text-[9px] text-slate-500 mt-1">วันที่ {{ $request->user_acknowledged_at ? $request->user_acknowledged_at->format('d/m/Y') : '........./........./.........' }}</p>
                            </div>
                         </div>
                    </div>



                    {{-- ประกาศ ระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์ --}}
                    <div class="space-y-6 pt-10 border-t-2 border-slate-200">
                        <div class="flex items-center justify-center">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-red-50 text-red-700 py-2 px-4 rounded-lg border border-red-100 text-center">
                                ประกาศ ระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์
                            </h3>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 text-[11px] text-slate-700 leading-relaxed space-y-4 max-h-[500px] overflow-y-auto no-print-scroll">
                            <p class="text-center text-sm font-bold text-slate-800 mb-2">ประกาศ</p>
                            <p class="text-center text-xs font-bold text-slate-700 mb-4">ระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์</p>

                            <p class="indent-8">
                                บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน) ได้จัดให้มีระบบเครือข่ายและคอมพิวเตอร์เพื่อสนับสนุนการดำเนินงานของบริษัทฯ 
                                ให้เกิดประสิทธิภาพ สามารถตอบสนองเป้าหมายทางธุรกิจในประสิทธิผลและรวดเร็วขึ้น ดังนั้น บริษัทฯ จึงจัดว่าระบบเครือข่ายและ
                                คอมพิวเตอร์เป็นสินทรัพย์ที่สำคัญของบริษัทฯ เพื่อให้การใช้งานเป็นไปอย่างเรียบร้อยและเกิดประโยชน์สูงสุด บริษัทฯ จึงได้ประกาศ
                                ระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์ โดยมีสาระสำคัญดังนี้
                            </p>

                            <div class="space-y-4 pl-4">
                                <div>
                                    <p class="font-bold text-slate-800">1. บริษัทฯ จะมอบหมายแผนกเทคโนโลยีสารสนเทศ ดูแลบำรุงรักษา พัฒนาและปรับปรุงระบบเครือข่ายและคอมพิวเตอร์ให้พร้อมใช้งานได้อยู่เสมอ พร้อมทั้งมอบบัญชีผู้ใช้งาน (User Account) และรหัสผ่าน (Password) ให้กับผู้ใช้งานเฉพาะบุคคล โดย ผู้ใช้งานจะต้องปฏิบัติ ดังนี้</p>
                                    <div class="pl-6 space-y-2 mt-2">
                                        <p>a. ผู้ใช้งาน (User Account) และรหัสผ่าน (Password) ถือเป็นความลับเฉพาะบุคคล ต้องเก็บและรักษาเป็นความลับ ไม่ให้ผู้อื่นนำไปใช้งานได้อย่างเด็ดขาด</p>
                                        <p>b. ผู้ใช้งานจะต้องเก็บรักษารหัสผ่าน (Password) ไว้เป็นความลับ โดยไม่อาจระ ทำการแสดงถึงการเปิดเผยรหัสผ่าน (Password) ให้ผู้อื่นทราบ และไม่ใช้โปรแกรมคอมพิวเตอร์ช่วยจำรหัสผ่านอัตโนมัติ (Save Password) สำหรับเครื่องคอมพิวเตอร์ที่ผู้ใช้งานใช้ปฏิบัติงาน</p>
                                        <p>c. ผู้ใช้งานต้องไม่ติดตั้ง Software หรือนำอุปกรณ์ Hardware ส่วนบุคคลที่เกี่ยวข้องกับงานและบอกเหตุข้อจำเป็นกับบริษัทฯ ก่อนนำ หากมีความจำเป็นต้องแจ้งผู้รับผิดชอบ และแผนกเทคโนโลยีสารสนเทศ เพื่อของอนุมัติก่อน</p>
                                    </div>
                                </div>

                                <p>d. ผู้ใช้งานต้องไม่ ตัดต่อ เปิดเผย หรืออ่ายโอนข้อมูลข้อมูลไปรูปแบบการ Upload, Download หรือวิธีการใดก็ตามที่อาจก่อให้ เสียหายแก่บริษัทฯ โดยเด็ดขาด ดังเช่น ข้อมูล สิ่งส่งพิมพ์อิเล็กทรอนิกส์ที่เป็นการละเมิดลิขสิทธิ์หรือเป็นข้อมูลของผู้ร้อง จ่ออื่น ข้อมูลที่เป็นความลับในเรื่องเกี่ยวกับธุรกิจ พนักงาน หรือบุคคลภายนอก เป็นต้น</p>

                                <div>
                                    <p class="font-bold text-slate-800">2. เมื่อผู้ใช้งานว่างจากการใช้เครื่องคอมพิวเตอร์ส่วนบุคคลเกิน 5 นาที ให้ผู้ใช้งานทำการปกหน้าจอ (Lock Screen) หรือทำการปิดเครื่อง (Shut Down) เมื่อการปฏิบัติงานประจำวันเสร็จสิ้น โดย</p>
                                    <div class="pl-6 space-y-2 mt-2">
                                        <p>a. กรณีที่ผู้ใช้งานยินยอมให้ผู้อื่นใช้เครื่องคอมพิวเตอร์ของตนเองหรือ ผู้ใช้งานจะต้องทำการออกจากระบบ (Logout) ทันที เพื่อที่ผู้ยืมเข้าทำการเข้าใช้ตัวเอง (Login) ด้วยบัญชีผู้ใช้งาน (User Account) และรหัสผ่าน (Password) ของตนเองเท่านั้น</p>
                                        <p>b. ผู้ใช้งานจะต้องไม่เปิดเผยพาสส์เวิร์ดตนเองให้คนอื่นทราบ หรือบุคคลอื่นใดก็ตามให้รับรู้หรือ กุญแจ ดังเช่น การบุกรุก (Hack) เจาะเข้าระบบ</p>
                                    </div>
                                </div>

                                <p class="font-bold text-slate-800">3. ผู้ใช้งาน (User Account) ของผู้ใช้ที่ถูกตั้งขึ้นมาเพื่อเข้าถึงคอมพิวเตอร์ของบริษัทฯ การเขียน เผยแพร่ข้อความใด ๆ ที่ อาจก่อให้เกิดความเสียหายอาจ เสื่อมเสียแก่ผู้อื่น การใช้เพื่ออุปกรณ์ที่ไม่สุภาพ เป็นต้น หากมีการกระทำเสียหายจากผู้ใช้งาน จะต้องรับผิดชอบชดเชย</p>

                                <p>3. ผู้ใช้งานต้องไม่ปฏิบัติการใด ๆ ที่เป็นการขัดต่อกฎหมายว่าด้วยการประกอบกิจกรรมว่าด้วยคอมพิวเตอร์ ทำให้ผู้ใช้งานบริษัทฯ รับความเสียหาก มีการกระทำการใด ๆ ที่ระบบค่า อ้อมถ้าว่า ย่อมถือว่าผู้ใช้งานผู้นั้นเป็นผิดระบบความรับผิดชอบบริษัทฯ</p>

                                <p class="font-bold text-slate-800">4. ผู้ใช้งานต้องปฏิบัติตามเงื่อนไข กฎระเบียบ ข้อบังคับที่บริษัทฯ กำหนดไว้ โดยเมื่อลงนามในใบสำเร็จ หมี เสมือนประกาศจากบริษัทฯ</p>

                                <p class="font-bold text-slate-800">5. บริษัทฯ ขอสงวนสิทธิ์ในการตรวจสอบเครื่องคอมพิวเตอร์ของผู้ใช้งาน ในกรณีที่พบเห็นการไม่ปฏิบัติตามระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์โดยมีต้องแจ้งให้ทราบล่วงหน้า</p>

                                <div>
                                    <p class="font-bold text-slate-800">6. หากผู้ใช้งานฝ่าฝืนหรือไม่ปฏิบัติตามระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์ บริษัทฯ จะพิจารณาบทลงโทษ ทันที ตามความเสียหายที่เกิดขึ้น ดังต่อไปนี้</p>
                                    <ul class="list-disc pl-10 mt-2 space-y-1">
                                        <li>ตักเตือนด้วยวาจา</li>
                                        <li>ตักเตือนเป็นลายลักษณ์อักษร</li>
                                        <li>ไม่พิจารณาขึ้นเงินเดือน</li>
                                        <li>เลิกจ้างโดยไม่จ่ายค่าชดเชย</li>
                                        <li>ฟ้องร้องและเรียกค่าเสียหาย</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Consolidated Acceptance Section --}}
                        <div class="bg-blue-50/50 rounded-2xl border-2 border-blue-100 p-8 space-y-6 no-print">
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">1</div>
                                    <p class="text-xs text-slate-700 font-bold leading-relaxed">
                                        ข้าพเจ้าได้รับข้อมูลผู้ใช้งานและรหัสผ่านเป็นที่เรียบร้อยแล้ว และได้ทำการเปลี่ยนแปลงแก้ไขรหัสผ่านในครั้งแรกที่เข้าใช้งานและจะเก็บข้อมูลดังกล่าวเป็นความลับ
                                    </p>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0 mt-0.5">2</div>
                                    <p class="text-xs text-slate-700 font-bold leading-relaxed">
                                        ข้าพเจ้าได้อ่านและทำความเข้าใจระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์ของบริษัทฯ แล้ว ข้าพเจยินยอมที่จะปฏิบัติตามระเบียบดังกล่าวอย่างเคร่งครัด
                                    </p>
                                </div>
                            </div>

                            @if(!$request->user_acknowledged_at)
                                @if($request->it_status === 'completed' && $request->user_id === Auth::id())
                                    <form action="{{ route('tracking.acknowledge', $request->request_no) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xl shadow-xl shadow-blue-200 transition transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            ยืนยันรับทราบและยอมรับระเบียบ
                                        </button>
                                    </form>
                                @else
                                    <div class="p-4 bg-white/50 rounded-xl border border-dashed border-blue-200 text-center">
                                        <p class="text-sm text-blue-400 italic">
                                            @if($request->it_status !== 'completed')
                                                รอเจ้าหน้าที่ IT ดำเนินการตั้งค่าระบบให้เสร็จสิ้นก่อนจึงจะสามารถกดยืนยันได้
                                            @else
                                                รอดำเนินการยืนยันจากผู้ใช้งาน
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="bg-green-600 rounded-2xl py-4 px-6 text-white flex items-center justify-between shadow-lg shadow-green-100">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div>
                                            <p class="font-bold text-lg">ดำเนินการยืนยันเรียบร้อยแล้ว</p>
                                            <p class="text-xs text-green-100">บันทึกข้อมูลเมื่อ: {{ $request->user_acknowledged_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Print version signature --}}
                        <div class="print-block mt-8">
                            <div class="flex justify-end">
                                <div class="text-center w-64 space-y-1">
                                    <p class="text-[9px] text-slate-500">ลงชื่อ...................................................................ผู้ใช้งานรับทราบ</p>
                                    <p class="text-[9px] text-slate-500">(..............................................................)</p>
                                    <p class="text-[9px] text-slate-500">วันที่........../........../...........</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Interaction สำหรับผู้อนุมัติ --}}
            @php
                $currentStep = $request->steps->where('step_order', $request->current_step)->where('status', 'pending')->first();
                $isAdmin = Auth::user()->role === 'admin';
                $isMyTurn = $currentStep && ($currentStep->approver_id == Auth::id() || $isAdmin);
            @endphp

            @if($isMyTurn)
                <div class="mt-8 no-print animate-fade-in" x-data="{ showForm: false, actionType: '', useExisting: {{ Auth::user()->signature ? 'true' : 'false' }} }">
                    <div x-show="!showForm" class="bg-blue-600 rounded-3xl p-6 md:p-8 text-center shadow-xl shadow-blue-200">
                        <h3 class="text-lg md:text-xl font-bold text-white mb-2">{{ $isAdmin && $currentStep->approver_id != Auth::id() ? 'Administrator Action' : 'กรุณาดำเนินการอนุมัติ' }}</h3>
                        <p class="text-white/70 text-xs md:text-sm mb-6">คำร้องนี้อยู่ในความรับผิดชอบของ: {{ $currentStep->approver->fullname ?? 'N/A' }}</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <button @click="showForm = true; actionType = 'approve'" class="w-full sm:w-auto px-10 py-3 bg-white text-blue-600 rounded-2xl font-bold hover:bg-blue-50 transition">อนุมัติคำร้อง</button>
                            <button @click="showForm = true; actionType = 'reject'" class="w-full sm:w-auto px-10 py-3 bg-red-500 text-white rounded-2xl font-bold hover:bg-red-600 transition">ปฏิเสธ</button>
                        </div>
                    </div>

                    <div x-show="showForm" class="bg-white rounded-3xl p-8 shadow-xl border border-slate-200">
                        <form :action="actionType === 'approve' ? '{{ route('manage.approvals.approve', $currentStep->id ?? 0) }}' : '{{ route('manage.approvals.reject', $currentStep->id ?? 0) }}'" method="POST" id="approval-form">
                            @csrf
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-slate-800" x-text="actionType === 'approve' ? 'ยืนยันการอนุมัติ' : 'ยืนยันการปฏิเสธ'"></h3>
                                <textarea name="remark" rows="3" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500" placeholder="ระบุหมายเหตุการตัดสินใจ (ถ้ามี)..."></textarea>

                                <div x-show="actionType === 'approve'" class="space-y-4">
                                    <label class="block text-sm font-bold text-slate-700">ลงนามลายมือชื่อ</label>
                                    @if(Auth::user()->signature)
                                        <label class="flex items-center gap-2 cursor-pointer mb-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                            <input type="checkbox" name="use_existing" value="1" x-model="useExisting" class="rounded text-blue-600">
                                            <span class="text-xs text-slate-500 font-bold uppercase">ใช้ลายเซ็นที่บันทึกในระบบ</span>
                                        </label>
                                        <div x-show="useExisting" class="p-4 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200 text-center">
                                            <img src="{{ asset('storage/signatures/' . Auth::user()->signature) }}" class="h-20 w-auto mx-auto grayscale opacity-50">
                                        </div>
                                    @endif
                                    <div x-show="!useExisting" class="bg-slate-50 border-2 border-slate-200 rounded-3xl overflow-hidden">
                                        <canvas id="approval-signature-pad" class="w-full h-48 touch-none"></canvas>
                                        <input type="hidden" name="signature" id="approval-signature-input">
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <button type="button" @click="showForm = false" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-bold">ยกเลิก</button>
                                    <button type="submit" class="flex-2 w-full py-4 rounded-2xl font-bold text-white shadow-lg" :class="actionType === 'approve' ? 'bg-blue-600 shadow-blue-100' : 'bg-red-500 shadow-red-100'">
                                        ยืนยันดำเนินการ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif


            {{-- ส่วนที่ 5: ข้อตกลงรักษาความลับ (NDA) --}}
            @if($request->status == 'completed')
            <div class="mt-8 space-y-6 no-print">
                 <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-file-contract text-blue-600"></i>
                    ข้อตกลงรักษาความลับ (NDA)
                 </h3>
                 
                 @php
                    $nda = \App\Models\ConfidentialityAgreement::where('request_form_id', $request->id)->first();
                    $isRequester = Auth::id() == $request->user_id;
                 @endphp

                 <div class="bg-white p-8 rounded-3xl border-2 border-slate-100 shadow-sm transition hover:shadow-md">
                    @if($nda)
                            @if($nda->witness1_agreed_at && $nda->witness2_agreed_at)
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-500">
                                        <i class="fa-solid fa-circle-check text-2xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">บันทึกข้อตกลงรักษาความลับเรียบร้อยแล้ว</h4>
                                        <p class="text-xs text-slate-400">เสร็จสมบูรณ์เมื่อ {{ $nda->witness2_agreed_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500">
                                        <i class="fa-solid fa-clock text-2xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">อยู่ระหว่างรอพยานลงนามรับรอง</h4>
                                        <p class="text-xs text-slate-400">บันทึกข้อมูลเบื้องต้นเมื่อ {{ $nda->agreement_date ? $nda->agreement_date->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="flex gap-2">
                                <a href="{{ route('request.nda', $request->request_no) }}" 
                                    class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                                    ดูรายละเอียด NDA
                                </a>
                            </div>
                        </div>
                    @else
                        @if($isRequester)
                            <div class="text-center space-y-6 py-6">
                                <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-pen-nib text-3xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-800">กรุณาบันทึกข้อตกลงรักษาความลับ</h4>
                                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2">กรุณาดำเนินการบันทึกข้อตกลงรักษาความลับ (NDA) เพื่อให้กระบวนการร้องขอสิทธิใช้งานสารสนเทศสมบูรณ์ตามระเบียบของบริษัท</p>
                                </div>
                                <a href="{{ route('request.nda', $request->request_no) }}" 
                                    class="inline-flex items-center gap-3 px-12 py-4 bg-blue-600 text-white rounded-2xl font-bold uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition transform hover:-translate-y-1">
                                    เริ่มบันทึกข้อตกลงรักษาความลับ
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="flex items-center gap-4 py-4">
                                <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-600 italic">อยู่ระหว่างรอผู้ใช้งานบันทึกข้อตกลงรักษาความลับ</h4>
                                    <p class="text-[10px] text-slate-400">ผู้ขอรับสิทธิจะต้องดำเนินการในส่วนนี้เพื่อให้การร้องขอสมบูรณ์</p>
                                </div>
                            </div>
                        @endif
                    @endif
                 </div>
            </div>
            @endif
        </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('turbo:load', function () {
            // Initialize Approval Signature Pad
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
    .print-block { display: none; }
    @media print {
        .no-print { display: none !important; }
        .print-block { display: block !important; }
        body { background: white !important; font-family: 'Sarabun', sans-serif; font-size: 10px; }
        .max-w-5xl { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .shadow-sm, .rounded-2xl { border: 1px solid #000 !important; box-shadow: none !important; border-radius: 0 !important; }
        .bg-slate-50, .bg-blue-50, .bg-green-50, .bg-slate-100 { background: white !important; border: 1px solid #000 !important; }
        .printable-content { border: 2px solid #000 !important; padding: 10px !important; }
        h2 { font-size: 16px !important; }
        h3 { background: #eee !important; border: 1px solid #000 !important; color: black !important; }
        input[type="checkbox"], input[type="radio"] { appearance: auto !important; -webkit-appearance: checkbox !important; }
        .border-dashed { border-style: solid !important; }
    }
</style>