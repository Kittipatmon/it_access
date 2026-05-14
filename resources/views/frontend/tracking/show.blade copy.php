@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 ">
            <div class="mb-4 no-print flex flex-col sm:flex-row gap-4 justify-between items-center px-2">
                <a href="{{ route('tracking.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-700 font-bold text-sm transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    犧≒ｸ･犧ｱ犧壟ｹ�ｸ巵ｸ伶ｸｵ犹謂ｸ｣犧ｲ犧｢犧≒ｸｲ犧｣犧�ｸｳ犧｣犹霞ｸｭ犧�
                </a>
                <div class="flex gap-2 w-full sm:w-auto">
                     @if($request->user_id === Auth::id() && $request->status === 'pending')
                        <form action="{{ route('tracking.destroy', $request->request_no) }}" method="POST" onsubmit="return confirm('犧�ｸｸ犧内ｹ≒ｸ吭ｹ謂ｹ�ｸ謂ｸｫ犧｣犧ｷ犧ｭ犹�ｸ｡犹謂ｸｧ犹謂ｸｲ犧歩ｹ霞ｸｭ犧�ｸ≒ｸｲ犧｣犧｢犧≒ｹ犧･犧ｴ犧≒ｸ�ｸｳ犧｣犹霞ｸｭ犧�ｸ吭ｸｵ犹�?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold text-sm hover:bg-red-600 hover:text-white transition-all">
                                犧｢犧≒ｹ犧･犧ｴ犧≒ｸ�ｸｳ犧｣犹霞ｸｭ犧�
                            </button>
                        </form>
                     @endif
                     <a href="{{ route('tracking.print', $request->request_no) }}" class="flex-1 sm:flex-none text-center px-6 py-2 bg-slate-900 text-white rounded-xl font-bold text-sm hover:scale-105 transition-all">
                        犧樅ｸｴ犧｡犧樅ｹ呉ｹ�ｸ壟ｸ�ｸｳ犧｣犹霞ｸｭ犧� (PDF)
                     </a>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200 printable-content">
                <!-- Header -->
                <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="text-3xl font-bold text-[#E10023] tracking-tighter select-none mr-2" style="font-family: 'Outfit', 'Inter', sans-serif;">Kumwell</div>
                        <div>
                            <h2 class="text-lg md:text-xl font-semibold text-slate-800 leading-tight">犹≒ｸ壟ｸ壟ｸ游ｸｭ犧｣犹呉ｸ｡犧≒ｸｲ犧｣犧｣犹霞ｸｭ犧�ｸもｸｭ犧ｪ犧ｴ犧伶ｸ倨ｸｴ犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ犧伶ｸ�ｹもｸ吭ｹもｸ･犧｢犧ｵ犧ｪ犧ｲ犧｣犧ｪ犧吭ｹ犧伶ｸｨ</h2>
                            <p class="text-[9px] md:text-[10px] text-slate-500">QF-IT-08: Rev: 02 (06-07-20)</p>
                        </div>
                    </div>
                    <div class="text-left md:text-right flex flex-col items-start md:items-end gap-2 w-full md:w-auto">
                        <p class="text-xs text-slate-400">Request No: <span class="text-blue-600 font-bold">{{ $request->request_no }}</span></p>
                        @if($request->status == 'pending')
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-[10px] font-bold uppercase border border-yellow-200">犧｣犧ｭ犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ</span>
                        @elseif($request->status == 'completed')
                            <span class="px-3 py-1 rounded-full bg-green-600 text-white text-[10px] font-bold uppercase border border-green-600 shadow-sm">犹犧ｪ犧｣犹�ｸ謂ｸｪ犧｡犧壟ｸｹ犧｣犧内ｹ�</span>
                        @elseif($request->status == 'approved' && $request->it_status == 'completed')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold uppercase border border-blue-200">犧｣犧ｭ犧�ｸｸ犧内ｸ｢犧ｷ犧吭ｸ｢犧ｱ犧�</span>
                        @elseif($request->status == 'approved')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold uppercase border border-blue-200">犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ犹≒ｸ･犹霞ｸｧ</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-[10px] font-bold uppercase border border-red-200">犧籾ｸｹ犧≒ｸ巵ｸ鐘ｸｴ犹犧ｪ犧�</span>
                        @endif
                    </div>
                </div>

                <div class="p-6 space-y-10">
                    {{-- 犧ｪ犹謂ｸｧ犧吭ｸ伶ｸｵ犹� 1: 犧憫ｸｹ犹霞ｸ｣犹霞ｸｭ犧�ｸもｸｭ --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg">犧ｪ犹謂ｸｧ犧吭ｸ伶ｸｵ犹� 1 犧憫ｸｹ犹霞ｸ｣犹霞ｸｭ犧�ｸもｸｭ</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                            <div class="md:col-span-3">
                                 <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">犧巵ｸ｣犧ｰ犹犧�犧伶ｸ�ｸｳ犧｣犹霞ｸｭ犧�</label>
                                 <p class="font-bold text-slate-800">
                                    @php $types = ['new_employee' => '犧樅ｸ吭ｸｱ犧≒ｸ�ｸｲ犧吭ｹ�ｸｫ犧｡犹�', 'resign' => '犧･犧ｲ犧ｭ犧ｭ犧�', 'position_change' => '犧巵ｸ｣犧ｱ犧壟ｸ歩ｸｳ犹≒ｸｫ犧吭ｹ謂ｸ�', 'transfer' => '犹もｸｭ犧吭ｸ｢犹霞ｸｲ犧｢', 'add_remove_access' => '犹犧樅ｸｴ犹謂ｸ｡犧ｪ犧ｴ犧伶ｸ倨ｸｴ犹�/犧･犧壟ｸｪ犧ｴ犧伶ｸ倨ｸｴ犹�']; @endphp
                                    {{ $types[$request->request_type] ?? $request->request_type }}
                                 </p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">犧癌ｸｷ犹謂ｸｭ-犧吭ｸｲ犧｡犧ｪ犧≒ｸｸ犧･</label>
                                <p class="text-sm text-slate-700 font-medium">{{ $request->firstname }} {{ $request->lastname }} ({{ $request->nickname_th ?: '-' }})</p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">犧｣犧ｫ犧ｱ犧ｪ犧樅ｸ吭ｸｱ犧≒ｸ�ｸｲ犧�</label>
                                <p class="text-sm text-slate-700 font-bold">{{ $request->emp_code }}</p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">犹≒ｸ憫ｸ吭ｸ�/犧杳ｹ謂ｸｲ犧｢</label>
                                <p class="text-sm text-slate-700 font-medium">{{ $request->department_name }} {{ $request->division_name ? '/ ' . $request->division_name : '' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- 犧ｪ犹謂ｸｧ犧吭ｸ伶ｸｵ犹� 2: 犧≒ｸｲ犧｣犹犧もｹ霞ｸｲ犧籾ｸｶ犧� --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg">犧ｪ犹謂ｸｧ犧吭ｸ伶ｸｵ犹� 2 犧≒ｸｲ犧｣犹犧もｹ霞ｸｲ犧籾ｸｶ犧� (犧歩ｸｲ犧｡犧�ｸｧ犧ｲ犧｡犧巵ｸ｣犧ｰ犧ｪ犧�ｸ�ｹ�)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border border-slate-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-slate-500 mb-3 underline italic uppercase">犧｣犧ｰ犧壟ｸ壟ｸ伶ｸｵ犹謂ｸ歩ｹ霞ｸｭ犧�ｸ≒ｸｲ犧｣犹犧もｹ霞ｸｲ犧籾ｸｶ犧�</h4>
                                <ul class="text-xs space-y-1 text-slate-700">
                                    @php $hasSystem = false; @endphp
                                    @foreach($request->system_access ?? [] as $key => $val)
                                        @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                            <li>窶｢ {{ ucwords(str_replace('_', ' ', $key)) }}
                                                @if(isset($request->system_access[$key.'_sub']))
                                                    <span class="text-[10px] text-blue-500">({{ is_array($request->system_access[$key.'_sub']) ? implode(', ', $request->system_access[$key.'_sub']) : $request->system_access[$key.'_sub'] }})</span>
                                                @endif
                                            </li>
                                            @php $hasSystem = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(isset($request->system_access['other_check']) && $request->system_access['other_check'])
                                        <li>窶｢ Other: {{ $request->system_access['other_text'] ?? '-' }}</li>
                                        @php $hasSystem = true; @endphp
                                    @endif
                                    @if(!$hasSystem) <li>-</li> @endif
                                </ul>
                            </div>
                            <div class="border border-slate-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-slate-500 mb-3 underline italic uppercase">犹もｸ巵ｸ｣犹≒ｸ≒ｸ｣犧｡犧伶ｸｵ犹謂ｸ歩ｹ霞ｸｭ犧�ｸ≒ｸｲ犧｣</h4>
                                <ul class="text-xs space-y-1 text-slate-700">
                                    @php $hasProgram = false; @endphp
                                    @foreach($request->program_access ?? [] as $key => $val)
                                        @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                            <li>窶｢ {{ ucwords(str_replace('_', ' ', $key)) }}
                                                @if(isset($request->program_access[$key.'_sub']))
                                                    <span class="text-[10px] text-orange-500">({{ is_array($request->program_access[$key.'_sub']) ? implode(', ', $request->program_access[$key.'_sub']) : $request->program_access[$key.'_sub'] }})</span>
                                                @endif
                                            </li>
                                            @php $hasProgram = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(isset($request->program_access['other_check']) && $request->program_access['other_check'])
                                        <li>窶｢ Other: {{ $request->program_access['other_text'] ?? '-' }}</li>
                                        @php $hasProgram = true; @endphp
                                    @endif
                                    @if(!$hasProgram) <li>-</li> @endif
                                </ul>
                            </div>
                            <div class="border border-slate-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-slate-500 mb-3 underline italic uppercase">犧ｭ犧ｸ犧巵ｸ≒ｸ｣犧内ｹ呉ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｸ�ｸｲ犧�</h4>
                                <ul class="text-xs space-y-1 text-slate-700">
                                    @php $hasEquip = false; @endphp
                                    @foreach($request->equipment_access ?? [] as $key => $val)
                                        @if($val && !Str::endsWith($key, '_sub') && $key !== 'other_check' && $key !== 'other_text')
                                            <li>窶｢ {{ ucwords(str_replace('_', ' ', $key)) }}
                                                @if(isset($request->equipment_access[$key.'_sub']))
                                                    <span class="text-[10px] text-green-600">({{ is_array($request->equipment_access[$key.'_sub']) ? implode(', ', $request->equipment_access[$key.'_sub']) : $request->equipment_access[$key.'_sub'] }})</span>
                                                @endif
                                            </li>
                                            @php $hasEquip = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(isset($request->equipment_access['other_check']) && $request->equipment_access['other_check'])
                                        <li>窶｢ Other: {{ $request->equipment_access['other_text'] ?? '-' }}</li>
                                        @php $hasEquip = true; @endphp
                                    @endif
                                    @if(!$hasEquip) <li>-</li> @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- 犧･犧ｳ犧扉ｸｱ犧壟ｸ≒ｸｲ犧｣犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-100">
                        <div class="flex flex-col items-center p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">犧･犧ｲ犧｢犧｡犧ｷ犧ｭ犧癌ｸｷ犹謂ｸｭ犧憫ｸｹ犹霞ｸ｣犹霞ｸｭ犧�ｸもｸｭ</label>
                            @if($request->signature_path)
                                <img src="{{ asset('storage/' . $request->signature_path) }}" alt="Signature" class="h-16 w-auto mb-2">
                            @else
                                <div class="h-16 flex items-center text-slate-300 italic text-xs">犹�ｸ｡犹謂ｸ樅ｸ壟ｸもｹ霞ｸｭ犧｡犧ｹ犧･</div>
                            @endif
                            <div class="w-32 h-px bg-slate-200 mb-1"></div>
                            <p class="text-[10px] font-bold text-slate-700">{{ $request->firstname }} {{ $request->lastname }}</p>
                            <p class="text-[8px] text-slate-400">{{ $request->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">犧･犧ｳ犧扉ｸｱ犧壟ｸ≒ｸｲ犧｣犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ</label>
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
                                            {{ $step->status == 'pending' ? '犧｣犧ｭ犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ' : ($step->status == 'approved' ? '犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ犹≒ｸ･犹霞ｸｧ' : '犧巵ｸ鐘ｸｴ犹犧ｪ犧�') }}
                                        </span>
                                        @if($step->status == 'approved' && $step->approved_at)
                                            <p class="text-[8px] text-slate-400 mt-0.5">{{ $step->approved_at->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 犧ｪ犹謂ｸｧ犧吭ｸ伶ｸｵ犹� 3: 犧ｪ犧ｳ犧ｫ犧｣犧ｱ犧壟ｹ犧謂ｹ霞ｸｲ犧ｫ犧吭ｹ霞ｸｲ犧伶ｸｵ犹� --}}
                    <div class="space-y-6 pt-10 border-t-2 border-slate-200">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg">犧ｪ犹謂ｸｧ犧吭ｸ伶ｸｵ犹� 3 犧ｪ犧ｳ犧ｫ犧｣犧ｱ犧壟ｹ犧謂ｹ霞ｸｲ犧ｫ犧吭ｹ霞ｸｲ犧伶ｸｵ犹�</h3>

                        @if($request->status == 'approved' || $request->status == 'completed')
                            @if($request->it_status == 'completed')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-2 border-slate-300 rounded-xl overflow-hidden">
                                    {{-- Left Box: System Access Results --}}
                                    <div class="p-6 border-r-2 border-slate-300 flex flex-col">
                                        <h4 class="text-xs font-bold text-slate-600 border-b pb-2 mb-4">犧≒ｸｲ犧｣犹犧もｹ霞ｸｲ犧籾ｸｶ犧�ｸ｣犧ｰ犧壟ｸ�</h4>
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
                                                    <span>犧ｭ犧吭ｸｸ犧財ｸｲ犧歩ｹ≒ｸ･犹霞ｸｧ</span>
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
                                            <div class="text-[10px] flex justify-between"><span class="font-bold">犧ｪ犧籾ｸｲ犧吭ｸｰ:</span> <span class="{{ ($request->it_system_config['status'] ?? '') == 'completed' ? 'text-green-600' : 'text-yellow-600' }} font-bold uppercase">{{ $request->it_system_config['status'] ?? 'Pending' }}</span></div>
                                            <div class="text-[10px] flex justify-between"><span class="font-bold">犧憫ｸｹ犹霞ｸ扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣:</span> {{ $request->itStaff->fullname ?? '-' }}</div>
                                            <div class="text-[9px] text-slate-400 italic text-right">犧ｧ犧ｱ犧吭ｸ伶ｸｵ犹�: {{ $request->it_configured_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                    {{-- Right Box: Program Access Results --}}
                                    <div class="p-6 flex flex-col">
                                        <h4 class="text-xs font-bold text-slate-600 border-b pb-2 mb-4">犧≒ｸｲ犧｣犹犧もｹ霞ｸｲ犧籾ｸｶ犧�ｹもｸ巵ｸ｣犹≒ｸ≒ｸ｣犧｡</h4>
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
                                            <div class="text-[10px] flex justify-between"><span class="font-bold">犧ｪ犧籾ｸｲ犧吭ｸｰ:</span> <span class="{{ ($request->it_program_config['status'] ?? '') == 'completed' ? 'text-green-600' : 'text-yellow-600' }} font-bold uppercase">{{ $request->it_program_config['status'] ?? 'Pending' }}</span></div>
                                            <div class="text-[10px] flex justify-between"><span class="font-bold">犧憫ｸｹ犹霞ｸ扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣:</span> {{ $request->itStaff->fullname ?? '-' }}</div>
                                            <div class="text-[9px] text-slate-400 italic text-right">犧ｧ犧ｱ犧吭ｸ伶ｸｵ犹�: {{ $request->it_configured_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- IT 犧｢犧ｱ犧�ｹ�ｸ｡犹謂ｸ扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣ - 犹≒ｸｪ犧扉ｸ�ｸｪ犧籾ｸｲ犧吭ｸｰ犧｣犧ｭ 犧ｪ犧ｳ犧ｫ犧｣犧ｱ犧壟ｸ伶ｸｸ犧≒ｸ�ｸ� --}}
                                <div class="p-16 text-center bg-gradient-to-b from-blue-50 to-white rounded-3xl border-2 border-blue-100">
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-6">
                                        <svg class="w-10 h-10 text-blue-500 animate-spin" style="animation-duration: 3s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-blue-600 mb-2">犧≒ｸｳ犧･犧ｱ犧�ｸ扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣</h4>
                                    <p class="text-sm text-slate-500 font-medium">犧｣犧ｭ犹犧謂ｹ霞ｸｲ犧ｫ犧吭ｹ霞ｸｲ犧伶ｸｵ犹謂ｹ犧伶ｸ�ｹもｸ吭ｹもｸ･犧｢犧ｵ犧ｪ犧ｲ犧｣犧ｪ犧吭ｹ犧伶ｸｨ犧≒ｸｳ犧･犧ｱ犧�ｸ扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣犧歩ｸｱ犹霞ｸ�ｸ�ｹ謂ｸｲ犧｣犧ｰ犧壟ｸ�</p>
                                    <p class="text-xs text-slate-400 mt-2">犧≒ｸ｣犧ｸ犧内ｸｲ犧｣犧ｭ犧ｪ犧ｱ犧≒ｸ�ｸ｣犧ｹ犹� 犧｣犧ｰ犧壟ｸ壟ｸ謂ｸｰ犧ｭ犧ｱ犧巵ｹ犧扉ｸ歩ｸｪ犧籾ｸｲ犧吭ｸｰ犹犧｡犧ｷ犹謂ｸｭ犧扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣犹犧ｪ犧｣犹�ｸ謂ｸｪ犧ｴ犹霞ｸ�</p>
                                </div>
                            @endif
                        @else
                             <div class="p-10 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                                <p class="text-sm font-bold text-slate-400 italic">犧｣犧ｭ犧≒ｸｲ犧｣犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ犹�ｸｫ犹霞ｸ�ｸ｣犧壟ｸ伶ｸｸ犧≒ｸ･犧ｳ犧扉ｸｱ犧壟ｸ≒ｹ謂ｸｭ犧吭ｸ扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣犧もｸｱ犹霞ｸ吭ｸ歩ｸｭ犧吭ｸ吭ｸｵ犹�</p>
                            </div>
                        @endif
                    </div>

                    {{-- 犧ｪ犹謂ｸｧ犧吭ｸ伶ｸｵ犹� 4: 犧ｪ犧ｳ犧ｫ犧｣犧ｱ犧壟ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� --}}
                    <div class="space-y-6 pt-10 border-t-2 border-slate-200">
                         <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg">犧ｪ犹謂ｸｧ犧吭ｸ伶ｸｵ犹� 4 犧ｪ犧ｳ犧ｫ犧｣犧ｱ犧壟ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧�</h3>

                         <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                            <p class="text-[13px] text-slate-700 leading-relaxed text-center font-medium">
                               "犧もｹ霞ｸｲ犧樅ｹ犧謂ｹ霞ｸｲ犹�ｸ扉ｹ霞ｸ｣犧ｱ犧壟ｸもｹ霞ｸｭ犧｡犧ｹ犧･犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ≒ｸ･犧ｰ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧吭ｹ犧巵ｹ�ｸ吭ｸ伶ｸｵ犹謂ｹ犧｣犧ｵ犧｢犧壟ｸ｣犹霞ｸｭ犧｢犹≒ｸ･犹霞ｸｧ 犹≒ｸ･犧ｰ犹�ｸ扉ｹ霞ｸ伶ｸｳ犧≒ｸｲ犧｣犹犧巵ｸ･犧ｵ犹謂ｸ｢犧吭ｹ≒ｸ巵ｸ･犧�ｹ≒ｸ≒ｹ霞ｹ�ｸもｸ｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧吭ｹ�ｸ吭ｸ�ｸ｣犧ｱ犹霞ｸ�ｹ≒ｸ｣犧≒ｸ伶ｸｵ犹謂ｹ犧もｹ霞ｸｲ犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ≒ｸ･犧ｰ犧謂ｸｰ犹犧≒ｹ�ｸ壟ｸもｹ霞ｸｭ犧｡犧ｹ犧･犧扉ｸｱ犧�ｸ≒ｸ･犹謂ｸｲ犧ｧ犹犧巵ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧･犧ｱ犧�"
                            </p>

                            <div class="bg-white rounded-2xl border border-slate-200 p-10 md:p-16 text-[12px] text-slate-700 leading-relaxed space-y-6 max-h-[600px] overflow-y-auto no-print-scroll shadow-inner">
                            <p class="text-center text-lg font-bold text-slate-800 mb-2">犧巵ｸ｣犧ｰ犧≒ｸｲ犧ｨ</p>
                            <p class="text-center text-sm font-bold text-slate-700 mb-8 uppercase tracking-widest">犧｣犧ｰ犹犧壟ｸｵ犧｢犧壟ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹�</p>

                            <p class="indent-12 text-justify">
                                犧壟ｸ｣犧ｴ犧ｩ犧ｱ犧� 犧�ｸｱ犧｡犹犧ｧ犧･ 犧�ｸｭ犧｣犹呉ｸ巵ｸｭ犹犧｣犧癌ｸｱ犹謂ｸ� 犧謂ｸｳ犧≒ｸｱ犧� (犧｡犧ｫ犧ｲ犧癌ｸ�) 犹�ｸ扉ｹ霞ｸ謂ｸｱ犧扉ｹ�ｸｫ犹霞ｸ｡犧ｵ犧｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｹ犧樅ｸｷ犹謂ｸｭ犧ｪ犧吭ｸｱ犧壟ｸｪ犧吭ｸｸ犧吭ｸ≒ｸｲ犧｣犧扉ｸｳ犹犧吭ｸｴ犧吭ｸ�ｸｲ犧吭ｸもｸｭ犧�ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 
                                犹�ｸｫ犹霞ｹ犧≒ｸｴ犧扉ｸ巵ｸ｣犧ｰ犧ｪ犧ｴ犧伶ｸ倨ｸｴ犧�犧ｲ犧� 犧ｪ犧ｲ犧｡犧ｲ犧｣犧籾ｸ歩ｸｭ犧壟ｸｪ犧吭ｸｭ犧�ｹ犧巵ｹ霞ｸｲ犧ｫ犧｡犧ｲ犧｢犧伶ｸｲ犧�ｸ倨ｸｸ犧｣犧≒ｸｴ犧謂ｹ�ｸ吭ｸ巵ｸ｣犧ｰ犧ｪ犧ｴ犧伶ｸ倨ｸｴ犧憫ｸ･犹≒ｸ･犧ｰ犧｣犧ｧ犧扉ｹ犧｣犹�ｸｧ犧もｸｶ犹霞ｸ� 犧扉ｸｱ犧�ｸ吭ｸｱ犹霞ｸ� 犧壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧謂ｸｶ犧�ｸ謂ｸｱ犧扉ｸｧ犹謂ｸｲ犧｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ
                                犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｹ犧巵ｹ�ｸ吭ｸｪ犧ｴ犧吭ｸ伶ｸ｣犧ｱ犧樅ｸ｢犹呉ｸ伶ｸｵ犹謂ｸｪ犧ｳ犧�ｸｱ犧財ｸもｸｭ犧�ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犹犧樅ｸｷ犹謂ｸｭ犹�ｸｫ犹霞ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ犧巵ｹ�ｸ吭ｹ�ｸ巵ｸｭ犧｢犹謂ｸｲ犧�ｹ犧｣犧ｵ犧｢犧壟ｸ｣犹霞ｸｭ犧｢犹≒ｸ･犧ｰ犹犧≒ｸｴ犧扉ｸ巵ｸ｣犧ｰ犹もｸ｢犧癌ｸ吭ｹ呉ｸｪ犧ｹ犧�ｸｪ犧ｸ犧� 犧壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧謂ｸｶ犧�ｹ�ｸ扉ｹ霞ｸ巵ｸ｣犧ｰ犧≒ｸｲ犧ｨ
                                犧｣犧ｰ犹犧壟ｸｵ犧｢犧壟ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹� 犹もｸ扉ｸ｢犧｡犧ｵ犧ｪ犧ｲ犧｣犧ｰ犧ｪ犧ｳ犧�ｸｱ犧財ｸ扉ｸｱ犧�ｸ吭ｸｵ犹�
                            </p>

                            <div class="space-y-6 px-4 md:px-8">
                                <div class="space-y-3">
                                    <p class="font-bold text-slate-800 text-[13px]">1. 犧壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧謂ｸｰ犧｡犧ｭ犧壟ｸｫ犧｡犧ｲ犧｢犹≒ｸ憫ｸ吭ｸ≒ｹ犧伶ｸ�ｹもｸ吭ｹもｸ･犧｢犧ｵ犧ｪ犧ｲ犧｣犧ｪ犧吭ｹ犧伶ｸｨ 犧扉ｸｹ犹≒ｸ･犧壟ｸｳ犧｣犧ｸ犧�ｸ｣犧ｱ犧≒ｸｩ犧ｲ 犧樅ｸｱ犧亭ｸ吭ｸｲ犹≒ｸ･犧ｰ犧巵ｸ｣犧ｱ犧壟ｸ巵ｸ｣犧ｸ犧�ｸ｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｹ�ｸｫ犹霞ｸ樅ｸ｣犹霞ｸｭ犧｡犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ�ｸ扉ｹ霞ｸｭ犧｢犧ｹ犹謂ｹ犧ｪ犧｡犧ｭ 犧樅ｸ｣犹霞ｸｭ犧｡犧伶ｸｱ犹霞ｸ�ｸ｡犧ｭ犧壟ｸ壟ｸｱ犧財ｸ癌ｸｵ犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� (User Account) 犹≒ｸ･犧ｰ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧� (Password) 犹�ｸｫ犹霞ｸ≒ｸｱ犧壟ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ犧霞ｸ樅ｸｲ犧ｰ犧壟ｸｸ犧�ｸ�ｸ･ 犹もｸ扉ｸ｢ 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ謂ｸｰ犧歩ｹ霞ｸｭ犧�ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ 犧扉ｸｱ犧�ｸ吭ｸｵ犹�</p>
                                    <div class="pl-8 space-y-3 mt-2 border-l-2 border-slate-100">
                                        <p>a. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� (User Account) 犹≒ｸ･犧ｰ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧� (Password) 犧籾ｸｷ犧ｭ犹犧巵ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧･犧ｱ犧壟ｹ犧霞ｸ樅ｸｲ犧ｰ犧壟ｸｸ犧�ｸ�ｸ･ 犧歩ｹ霞ｸｭ犧�ｹ犧≒ｹ�ｸ壟ｹ≒ｸ･犧ｰ犧｣犧ｱ犧≒ｸｩ犧ｲ犹犧巵ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧･犧ｱ犧� 犹�ｸ｡犹謂ｹ�ｸｫ犹霞ｸ憫ｸｹ犹霞ｸｭ犧ｷ犹謂ｸ吭ｸ吭ｸｳ犹�ｸ巵ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ�ｸ扉ｹ霞ｸｭ犧｢犹謂ｸｲ犧�ｹ犧扉ｹ�ｸ扉ｸもｸｲ犧�</p>
                                        <p>b. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ謂ｸｰ犧歩ｹ霞ｸｭ犧�ｹ犧≒ｹ�ｸ壟ｸ｣犧ｱ犧≒ｸｩ犧ｲ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧� (Password) 犹�ｸｧ犹霞ｹ犧巵ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧･犧ｱ犧� 犹もｸ扉ｸ｢犹�ｸ｡犹謂ｸｭ犧ｲ犧謂ｸ≒ｸ｣犧ｰ 犧伶ｸｳ犧≒ｸｲ犧｣犹≒ｸｪ犧扉ｸ�ｸ籾ｸｶ犧�ｸ≒ｸｲ犧｣犹犧巵ｸｴ犧扉ｹ犧憫ｸ｢犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧� (Password) 犹�ｸｫ犹霞ｸ憫ｸｹ犹霞ｸｭ犧ｷ犹謂ｸ吭ｸ伶ｸ｣犧ｲ犧� 犹≒ｸ･犧ｰ犹�ｸ｡犹謂ｹ�ｸ癌ｹ霞ｹもｸ巵ｸ｣犹≒ｸ≒ｸ｣犧｡犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸ癌ｹ謂ｸｧ犧｢犧謂ｸｳ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧吭ｸｭ犧ｱ犧歩ｹもｸ吭ｸ｡犧ｱ犧歩ｸｴ (Save Password) 犧ｪ犧ｳ犧ｫ犧｣犧ｱ犧壟ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸ伶ｸｵ犹謂ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ�ｸ癌ｹ霞ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧�ｸｲ犧�</p>
                                        <p>c. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ歩ｹ霞ｸｭ犧�ｹ�ｸ｡犹謂ｸ歩ｸｴ犧扉ｸ歩ｸｱ犹霞ｸ� Software 犧ｫ犧｣犧ｷ犧ｭ犧吭ｸｳ犧ｭ犧ｸ犧巵ｸ≒ｸ｣犧内ｹ� Hardware 犧ｪ犹謂ｸｧ犧吭ｸ壟ｸｸ犧�ｸ�ｸ･犧伶ｸｵ犹謂ｹ犧≒ｸｵ犹謂ｸ｢犧ｧ犧もｹ霞ｸｭ犧�ｸ≒ｸｱ犧壟ｸ�ｸｲ犧吭ｹ≒ｸ･犧ｰ犧壟ｸｭ犧≒ｹ犧ｫ犧歩ｸｸ犧もｹ霞ｸｭ犧謂ｸｳ犹犧巵ｹ�ｸ吭ｸ≒ｸｱ犧壟ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧≒ｹ謂ｸｭ犧吭ｸ吭ｸｳ 犧ｫ犧ｲ犧≒ｸ｡犧ｵ犧�ｸｧ犧ｲ犧｡犧謂ｸｳ犹犧巵ｹ�ｸ吭ｸ歩ｹ霞ｸｭ犧�ｹ≒ｸ謂ｹ霞ｸ�ｸ憫ｸｹ犹霞ｸ｣犧ｱ犧壟ｸ憫ｸｴ犧扉ｸ癌ｸｭ犧� 犹≒ｸ･犧ｰ犹≒ｸ憫ｸ吭ｸ≒ｹ犧伶ｸ�ｹもｸ吭ｹもｸ･犧｢犧ｵ犧ｪ犧ｲ犧｣犧ｪ犧吭ｹ犧伶ｸｨ 犹犧樅ｸｷ犹謂ｸｭ犧もｸｭ犧�ｸｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ犧≒ｹ謂ｸｭ犧�</p>
                                    </div>
                                </div>

                                <p class="text-justify italic">d. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ歩ｹ霞ｸｭ犧�ｹ�ｸ｡犹� 犧歩ｸｱ犧扉ｸ歩ｹ謂ｸｭ 犹犧巵ｸｴ犧扉ｹ犧憫ｸ｢ 犧ｫ犧｣犧ｷ犧ｭ犧ｭ犹謂ｸｲ犧｢犹もｸｭ犧吭ｸもｹ霞ｸｭ犧｡犧ｹ犧･犧もｹ霞ｸｭ犧｡犧ｹ犧･犹�ｸ巵ｸ｣犧ｹ犧巵ｹ≒ｸ壟ｸ壟ｸ≒ｸｲ犧｣ Upload, Download 犧ｫ犧｣犧ｷ犧ｭ犧ｧ犧ｴ犧倨ｸｵ犧≒ｸｲ犧｣犹�ｸ扉ｸ≒ｹ�ｸ歩ｸｲ犧｡犧伶ｸｵ犹謂ｸｭ犧ｲ犧謂ｸ≒ｹ謂ｸｭ犹�ｸｫ犹� 犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢犹≒ｸ≒ｹ謂ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犹もｸ扉ｸ｢犹犧扉ｹ�ｸ扉ｸもｸｲ犧� 犧扉ｸｱ犧�ｹ犧癌ｹ謂ｸ� 犧もｹ霞ｸｭ犧｡犧ｹ犧･ 犧ｪ犧ｴ犹謂ｸ�ｸｪ犹謂ｸ�ｸ樅ｸｴ犧｡犧樅ｹ呉ｸｭ犧ｴ犹犧･犹�ｸ≒ｸ伶ｸ｣犧ｭ犧吭ｸｴ犧≒ｸｪ犹呉ｸ伶ｸｵ犹謂ｹ犧巵ｹ�ｸ吭ｸ≒ｸｲ犧｣犧･犧ｰ犹犧｡犧ｴ犧扉ｸ･犧ｴ犧もｸｪ犧ｴ犧伶ｸ倨ｸｴ犹呉ｸｫ犧｣犧ｷ犧ｭ犹犧巵ｹ�ｸ吭ｸもｹ霞ｸｭ犧｡犧ｹ犧･犧もｸｭ犧�ｸ憫ｸｹ犹霞ｸ｣犹霞ｸｭ犧� 犧謂ｹ謂ｸｭ犧ｭ犧ｷ犹謂ｸ� 犧もｹ霞ｸｭ犧｡犧ｹ犧･犧伶ｸｵ犹謂ｹ犧巵ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧･犧ｱ犧壟ｹ�ｸ吭ｹ犧｣犧ｷ犹謂ｸｭ犧�ｹ犧≒ｸｵ犹謂ｸ｢犧ｧ犧≒ｸｱ犧壟ｸ倨ｸｸ犧｣犧≒ｸｴ犧� 犧樅ｸ吭ｸｱ犧≒ｸ�ｸｲ犧� 犧ｫ犧｣犧ｷ犧ｭ犧壟ｸｸ犧�ｸ�ｸ･犧�犧ｲ犧｢犧吭ｸｭ犧� 犹犧巵ｹ�ｸ吭ｸ歩ｹ霞ｸ�</p>

                                <div class="space-y-3">
                                    <p class="font-bold text-slate-800 text-[13px]">2. 犹犧｡犧ｷ犹謂ｸｭ犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸｧ犹謂ｸｲ犧�ｸ謂ｸｲ犧≒ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸｪ犹謂ｸｧ犧吭ｸ壟ｸｸ犧�ｸ�ｸ･犹犧≒ｸｴ犧� 5 犧吭ｸｲ犧伶ｸｵ 犹�ｸｫ犹霞ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ伶ｸｳ犧≒ｸｲ犧｣犧巵ｸ≒ｸｫ犧吭ｹ霞ｸｲ犧謂ｸｭ (Lock Screen) 犧ｫ犧｣犧ｷ犧ｭ犧伶ｸｳ犧≒ｸｲ犧｣犧巵ｸｴ犧扉ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧� (Shut Down) 犹犧｡犧ｷ犹謂ｸｭ犧≒ｸｲ犧｣犧巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧�ｸｲ犧吭ｸ巵ｸ｣犧ｰ犧謂ｸｳ犧ｧ犧ｱ犧吭ｹ犧ｪ犧｣犹�ｸ謂ｸｪ犧ｴ犹霞ｸ� 犹もｸ扉ｸ｢</p>
                                    <div class="pl-8 space-y-3 mt-2 border-l-2 border-slate-100">
                                        <p>a. 犧≒ｸ｣犧内ｸｵ犧伶ｸｵ犹謂ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｢犧ｴ犧吭ｸ｢犧ｭ犧｡犹�ｸｫ犹霞ｸ憫ｸｹ犹霞ｸｭ犧ｷ犹謂ｸ吭ｹ�ｸ癌ｹ霞ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸもｸｭ犧�ｸ歩ｸ吭ｹ犧ｭ犧�ｸｫ犧｣犧ｷ犧ｭ 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ謂ｸｰ犧歩ｹ霞ｸｭ犧�ｸ伶ｸｳ犧≒ｸｲ犧｣犧ｭ犧ｭ犧≒ｸ謂ｸｲ犧≒ｸ｣犧ｰ犧壟ｸ� (Logout) 犧伶ｸｱ犧吭ｸ伶ｸｵ 犹犧樅ｸｷ犹謂ｸｭ犧伶ｸｵ犹謂ｸ憫ｸｹ犹霞ｸ｢犧ｷ犧｡犹犧もｹ霞ｸｲ犧伶ｸｳ犧≒ｸｲ犧｣犹犧もｹ霞ｸｲ犹�ｸ癌ｹ霞ｸ歩ｸｱ犧ｧ犹犧ｭ犧� (Login) 犧扉ｹ霞ｸｧ犧｢犧壟ｸｱ犧財ｸ癌ｸｵ犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� (User Account) 犹≒ｸ･犧ｰ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧� (Password) 犧もｸｭ犧�ｸ歩ｸ吭ｹ犧ｭ犧�ｹ犧伶ｹ謂ｸｲ犧吭ｸｱ犹霞ｸ�</p>
                                        <p>b. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ謂ｸｰ犧歩ｹ霞ｸｭ犧�ｹ�ｸ｡犹謂ｹ犧巵ｸｴ犧扉ｹ犧憫ｸ｢犧樅ｸｲ犧ｪ犧ｪ犹呉ｹ犧ｧ犧ｴ犧｣犹呉ｸ扉ｸ歩ｸ吭ｹ犧ｭ犧�ｹ�ｸｫ犹霞ｸ�ｸ吭ｸｭ犧ｷ犹謂ｸ吭ｸ伶ｸ｣犧ｲ犧� 犧ｫ犧｣犧ｷ犧ｭ犧壟ｸｸ犧�ｸ�ｸ･犧ｭ犧ｷ犹謂ｸ吭ｹ�ｸ扉ｸ≒ｹ�ｸ歩ｸｲ犧｡犹�ｸｫ犹霞ｸ｣犧ｱ犧壟ｸ｣犧ｹ犹霞ｸｫ犧｣犧ｷ犧ｭ 犧≒ｸｸ犧財ｹ≒ｸ� 犧扉ｸｱ犧�ｹ犧癌ｹ謂ｸ� 犧≒ｸｲ犧｣犧壟ｸｸ犧≒ｸ｣犧ｸ犧� (Hack) 犹犧謂ｸｲ犧ｰ犹犧もｹ霞ｸｲ犧｣犧ｰ犧壟ｸ�</p>
                                    </div>
                                </div>

                                <p class="font-bold text-slate-800 text-[13px]">3. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� (User Account) 犧もｸｭ犧�ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ伶ｸｵ犹謂ｸ籾ｸｹ犧≒ｸ歩ｸｱ犹霞ｸ�ｸもｸｶ犹霞ｸ吭ｸ｡犧ｲ犹犧樅ｸｷ犹謂ｸｭ犹犧もｹ霞ｸｲ犧籾ｸｶ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸもｸｭ犧�ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧≒ｸｲ犧｣犹犧もｸｵ犧｢犧� 犹犧憫ｸ｢犹≒ｸ樅ｸ｣犹謂ｸもｹ霞ｸｭ犧�ｸｧ犧ｲ犧｡犹�ｸ� 犹� 犧伶ｸｵ犹� 犧ｭ犧ｲ犧謂ｸ≒ｹ謂ｸｭ犹�ｸｫ犹霞ｹ犧≒ｸｴ犧扉ｸ�ｸｧ犧ｲ犧｡犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢犧ｭ犧ｲ犧� 犹犧ｪ犧ｷ犹謂ｸｭ犧｡犹犧ｪ犧ｵ犧｢犹≒ｸ≒ｹ謂ｸ憫ｸｹ犹霞ｸｭ犧ｷ犹謂ｸ� 犧≒ｸｲ犧｣犹�ｸ癌ｹ霞ｹ犧樅ｸｷ犹謂ｸｭ犧ｭ犧ｸ犧巵ｸ≒ｸ｣犧内ｹ呉ｸ伶ｸｵ犹謂ｹ�ｸ｡犹謂ｸｪ犧ｸ犧�犧ｲ犧� 犹犧巵ｹ�ｸ吭ｸ歩ｹ霞ｸ� 犧ｫ犧ｲ犧≒ｸ｡犧ｵ犧≒ｸｲ犧｣犧≒ｸ｣犧ｰ犧伶ｸｳ犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢犧謂ｸｲ犧≒ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� 犧謂ｸｰ犧歩ｹ霞ｸｭ犧�ｸ｣犧ｱ犧壟ｸ憫ｸｴ犧扉ｸ癌ｸｭ犧壟ｸ癌ｸ扉ｹ犧癌ｸ｢</p>

                                <p class="text-justify">3. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ歩ｹ霞ｸｭ犧�ｹ�ｸ｡犹謂ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧≒ｸｲ犧｣犹�ｸ� 犹� 犧伶ｸｵ犹謂ｹ犧巵ｹ�ｸ吭ｸ≒ｸｲ犧｣犧もｸｱ犧扉ｸ歩ｹ謂ｸｭ犧≒ｸ錫ｸｫ犧｡犧ｲ犧｢犧ｧ犹謂ｸｲ犧扉ｹ霞ｸｧ犧｢犧≒ｸｲ犧｣犧巵ｸ｣犧ｰ犧≒ｸｭ犧壟ｸ≒ｸｴ犧謂ｸ≒ｸ｣犧｣犧｡犧ｧ犹謂ｸｲ犧扉ｹ霞ｸｧ犧｢犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹� 犧伶ｸｳ犹�ｸｫ犹霞ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧｣犧ｱ犧壟ｸ�ｸｧ犧ｲ犧｡犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧� 犧｡犧ｵ犧≒ｸｲ犧｣犧≒ｸ｣犧ｰ犧伶ｸｳ犧≒ｸｲ犧｣犹�ｸ� 犹� 犧伶ｸｵ犹謂ｸ｣犧ｰ犧壟ｸ壟ｸ�ｹ謂ｸｲ 犧ｭ犹霞ｸｭ犧｡犧籾ｹ霞ｸｲ犧ｧ犹謂ｸｲ 犧｢犹謂ｸｭ犧｡犧籾ｸｷ犧ｭ犧ｧ犹謂ｸｲ犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ憫ｸｹ犹霞ｸ吭ｸｱ犹霞ｸ吭ｹ犧巵ｹ�ｸ吭ｸ憫ｸｴ犧扉ｸ｣犧ｰ犧壟ｸ壟ｸ�ｸｧ犧ｲ犧｡犧｣犧ｱ犧壟ｸ憫ｸｴ犧扉ｸ癌ｸｭ犧壟ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ</p>

                                <p class="font-bold text-slate-800 text-[13px]">4. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ歩ｹ霞ｸｭ犧�ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧歩ｸｲ犧｡犹犧�ｸｷ犹謂ｸｭ犧吭ｹ�ｸ� 犧≒ｸ錫ｸ｣犧ｰ犹犧壟ｸｵ犧｢犧� 犧もｹ霞ｸｭ犧壟ｸｱ犧�ｸ�ｸｱ犧壟ｸ伶ｸｵ犹謂ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧≒ｸｳ犧ｫ犧吭ｸ扉ｹ�ｸｧ犹� 犹もｸ扉ｸ｢犹犧｡犧ｷ犹謂ｸｭ犧･犧�ｸ吭ｸｲ犧｡犹�ｸ吭ｹ�ｸ壟ｸｪ犧ｳ犹犧｣犹�ｸ� 犧ｫ犧｡犧ｵ 犹犧ｪ犧｡犧ｷ犧ｭ犧吭ｸ巵ｸ｣犧ｰ犧≒ｸｲ犧ｨ犧謂ｸｲ犧≒ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ</p>

                                <p class="font-bold text-slate-800 text-[13px]">5. 犧壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧もｸｭ犧ｪ犧�ｸｧ犧吭ｸｪ犧ｴ犧伶ｸ倨ｸｴ犹呉ｹ�ｸ吭ｸ≒ｸｲ犧｣犧歩ｸ｣犧ｧ犧謂ｸｪ犧ｭ犧壟ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸもｸｭ犧�ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� 犹�ｸ吭ｸ≒ｸ｣犧内ｸｵ犧伶ｸｵ犹謂ｸ樅ｸ壟ｹ犧ｫ犹�ｸ吭ｸ≒ｸｲ犧｣犹�ｸ｡犹謂ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧歩ｸｲ犧｡犧｣犧ｰ犹犧壟ｸｵ犧｢犧壟ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｹもｸ扉ｸ｢犧｡犧ｵ犧歩ｹ霞ｸｭ犧�ｹ≒ｸ謂ｹ霞ｸ�ｹ�ｸｫ犹霞ｸ伶ｸ｣犧ｲ犧壟ｸ･犹謂ｸｧ犧�ｸｫ犧吭ｹ霞ｸｲ</p>

                                <div class="space-y-3">
                                    <p class="font-bold text-slate-800 text-[13px]">6. 犧ｫ犧ｲ犧≒ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ杳ｹ謂ｸｲ犧杳ｸｷ犧吭ｸｫ犧｣犧ｷ犧ｭ犹�ｸ｡犹謂ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧歩ｸｲ犧｡犧｣犧ｰ犹犧壟ｸｵ犧｢犧壟ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹� 犧壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧謂ｸｰ犧樅ｸｴ犧謂ｸｲ犧｣犧内ｸｲ犧壟ｸ伶ｸ･犧�ｹもｸ伶ｸｩ 犧伶ｸｱ犧吭ｸ伶ｸｵ 犧歩ｸｲ犧｡犧�ｸｧ犧ｲ犧｡犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢犧伶ｸｵ犹謂ｹ犧≒ｸｴ犧扉ｸもｸｶ犹霞ｸ� 犧扉ｸｱ犧�ｸ歩ｹ謂ｸｭ犹�ｸ巵ｸ吭ｸｵ犹�</p>
                                    <ul class="list-disc pl-12 mt-2 space-y-2 font-medium text-red-700/80">
                                        <li>犧歩ｸｱ犧≒ｹ犧歩ｸｷ犧ｭ犧吭ｸ扉ｹ霞ｸｧ犧｢犧ｧ犧ｲ犧謂ｸｲ</li>
                                        <li>犧歩ｸｱ犧≒ｹ犧歩ｸｷ犧ｭ犧吭ｹ犧巵ｹ�ｸ吭ｸ･犧ｲ犧｢犧･犧ｱ犧≒ｸｩ犧内ｹ呉ｸｭ犧ｱ犧≒ｸｩ犧｣</li>
                                        <li>犹�ｸ｡犹謂ｸ樅ｸｴ犧謂ｸｲ犧｣犧内ｸｲ犧もｸｶ犹霞ｸ吭ｹ犧�ｸｴ犧吭ｹ犧扉ｸｷ犧ｭ犧�</li>
                                        <li>犹犧･犧ｴ犧≒ｸ謂ｹ霞ｸｲ犧�ｹもｸ扉ｸ｢犹�ｸ｡犹謂ｸ謂ｹ謂ｸｲ犧｢犧�ｹ謂ｸｲ犧癌ｸ扉ｹ犧癌ｸ｢</li>
                                        <li>犧游ｹ霞ｸｭ犧�ｸ｣犹霞ｸｭ犧�ｹ≒ｸ･犧ｰ犹犧｣犧ｵ犧｢犧≒ｸ�ｹ謂ｸｲ犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢</li>
                                    </ul>
                                </div>
                            </div>
                        </div>ｰ犧壟ｸｸ犧�ｸ�ｸ･ 犧歩ｹ霞ｸｭ犧�ｹ犧≒ｹ�ｸ壟ｹ≒ｸ･犧ｰ犧｣犧ｱ犧≒ｸｩ犧ｲ犹犧巵ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧･犧ｱ犧� 犹�ｸ｡犹謂ｹ�ｸｫ犹霞ｸ憫ｸｹ犹霞ｸｭ犧ｷ犹謂ｸ吭ｸ吭ｸｳ犹�ｸ巵ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ�ｸ扉ｹ霞ｸｭ犧｢犹謂ｸｲ犧�ｹ犧扉ｹ�ｸ扉ｸもｸｲ犧�</p>
                                        <p>b. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ謂ｸｰ犧歩ｹ霞ｸｭ犧�ｹ犧≒ｹ�ｸ壟ｸ｣犧ｱ犧≒ｸｩ犧ｲ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧� (Password) 犹�ｸｧ犹霞ｹ犧巵ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧･犧ｱ犧� 犹もｸ扉ｸ｢犹�ｸ｡犹謂ｸｭ犧ｲ犧謂ｸ｣犧ｰ 犧伶ｸｳ犧≒ｸｲ犧｣犹≒ｸｪ犧扉ｸ�ｸ籾ｸｶ犧�ｸ≒ｸｲ犧｣犹犧巵ｸｴ犧扉ｹ犧憫ｸ｢犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧� (Password) 犹�ｸｫ犹霞ｸ憫ｸｹ犹霞ｸｭ犧ｷ犹謂ｸ吭ｸ伶ｸ｣犧ｲ犧� 犹≒ｸ･犧ｰ犹�ｸ｡犹謂ｹ�ｸ癌ｹ霞ｹもｸ巵ｸ｣犹≒ｸ≒ｸ｣犧｡犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸ癌ｹ謂ｸｧ犧｢犧謂ｸｳ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧吭ｸｭ犧ｱ犧歩ｹもｸ吭ｸ｡犧ｱ犧歩ｸｴ (Save Password) 犧ｪ犧ｳ犧ｫ犧｣犧ｱ犧壟ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸ伶ｸｵ犹謂ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ�ｸ癌ｹ霞ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧�ｸｲ犧�</p>
                                        <p>c. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ歩ｹ霞ｸｭ犧�ｹ�ｸ｡犹謂ｸ歩ｸｴ犧扉ｸ歩ｸｱ犹霞ｸ� Software 犧ｫ犧｣犧ｷ犧ｭ犧吭ｸｳ犧ｭ犧ｸ犧巵ｸ≒ｸ｣犧内ｹ� Hardware 犧ｪ犹謂ｸｧ犧吭ｸ壟ｸｸ犧�ｸ�ｸ･犧伶ｸｵ犹謂ｹ犧≒ｸｵ犹謂ｸ｢犧ｧ犧もｹ霞ｸｭ犧�ｸ≒ｸｱ犧壟ｸ�ｸｲ犧吭ｹ≒ｸ･犧ｰ犧壟ｸｭ犧≒ｹ犧ｫ犧歩ｸｸ犧もｹ霞ｸｭ犧謂ｸｳ犹犧巵ｹ�ｸ吭ｸ≒ｸｱ犧壟ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧≒ｹ謂ｸｭ犧吭ｸ吭ｸｳ 犧ｫ犧ｲ犧≒ｸ｡犧ｵ犧�ｸｧ犧ｲ犧｡犧謂ｸｳ犹犧巵ｹ�ｸ吭ｸ歩ｹ霞ｸｭ犧�ｹ≒ｸ謂ｹ霞ｸ�ｸ憫ｸｹ犹霞ｸ｣犧ｱ犧壟ｸ憫ｸｴ犧扉ｸ癌ｸｭ犧� 犹≒ｸ･犧ｰ犹≒ｸ憫ｸ吭ｸ≒ｹ犧伶ｸ�ｹもｸ吭ｹもｸ･犧｢犧ｵ犧ｪ犧ｲ犧｣犧ｪ犧吭ｹ犧伶ｸｨ 犹犧樅ｸｷ犹謂ｸｭ犧もｸｭ犧�ｸｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ犧≒ｹ謂ｸｭ犧�</p>
                                    </div>
                                </div>

                                <p>d. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ歩ｹ霞ｸｭ犧�ｹ�ｸ｡犹� 犧歩ｸｱ犧扉ｸ歩ｹ謂ｸｭ 犹犧巵ｸｴ犧扉ｹ犧憫ｸ｢ 犧ｫ犧｣犧ｷ犧ｭ犧ｭ犹謂ｸｲ犧｢犹もｸｭ犧吭ｸもｹ霞ｸｭ犧｡犧ｹ犧･犧もｹ霞ｸｭ犧｡犧ｹ犧･犹�ｸ巵ｸ｣犧ｹ犧巵ｹ≒ｸ壟ｸ壟ｸ≒ｸｲ犧｣ Upload, Download 犧ｫ犧｣犧ｷ犧ｭ犧ｧ犧ｴ犧倨ｸｵ犧≒ｸｲ犧｣犹�ｸ扉ｸ≒ｹ�ｸ歩ｸｲ犧｡犧伶ｸｵ犹謂ｸｭ犧ｲ犧謂ｸ≒ｹ謂ｸｭ犹�ｸｫ犹� 犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢犹≒ｸ≒ｹ謂ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犹もｸ扉ｸ｢犹犧扉ｹ�ｸ扉ｸもｸｲ犧� 犧扉ｸｱ犧�ｹ犧癌ｹ謂ｸ� 犧もｹ霞ｸｭ犧｡犧ｹ犧･ 犧ｪ犧ｴ犹謂ｸ�ｸｪ犹謂ｸ�ｸ樅ｸｴ犧｡犧樅ｹ呉ｸｭ犧ｴ犹犧･犹�ｸ≒ｸ伶ｸ｣犧ｭ犧吭ｸｴ犧≒ｸｪ犹呉ｸ伶ｸｵ犹謂ｹ犧巵ｹ�ｸ吭ｸ≒ｸｲ犧｣犧･犧ｰ犹犧｡犧ｴ犧扉ｸ･犧ｴ犧もｸｪ犧ｴ犧伶ｸ倨ｸｴ犹呉ｸｫ犧｣犧ｷ犧ｭ犹犧巵ｹ�ｸ吭ｸもｹ霞ｸｭ犧｡犧ｹ犧･犧もｸｭ犧�ｸ憫ｸｹ犹霞ｸ｣犹霞ｸｭ犧� 犧謂ｹ謂ｸｭ犧ｭ犧ｷ犹謂ｸ� 犧もｹ霞ｸｭ犧｡犧ｹ犧･犧伶ｸｵ犹謂ｹ犧巵ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧･犧ｱ犧壟ｹ�ｸ吭ｹ犧｣犧ｷ犹謂ｸｭ犧�ｹ犧≒ｸｵ犹謂ｸ｢犧ｧ犧≒ｸｱ犧壟ｸ倨ｸｸ犧｣犧≒ｸｴ犧� 犧樅ｸ吭ｸｱ犧≒ｸ�ｸｲ犧� 犧ｫ犧｣犧ｷ犧ｭ犧壟ｸｸ犧�ｸ�ｸ･犧�犧ｲ犧｢犧吭ｸｭ犧� 犹犧巵ｹ�ｸ吭ｸ歩ｹ霞ｸ�</p>

                                <div>
                                    <p class="font-bold text-slate-800">2. 犹犧｡犧ｷ犹謂ｸｭ犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸｧ犹謂ｸｲ犧�ｸ謂ｸｲ犧≒ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸｪ犹謂ｸｧ犧吭ｸ壟ｸｸ犧�ｸ�ｸ･犹犧≒ｸｴ犧� 5 犧吭ｸｲ犧伶ｸｵ 犹�ｸｫ犹霞ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ伶ｸｳ犧≒ｸｲ犧｣犧巵ｸ≒ｸｫ犧吭ｹ霞ｸｲ犧謂ｸｭ (Lock Screen) 犧ｫ犧｣犧ｷ犧ｭ犧伶ｸｳ犧≒ｸｲ犧｣犧巵ｸｴ犧扉ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧� (Shut Down) 犹犧｡犧ｷ犹謂ｸｭ犧≒ｸｲ犧｣犧巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧�ｸｲ犧吭ｸ巵ｸ｣犧ｰ犧謂ｸｳ犧ｧ犧ｱ犧吭ｹ犧ｪ犧｣犹�ｸ謂ｸｪ犧ｴ犹霞ｸ� 犹もｸ扉ｸ｢</p>
                                    <div class="pl-6 space-y-2 mt-2">
                                        <p>a. 犧≒ｸ｣犧内ｸｵ犧伶ｸｵ犹謂ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｢犧ｴ犧吭ｸ｢犧ｭ犧｡犹�ｸｫ犹霞ｸ憫ｸｹ犹霞ｸｭ犧ｷ犹謂ｸ吭ｹ�ｸ癌ｹ霞ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸもｸｭ犧�ｸ歩ｸ吭ｹ犧ｭ犧�ｸｫ犧｣犧ｷ犧ｭ 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ謂ｸｰ犧歩ｹ霞ｸｭ犧�ｸ伶ｸｳ犧≒ｸｲ犧｣犧ｭ犧ｭ犧≒ｸ謂ｸｲ犧≒ｸ｣犧ｰ犧壟ｸ� (Logout) 犧伶ｸｱ犧吭ｸ伶ｸｵ 犹犧樅ｸｷ犹謂ｸｭ犧伶ｸｵ犹謂ｸ憫ｸｹ犹霞ｸ｢犧ｷ犧｡犹犧もｹ霞ｸｲ犧伶ｸｳ犧≒ｸｲ犧｣犹犧もｹ霞ｸｲ犹�ｸ癌ｹ霞ｸ歩ｸｱ犧ｧ犹犧ｭ犧� (Login) 犧扉ｹ霞ｸｧ犧｢犧壟ｸｱ犧財ｸ癌ｸｵ犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� (User Account) 犹≒ｸ･犧ｰ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧� (Password) 犧もｸｭ犧�ｸ歩ｸ吭ｹ犧ｭ犧�ｹ犧伶ｹ謂ｸｲ犧吭ｸｱ犹霞ｸ�</p>
                                        <p>b. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ謂ｸｰ犧歩ｹ霞ｸｭ犧�ｹ�ｸ｡犹謂ｹ犧巵ｸｴ犧扉ｹ犧憫ｸ｢犧樅ｸｲ犧ｪ犧ｪ犹呉ｹ犧ｧ犧ｴ犧｣犹呉ｸ扉ｸ歩ｸ吭ｹ犧ｭ犧�ｹ�ｸｫ犹霞ｸ�ｸ吭ｸｭ犧ｷ犹謂ｸ吭ｸ伶ｸ｣犧ｲ犧� 犧ｫ犧｣犧ｷ犧ｭ犧壟ｸｸ犧�ｸ�ｸ･犧ｭ犧ｷ犹謂ｸ吭ｹ�ｸ扉ｸ≒ｹ�ｸ歩ｸｲ犧｡犹�ｸｫ犹霞ｸ｣犧ｱ犧壟ｸ｣犧ｹ犹霞ｸｫ犧｣犧ｷ犧ｭ 犧≒ｸｸ犧財ｹ≒ｸ� 犧扉ｸｱ犧�ｹ犧癌ｹ謂ｸ� 犧≒ｸｲ犧｣犧壟ｸｸ犧≒ｸ｣犧ｸ犧� (Hack) 犹犧謂ｸｲ犧ｰ犹犧もｹ霞ｸｲ犧｣犧ｰ犧壟ｸ�</p>
                                    </div>
                                </div>

                                <p class="font-bold text-slate-800">3. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� (User Account) 犧もｸｭ犧�ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ伶ｸｵ犹謂ｸ籾ｸｹ犧≒ｸ歩ｸｱ犹霞ｸ�ｸもｸｶ犹霞ｸ吭ｸ｡犧ｲ犹犧樅ｸｷ犹謂ｸｭ犹犧もｹ霞ｸｲ犧籾ｸｶ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸもｸｭ犧�ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧≒ｸｲ犧｣犹犧もｸｵ犧｢犧� 犹犧憫ｸ｢犹≒ｸ樅ｸ｣犹謂ｸもｹ霞ｸｭ犧�ｸｧ犧ｲ犧｡犹�ｸ� 犹� 犧伶ｸｵ犹� 犧ｭ犧ｲ犧謂ｸ≒ｹ謂ｸｭ犹�ｸｫ犹霞ｹ犧≒ｸｴ犧扉ｸ�ｸｧ犧ｲ犧｡犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢犧ｭ犧ｲ犧� 犹犧ｪ犧ｷ犹謂ｸｭ犧｡犹犧ｪ犧ｵ犧｢犹≒ｸ≒ｹ謂ｸ憫ｸｹ犹霞ｸｭ犧ｷ犹謂ｸ� 犧≒ｸｲ犧｣犹�ｸ癌ｹ霞ｹ犧樅ｸｷ犹謂ｸｭ犧ｭ犧ｸ犧巵ｸ≒ｸ｣犧内ｹ呉ｸ伶ｸｵ犹謂ｹ�ｸ｡犹謂ｸｪ犧ｸ犧�犧ｲ犧� 犹犧巵ｹ�ｸ吭ｸ歩ｹ霞ｸ� 犧ｫ犧ｲ犧≒ｸ｡犧ｵ犧≒ｸｲ犧｣犧≒ｸ｣犧ｰ犧伶ｸｳ犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢犧謂ｸｲ犧≒ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� 犧謂ｸｰ犧歩ｹ霞ｸｭ犧�ｸ｣犧ｱ犧壟ｸ憫ｸｴ犧扉ｸ癌ｸｭ犧壟ｸ癌ｸ扉ｹ犧癌ｸ｢</p>

                                <p>3. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ歩ｹ霞ｸｭ犧�ｹ�ｸ｡犹謂ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧≒ｸｲ犧｣犹�ｸ� 犹� 犧伶ｸｵ犹謂ｹ犧巵ｹ�ｸ吭ｸ≒ｸｲ犧｣犧もｸｱ犧扉ｸ歩ｹ謂ｸｭ犧≒ｸ錫ｸｫ犧｡犧ｲ犧｢犧ｧ犹謂ｸｲ犧扉ｹ霞ｸｧ犧｢犧≒ｸｲ犧｣犧巵ｸ｣犧ｰ犧≒ｸｭ犧壟ｸ≒ｸｴ犧謂ｸ≒ｸ｣犧｣犧｡犧ｧ犹謂ｸｲ犧扉ｹ霞ｸｧ犧｢犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹� 犧伶ｸｳ犹�ｸｫ犹霞ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧｣犧ｱ犧壟ｸ�ｸｧ犧ｲ犧｡犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧� 犧｡犧ｵ犧≒ｸｲ犧｣犧≒ｸ｣犧ｰ犧伶ｸｳ犧≒ｸｲ犧｣犹�ｸ� 犹� 犧伶ｸｵ犹謂ｸ｣犧ｰ犧壟ｸ壟ｸ�ｹ謂ｸｲ 犧ｭ犹霞ｸｭ犧｡犧籾ｹ霞ｸｲ犧ｧ犹謂ｸｲ 犧｢犹謂ｸｭ犧｡犧籾ｸｷ犧ｭ犧ｧ犹謂ｸｲ犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ憫ｸｹ犹霞ｸ吭ｸｱ犹霞ｸ吭ｹ犧巵ｹ�ｸ吭ｸ憫ｸｴ犧扉ｸ｣犧ｰ犧壟ｸ壟ｸ�ｸｧ犧ｲ犧｡犧｣犧ｱ犧壟ｸ憫ｸｴ犧扉ｸ癌ｸｭ犧壟ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ</p>

                                <p class="font-bold text-slate-800">4. 犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ歩ｹ霞ｸｭ犧�ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧歩ｸｲ犧｡犹犧�ｸｷ犹謂ｸｭ犧吭ｹ�ｸ� 犧≒ｸ錫ｸ｣犧ｰ犹犧壟ｸｵ犧｢犧� 犧もｹ霞ｸｭ犧壟ｸｱ犧�ｸ�ｸｱ犧壟ｸ伶ｸｵ犹謂ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧≒ｸｳ犧ｫ犧吭ｸ扉ｹ�ｸｧ犹� 犹もｸ扉ｸ｢犹犧｡犧ｷ犹謂ｸｭ犧･犧�ｸ吭ｸｲ犧｡犹�ｸ吭ｹ�ｸ壟ｸｪ犧ｳ犹犧｣犹�ｸ� 犧ｫ犧｡犧ｵ 犹犧ｪ犧｡犧ｷ犧ｭ犧吭ｸ巵ｸ｣犧ｰ犧≒ｸｲ犧ｨ犧謂ｸｲ犧≒ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ</p>

                                <p class="font-bold text-slate-800">5. 犧壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧もｸｭ犧ｪ犧�ｸｧ犧吭ｸｪ犧ｴ犧伶ｸ倨ｸｴ犹呉ｹ�ｸ吭ｸ≒ｸｲ犧｣犧歩ｸ｣犧ｧ犧謂ｸｪ犧ｭ犧壟ｹ犧�ｸ｣犧ｷ犹謂ｸｭ犧�ｸ�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸもｸｭ犧�ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧� 犹�ｸ吭ｸ≒ｸ｣犧内ｸｵ犧伶ｸｵ犹謂ｸ樅ｸ壟ｹ犧ｫ犹�ｸ吭ｸ≒ｸｲ犧｣犹�ｸ｡犹謂ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧歩ｸｲ犧｡犧｣犧ｰ犹犧壟ｸｵ犧｢犧壟ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｹもｸ扉ｸ｢犧｡犧ｵ犧歩ｹ霞ｸｭ犧�ｹ≒ｸ謂ｹ霞ｸ�ｹ�ｸｫ犹霞ｸ伶ｸ｣犧ｲ犧壟ｸ･犹謂ｸｧ犧�ｸｫ犧吭ｹ霞ｸｲ</p>

                                <div>
                                    <p class="font-bold text-slate-800">6. 犧ｫ犧ｲ犧≒ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ杳ｹ謂ｸｲ犧杳ｸｷ犧吭ｸｫ犧｣犧ｷ犧ｭ犹�ｸ｡犹謂ｸ巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧歩ｸｲ犧｡犧｣犧ｰ犹犧壟ｸｵ犧｢犧壟ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹� 犧壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犧謂ｸｰ犧樅ｸｴ犧謂ｸｲ犧｣犧内ｸｲ犧壟ｸ伶ｸ･犧�ｹもｸ伶ｸｩ 犧伶ｸｱ犧吭ｸ伶ｸｵ 犧歩ｸｲ犧｡犧�ｸｧ犧ｲ犧｡犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢犧伶ｸｵ犹謂ｹ犧≒ｸｴ犧扉ｸもｸｶ犹霞ｸ� 犧扉ｸｱ犧�ｸ歩ｹ謂ｸｭ犹�ｸ巵ｸ吭ｸｵ犹�</p>
                                    <ul class="list-disc pl-10 mt-2 space-y-1">
                                        <li>犧歩ｸｱ犧≒ｹ犧歩ｸｷ犧ｭ犧吭ｸ扉ｹ霞ｸｧ犧｢犧ｧ犧ｲ犧謂ｸｲ</li>
                                        <li>犧歩ｸｱ犧≒ｹ犧歩ｸｷ犧ｭ犧吭ｹ犧巵ｹ�ｸ吭ｸ･犧ｲ犧｢犧･犧ｱ犧≒ｸｩ犧内ｹ呉ｸｭ犧ｱ犧≒ｸｩ犧｣</li>
                                        <li>犹�ｸ｡犹謂ｸ樅ｸｴ犧謂ｸｲ犧｣犧内ｸｲ犧もｸｶ犹霞ｸ吭ｹ犧�ｸｴ犧吭ｹ犧扉ｸｷ犧ｭ犧�</li>
                                        <li>犹犧･犧ｴ犧≒ｸ謂ｹ霞ｸｲ犧�ｹもｸ扉ｸ｢犹�ｸ｡犹謂ｸ謂ｹ謂ｸｲ犧｢犧�ｹ謂ｸｲ犧癌ｸ扉ｹ犧癌ｸ｢</li>
                                        <li>犧游ｹ霞ｸｭ犧�ｸ｣犹霞ｸｭ犧�ｹ≒ｸ･犧ｰ犹犧｣犧ｵ犧｢犧≒ｸ�ｹ謂ｸｲ犹犧ｪ犧ｵ犧｢犧ｫ犧ｲ犧｢</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Consolidated Acceptance Section --}}
                        <div class="bg-blue-50/50 rounded-3xl border-2 border-blue-100 p-10 md:p-14 space-y-8 no-print shadow-sm">
                            <div class="space-y-6 max-w-3xl mx-auto">
                                <div class="flex items-start gap-5">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5 shadow-lg shadow-blue-200">1</div>
                                    <p class="text-[13px] text-slate-700 font-bold leading-relaxed">
                                        犧もｹ霞ｸｲ犧樅ｹ犧謂ｹ霞ｸｲ犹�ｸ扉ｹ霞ｸ｣犧ｱ犧壟ｸもｹ霞ｸｭ犧｡犧ｹ犧･犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ≒ｸ･犧ｰ犧｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧吭ｹ犧巵ｹ�ｸ吭ｸ伶ｸｵ犹謂ｹ犧｣犧ｵ犧｢犧壟ｸ｣犹霞ｸｭ犧｢犹≒ｸ･犹霞ｸｧ 犹≒ｸ･犧ｰ犹�ｸ扉ｹ霞ｸ伶ｸｳ犧≒ｸｲ犧｣犹犧巵ｸ･犧ｵ犹謂ｸ｢犧吭ｹ≒ｸ巵ｸ･犧�ｹ≒ｸ≒ｹ霞ｹ�ｸもｸ｣犧ｫ犧ｱ犧ｪ犧憫ｹ謂ｸｲ犧吭ｹ�ｸ吭ｸ�ｸ｣犧ｱ犹霞ｸ�ｹ≒ｸ｣犧≒ｸ伶ｸｵ犹謂ｹ犧もｹ霞ｸｲ犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｹ≒ｸ･犧ｰ犧謂ｸｰ犹犧≒ｹ�ｸ壟ｸもｹ霞ｸｭ犧｡犧ｹ犧･犧扉ｸｱ犧�ｸ≒ｸ･犹謂ｸｲ犧ｧ犹犧巵ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧･犧ｱ犧�
                                    </p>
                                </div>
                                <div class="flex items-start gap-5">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5 shadow-lg shadow-blue-200">2</div>
                                    <p class="text-[13px] text-slate-700 font-bold leading-relaxed">
                                        犧もｹ霞ｸｲ犧樅ｹ犧謂ｹ霞ｸｲ犹�ｸ扉ｹ霞ｸｭ犹謂ｸｲ犧吭ｹ≒ｸ･犧ｰ犧伶ｸｳ犧�ｸｧ犧ｲ犧｡犹犧もｹ霞ｸｲ犹�ｸ謂ｸ｣犧ｰ犹犧壟ｸｵ犧｢犧壟ｸ≒ｸｲ犧｣犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｣犧ｰ犧壟ｸ壟ｹ犧�ｸ｣犧ｷ犧ｭ犧もｹ謂ｸｲ犧｢犹≒ｸ･犧ｰ犧�ｸｭ犧｡犧樅ｸｴ犧ｧ犹犧歩ｸｭ犧｣犹呉ｸもｸｭ犧�ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧伶ｸｯ 犹≒ｸ･犹霞ｸｧ 犧もｹ霞ｸｲ犧樅ｹ犧謂ｸ｢犧ｴ犧吭ｸ｢犧ｭ犧｡犧伶ｸｵ犹謂ｸ謂ｸｰ犧巵ｸ鐘ｸｴ犧壟ｸｱ犧歩ｸｴ犧歩ｸｲ犧｡犧｣犧ｰ犹犧壟ｸｵ犧｢犧壟ｸ扉ｸｱ犧�ｸ≒ｸ･犹謂ｸｲ犧ｧ犧ｭ犧｢犹謂ｸｲ犧�ｹ犧�ｸ｣犹謂ｸ�ｸ�ｸ｣犧ｱ犧�
                                    </p>
                                </div>
                            </div>

                            @if(!$request->user_acknowledged_at)
                                @if($request->it_status === 'completed' && $request->user_id === Auth::id())
                                    <form action="{{ route('tracking.acknowledge', $request->request_no) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xl shadow-xl shadow-blue-200 transition transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            犧｢犧ｷ犧吭ｸ｢犧ｱ犧吭ｸ｣犧ｱ犧壟ｸ伶ｸ｣犧ｲ犧壟ｹ≒ｸ･犧ｰ犧｢犧ｭ犧｡犧｣犧ｱ犧壟ｸ｣犧ｰ犹犧壟ｸｵ犧｢犧�
                                        </button>
                                    </form>
                                @else
                                    <div class="p-4 bg-white/50 rounded-xl border border-dashed border-blue-200 text-center">
                                        <p class="text-sm text-blue-400 italic">
                                            @if($request->it_status !== 'completed')
                                                犧｣犧ｭ犹犧謂ｹ霞ｸｲ犧ｫ犧吭ｹ霞ｸｲ犧伶ｸｵ犹� IT 犧扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣犧歩ｸｱ犹霞ｸ�ｸ�ｹ謂ｸｲ犧｣犧ｰ犧壟ｸ壟ｹ�ｸｫ犹霞ｹ犧ｪ犧｣犹�ｸ謂ｸｪ犧ｴ犹霞ｸ吭ｸ≒ｹ謂ｸｭ犧吭ｸ謂ｸｶ犧�ｸ謂ｸｰ犧ｪ犧ｲ犧｡犧ｲ犧｣犧籾ｸ≒ｸ扉ｸ｢犧ｷ犧吭ｸ｢犧ｱ犧吭ｹ�ｸ扉ｹ�
                                            @else
                                                犧｣犧ｭ犧扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣犧｢犧ｷ犧吭ｸ｢犧ｱ犧吭ｸ謂ｸｲ犧≒ｸ憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧�
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="bg-green-600 rounded-2xl py-4 px-6 text-white flex items-center justify-between shadow-lg shadow-green-100">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div>
                                            <p class="font-bold text-lg">犧扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣犧｢犧ｷ犧吭ｸ｢犧ｱ犧吭ｹ犧｣犧ｵ犧｢犧壟ｸ｣犹霞ｸｭ犧｢犹≒ｸ･犹霞ｸｧ</p>
                                            <p class="text-xs text-green-100">犧壟ｸｱ犧吭ｸ伶ｸｶ犧≒ｸもｹ霞ｸｭ犧｡犧ｹ犧･犹犧｡犧ｷ犹謂ｸｭ: {{ $request->user_acknowledged_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Print version signature --}}
                        <div class="print-block mt-8">
                            <div class="flex justify-end">
                                <div class="text-center w-64 space-y-1">
                                    <p class="text-[9px] text-slate-500">犧･犧�ｸ癌ｸｷ犹謂ｸｭ...................................................................犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ｣犧ｱ犧壟ｸ伶ｸ｣犧ｲ犧�</p>
                                    <p class="text-[9px] text-slate-500">(..............................................................)</p>
                                    <p class="text-[9px] text-slate-500">犧ｧ犧ｱ犧吭ｸ伶ｸｵ犹�........../........../...........</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Interaction 犧ｪ犧ｳ犧ｫ犧｣犧ｱ犧壟ｸ憫ｸｹ犹霞ｸｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ --}}
            @php
                $currentStep = $request->steps->where('step_order', $request->current_step)->where('status', 'pending')->first();
                $isAdmin = Auth::user()->role === 'admin';
                $isMyTurn = $currentStep && ($currentStep->approver_id == Auth::id() || $isAdmin);
            @endphp

            @if($isMyTurn)
                <div class="mt-8 no-print animate-fade-in" x-data="{ showForm: false, actionType: '', useExisting: {{ Auth::user()->signature ? 'true' : 'false' }} }">
                    <div x-show="!showForm" class="bg-blue-600 rounded-3xl p-6 md:p-8 text-center shadow-xl shadow-blue-200">
                        <h3 class="text-lg md:text-xl font-bold text-white mb-2">{{ $isAdmin && $currentStep->approver_id != Auth::id() ? 'Administrator Action' : '犧≒ｸ｣犧ｸ犧内ｸｲ犧扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ' }}</h3>
                        <p class="text-white/70 text-xs md:text-sm mb-6">犧�ｸｳ犧｣犹霞ｸｭ犧�ｸ吭ｸｵ犹霞ｸｭ犧｢犧ｹ犹謂ｹ�ｸ吭ｸ�ｸｧ犧ｲ犧｡犧｣犧ｱ犧壟ｸ憫ｸｴ犧扉ｸ癌ｸｭ犧壟ｸもｸｭ犧�: {{ $currentStep->approver->fullname ?? 'N/A' }}</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <button @click="showForm = true; actionType = 'approve'" class="w-full sm:w-auto px-10 py-3 bg-white text-blue-600 rounded-2xl font-bold hover:bg-blue-50 transition">犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ犧�ｸｳ犧｣犹霞ｸｭ犧�</button>
                            <button @click="showForm = true; actionType = 'reject'" class="w-full sm:w-auto px-10 py-3 bg-red-500 text-white rounded-2xl font-bold hover:bg-red-600 transition">犧巵ｸ鐘ｸｴ犹犧ｪ犧�</button>
                        </div>
                    </div>

                    <div x-show="showForm" class="bg-white rounded-3xl p-8 shadow-xl border border-slate-200">
                        <form :action="actionType === 'approve' ? '{{ route('manage.approvals.approve', $currentStep->id ?? 0) }}' : '{{ route('manage.approvals.reject', $currentStep->id ?? 0) }}'" method="POST" id="approval-form">
                            @csrf
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-slate-800" x-text="actionType === 'approve' ? '犧｢犧ｷ犧吭ｸ｢犧ｱ犧吭ｸ≒ｸｲ犧｣犧ｭ犧吭ｸｸ犧｡犧ｱ犧歩ｸｴ' : '犧｢犧ｷ犧吭ｸ｢犧ｱ犧吭ｸ≒ｸｲ犧｣犧巵ｸ鐘ｸｴ犹犧ｪ犧�'"></h3>
                                <textarea name="remark" rows="3" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500" placeholder="犧｣犧ｰ犧壟ｸｸ犧ｫ犧｡犧ｲ犧｢犹犧ｫ犧歩ｸｸ犧≒ｸｲ犧｣犧歩ｸｱ犧扉ｸｪ犧ｴ犧吭ｹ�ｸ� (犧籾ｹ霞ｸｲ犧｡犧ｵ)..."></textarea>

                                <div x-show="actionType === 'approve'" class="space-y-4">
                                    <label class="block text-sm font-bold text-slate-700">犧･犧�ｸ吭ｸｲ犧｡犧･犧ｲ犧｢犧｡犧ｷ犧ｭ犧癌ｸｷ犹謂ｸｭ</label>
                                    @if(Auth::user()->signature)
                                        <label class="flex items-center gap-2 cursor-pointer mb-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                            <input type="checkbox" name="use_existing" value="1" x-model="useExisting" class="rounded text-blue-600">
                                            <span class="text-xs text-slate-500 font-bold uppercase">犹�ｸ癌ｹ霞ｸ･犧ｲ犧｢犹犧金ｹ�ｸ吭ｸ伶ｸｵ犹謂ｸ壟ｸｱ犧吭ｸ伶ｸｶ犧≒ｹ�ｸ吭ｸ｣犧ｰ犧壟ｸ�</span>
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
                                    <button type="button" @click="showForm = false" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-bold">犧｢犧≒ｹ犧･犧ｴ犧�</button>
                                    <button type="submit" class="flex-2 w-full py-4 rounded-2xl font-bold text-white shadow-lg" :class="actionType === 'approve' ? 'bg-blue-600 shadow-blue-100' : 'bg-red-500 shadow-red-100'">
                                        犧｢犧ｷ犧吭ｸ｢犧ｱ犧吭ｸ扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif


            {{-- 犧ｪ犹謂ｸｧ犧吭ｸ伶ｸｵ犹� 5: 犧もｹ霞ｸｭ犧歩ｸ≒ｸ･犧�ｸ｣犧ｱ犧≒ｸｩ犧ｲ犧�ｸｧ犧ｲ犧｡犧･犧ｱ犧� (NDA) --}}
            @if($request->status == 'completed')
            <div class="mt-8 space-y-6 no-print">
                 <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest bg-slate-100 py-2 px-4 rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-file-contract text-blue-600"></i>
                    犧もｹ霞ｸｭ犧歩ｸ≒ｸ･犧�ｸ｣犧ｱ犧≒ｸｩ犧ｲ犧�ｸｧ犧ｲ犧｡犧･犧ｱ犧� (NDA)
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
                                        <h4 class="font-bold text-slate-800">犧壟ｸｱ犧吭ｸ伶ｸｶ犧≒ｸもｹ霞ｸｭ犧歩ｸ≒ｸ･犧�ｸ｣犧ｱ犧≒ｸｩ犧ｲ犧�ｸｧ犧ｲ犧｡犧･犧ｱ犧壟ｹ犧｣犧ｵ犧｢犧壟ｸ｣犹霞ｸｭ犧｢犹≒ｸ･犹霞ｸｧ</h4>
                                        <p class="text-xs text-slate-400">犹犧ｪ犧｣犹�ｸ謂ｸｪ犧｡犧壟ｸｹ犧｣犧内ｹ呉ｹ犧｡犧ｷ犹謂ｸｭ {{ $nda->witness2_agreed_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500">
                                        <i class="fa-solid fa-clock text-2xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">犧ｭ犧｢犧ｹ犹謂ｸ｣犧ｰ犧ｫ犧ｧ犹謂ｸｲ犧�ｸ｣犧ｭ犧樅ｸ｢犧ｲ犧吭ｸ･犧�ｸ吭ｸｲ犧｡犧｣犧ｱ犧壟ｸ｣犧ｭ犧�</h4>
                                        <p class="text-xs text-slate-400">犧壟ｸｱ犧吭ｸ伶ｸｶ犧≒ｸもｹ霞ｸｭ犧｡犧ｹ犧･犹犧壟ｸｷ犹霞ｸｭ犧�ｸ歩ｹ霞ｸ吭ｹ犧｡犧ｷ犹謂ｸｭ {{ $nda->agreement_date ? $nda->agreement_date->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="flex gap-2">
                                <a href="{{ route('request.nda', $request->request_no) }}" 
                                    class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                                    犧扉ｸｹ犧｣犧ｲ犧｢犧･犧ｰ犹犧ｭ犧ｵ犧｢犧� NDA
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
                                    <h4 class="text-xl font-bold text-slate-800">犧≒ｸ｣犧ｸ犧内ｸｲ犧壟ｸｱ犧吭ｸ伶ｸｶ犧≒ｸもｹ霞ｸｭ犧歩ｸ≒ｸ･犧�ｸ｣犧ｱ犧≒ｸｩ犧ｲ犧�ｸｧ犧ｲ犧｡犧･犧ｱ犧�</h4>
                                    <p class="text-sm text-slate-500 max-w-md mx-auto mt-2">犧≒ｸ｣犧ｸ犧内ｸｲ犧扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣犧壟ｸｱ犧吭ｸ伶ｸｶ犧≒ｸもｹ霞ｸｭ犧歩ｸ≒ｸ･犧�ｸ｣犧ｱ犧≒ｸｩ犧ｲ犧�ｸｧ犧ｲ犧｡犧･犧ｱ犧� (NDA) 犹犧樅ｸｷ犹謂ｸｭ犹�ｸｫ犹霞ｸ≒ｸ｣犧ｰ犧壟ｸｧ犧吭ｸ≒ｸｲ犧｣犧｣犹霞ｸｭ犧�ｸもｸｭ犧ｪ犧ｴ犧伶ｸ倨ｸｴ犹�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸｪ犧ｲ犧｣犧ｪ犧吭ｹ犧伶ｸｨ犧ｪ犧｡犧壟ｸｹ犧｣犧内ｹ呉ｸ歩ｸｲ犧｡犧｣犧ｰ犹犧壟ｸｵ犧｢犧壟ｸもｸｭ犧�ｸ壟ｸ｣犧ｴ犧ｩ犧ｱ犧�</p>
                                </div>
                                <a href="{{ route('request.nda', $request->request_no) }}" 
                                    class="inline-flex items-center gap-3 px-12 py-4 bg-blue-600 text-white rounded-2xl font-bold uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition transform hover:-translate-y-1">
                                    犹犧｣犧ｴ犹謂ｸ｡犧壟ｸｱ犧吭ｸ伶ｸｶ犧≒ｸもｹ霞ｸｭ犧歩ｸ≒ｸ･犧�ｸ｣犧ｱ犧≒ｸｩ犧ｲ犧�ｸｧ犧ｲ犧｡犧･犧ｱ犧�
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="flex items-center gap-4 py-4">
                                <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-600 italic">犧ｭ犧｢犧ｹ犹謂ｸ｣犧ｰ犧ｫ犧ｧ犹謂ｸｲ犧�ｸ｣犧ｭ犧憫ｸｹ犹霞ｹ�ｸ癌ｹ霞ｸ�ｸｲ犧吭ｸ壟ｸｱ犧吭ｸ伶ｸｶ犧≒ｸもｹ霞ｸｭ犧歩ｸ≒ｸ･犧�ｸ｣犧ｱ犧≒ｸｩ犧ｲ犧�ｸｧ犧ｲ犧｡犧･犧ｱ犧�</h4>
                                    <p class="text-[10px] text-slate-400">犧憫ｸｹ犹霞ｸもｸｭ犧｣犧ｱ犧壟ｸｪ犧ｴ犧伶ｸ倨ｸｴ犧謂ｸｰ犧歩ｹ霞ｸｭ犧�ｸ扉ｸｳ犹犧吭ｸｴ犧吭ｸ≒ｸｲ犧｣犹�ｸ吭ｸｪ犹謂ｸｧ犧吭ｸ吭ｸｵ犹霞ｹ犧樅ｸｷ犹謂ｸｭ犹�ｸｫ犹霞ｸ≒ｸｲ犧｣犧｣犹霞ｸｭ犧�ｸもｸｭ犧ｪ犧｡犧壟ｸｹ犧｣犧内ｹ�</p>
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
                        if (signaturePad.isEmpty()) { e.preventDefault(); alert('犧≒ｸ｣犧ｸ犧内ｸｲ犧･犧�ｸ吭ｸｲ犧｡犧･犧ｲ犧｢犧｡犧ｷ犧ｭ犧癌ｸｷ犹謂ｸｭ'); return; }
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