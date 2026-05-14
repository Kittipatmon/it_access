@extends('layouts.admin')

@section('breadcrumb', 'กำหนดผู้ลงนาม NDA ในนามบริษัท')

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

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">NDA Company Representative</h2>
            <p class="text-sm text-slate-400">ระบุผู้มีอำนาจลงนามในนามบริษัทสำหรับเอกสารข้อตกลงรักษาความลับ (NDA)</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="p-8 md:p-10">
                <form action="{{ route('backend.nda-config.update') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-4">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">ผู้ลงนามในนามบริษัท (Authorized Representative)</label>
                        <select name="representative_id" id="representative_id" required class="w-full">
                            <option value="">เลือกพนักงาน...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ ($setting && $setting->value == $user->id) ? 'selected' : '' }}>
                                    {{ $user->fullname }} [{{ $user->emp_code }}] ({{ $user->department_name }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 italic">รายชื่อที่เลือกจะถูกนำไปแสดงในหน้าลงนาม NDA ฝั่งบริษัท พร้อมลายเซ็นที่พนักงานท่านนี้อัปโหลดไว้ในโปรไฟล์</p>
                    </div>

                    @if($setting && $setting->value)
                        @php $currentRep = \App\Models\User::find($setting->value); @endphp
                        @if($currentRep)
                        <div class="bg-slate-50 rounded-2xl p-6 flex items-center gap-6 border border-slate-100">
                            <div class="w-20 h-20 bg-white border border-slate-200 rounded-xl flex items-center justify-center overflow-hidden">
                                @if($currentRep->signature)
                                    <img src="{{ asset('storage/signatures/' . $currentRep->signature) }}" class="max-h-full object-contain mix-blend-multiply">
                                @else
                                    <i class="fa-solid fa-signature text-slate-200 text-3xl"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">ตัวอย่างลายเซ็นปัจจุบัน</p>
                                <p class="text-sm font-bold text-slate-700">{{ $currentRep->fullname }}</p>
                                <p class="text-xs text-slate-400">{{ $currentRep->department_name }}</p>
                            </div>
                        </div>
                        @endif
                    @endif

                    <div class="pt-6 border-t border-slate-50">
                        <button type="submit" 
                            class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold transition text-sm tracking-widest uppercase shadow-xl shadow-blue-100">
                            บันทึกการตั้งค่า
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <a href="{{ route('backend.dashboard') }}" class="flex items-center gap-2 text-slate-400 hover:text-slate-600 transition text-sm font-bold mx-auto w-fit">
            <i class="fa-solid fa-arrow-left"></i>
            กลับไปยังแดชบอร์ด
        </a>
    </div>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('turbo:load', function() {
            const el = document.getElementById('representative_id');
            if (el && !el.tomselect) {
                new TomSelect(el, {
                    create: false,
                    placeholder: 'ค้นหาชื่อพนักงาน...',
                    allowEmptyOption: false,
                    maxOptions: null, // Show all options in search
                });
            }
        });
    </script>
@endsection
