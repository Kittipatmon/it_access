@extends('layouts.app')

@section('title', 'จัดการลายมือชื่อ')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4" x-data="{ method: 'draw', loading: false, previewUrl: null, fileName: '' }">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">จัดการลายมือชื่อ (My Signature)</h1>
                <p class="text-slate-500 mt-1 text-sm">บันทึกลายเซ็นของคุณไว้เพื่อใช้ในการส่งคำร้องและอนุมัติอย่างรวดเร็ว
                </p>
            </div>
            <a href="{{ Auth::user()->role === 'admin' || Auth::user()->dept_id == 16 ? route('backend.dashboard') : route('request.index') }}"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition text-sm font-bold flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>กลับหน้าหลัก</span>
            </a>
        </div>

        @if(session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center space-x-3 animate-fade-in">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Current Signature -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 h-full">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">ลายเซ็นปัจจุบัน</h3>

                    @if($user->signature)
                        <div
                            class="bg-slate-50 rounded-2xl p-6 flex flex-col items-center justify-center border border-slate-100">
                            <div class="bg-white p-4 rounded-xl border border-slate-50 shadow-sm inline-block">
                                <img src="{{ asset('storage/signatures/' . $user->signature) }}" alt="Current Signature"
                                    class="max-h-32 w-auto">
                            </div>
                            <p class="text-[10px] text-slate-400 mt-4 italic">บันทึกเมื่อ:
                                {{ $user->signature_updated_at ? $user->signature_updated_at->format('d/m/Y H:i') : '-' }}
                            </p>
                        </div>
                    @else
                        <div
                            class="bg-slate-50 rounded-2xl p-8 flex flex-col items-center justify-center border border-dashed border-slate-200 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 opacity-20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            <p class="text-xs font-bold uppercase tracking-tight">ยังไม่มีลายเซ็น</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Signature Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                    <form action="{{ route('backend.profile.signature.update') }}" method="POST"
                        enctype="multipart/form-data" id="signature-form" @submit="loading = true">
                        @csrf

                        <div class="space-y-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">อัปเดตลายเซ็นใหม่
                                </h3>

                                <div class="inline-flex p-1 bg-slate-50 rounded-2xl border border-slate-100">
                                    <button type="button" @click="method = 'draw'; setTimeout(() => resizeCanvas(), 100)"
                                        :class="method === 'draw' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                                        class="px-4 py-2 rounded-xl text-[10px] font-bold transition-all duration-200 flex items-center space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span>วาดใหม่</span>
                                    </button>
                                    <button type="button" @click="method = 'upload'"
                                        :class="method === 'upload' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                                        class="px-4 py-2 rounded-xl text-[10px] font-bold transition-all duration-200 flex items-center space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        <span>อัปโหลดรูป</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Canvas Area -->
                            <div x-show="method === 'draw'" x-transition class="animate-fade-in">
                                <div class="relative bg-slate-50 border-2 border-slate-100 rounded-3xl overflow-hidden">
                                    <canvas id="signature-pad" class="w-full h-64 touch-none"></canvas>
                                    <button type="button" id="clear-signature"
                                        class="absolute top-4 right-4 p-2.5 bg-white shadow-md border border-slate-100 rounded-xl text-slate-400 hover:text-red-500 transition active:scale-95">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" name="signature" id="signature-input">
                            </div>

                            <!-- Upload Area -->
                            <div x-show="method === 'upload'" x-transition class="animate-fade-in">
                                <div class="relative group">
                                    <input type="file" name="signature_file" id="signature_file" accept="image/*"
                                        class="hidden" @change="
                                                const file = $event.target.files[0];
                                                if (file) {
                                                    fileName = file.name;
                                                    const reader = new FileReader();
                                                    reader.onload = (e) => previewUrl = e.target.result;
                                                    reader.readAsDataURL(file);
                                                }
                                            ">
                                    <label for="signature_file"
                                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50 hover:bg-slate-100 hover:border-blue-400 transition-all cursor-pointer">
                                        <template x-if="!previewUrl">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <div
                                                    class="p-4 bg-white rounded-2xl shadow-sm mb-4 group-hover:scale-110 transition duration-300">
                                                    <svg class="w-8 h-8 text-blue-500" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                    </svg>
                                                </div>
                                                <p class="mb-2 text-sm text-slate-600"><span
                                                        class="font-bold text-blue-600">คลิกเพื่ออัปโหลด</span> หรือลากไฟล์
                                                </p>
                                            </div>
                                        </template>
                                        <template x-if="previewUrl">
                                            <div class="relative p-6 flex flex-col items-center">
                                                <button type="button"
                                                    @click.stop.prevent="previewUrl = null; fileName = ''; document.getElementById('signature_file').value = ''"
                                                    class="absolute -top-4 -right-4 p-2 bg-red-500 text-white rounded-full shadow-xl hover:bg-red-600 transition z-20 hover:scale-110">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-50">
                                                    <img :src="previewUrl"
                                                        class="max-h-40 w-auto object-contain rounded-lg">
                                                </div>
                                                <p class="text-xs text-blue-500 font-bold mt-4" x-text="fileName"></p>
                                            </div>
                                        </template>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" :disabled="loading"
                                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold transition-all duration-300 shadow-lg shadow-blue-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-3">
                                    <template x-if="!loading">
                                        <div class="flex items-center space-x-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                            </svg>
                                            <span>บันทึกลายมือชื่อ</span>
                                        </div>
                                    </template>
                                    <template x-if="loading">
                                        <div class="flex items-center space-x-3">
                                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            <span>กำลังบันทึก...</span>
                                        </div>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('turbo:load', function () {
            const canvas = document.getElementById('signature-pad');
            if (!canvas) return;

            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(0, 0, 0, 0)',
                penColor: 'rgb(30, 41, 59)'
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const width = canvas.offsetWidth;
                const height = canvas.offsetHeight;

                if (width > 0 && height > 0) {
                    canvas.width = width * ratio;
                    canvas.height = height * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                    signaturePad.clear();
                }
            }

            window.addEventListener('resize', resizeCanvas);

            // Initial resize
            setTimeout(resizeCanvas, 200);

            document.getElementById('clear-signature').addEventListener('click', function () {
                signaturePad.clear();
            });

            document.getElementById('signature-form').addEventListener('submit', function (e) {
                const alpineData = document.querySelector('[x-data]').__x.$data;
                if (alpineData.method === 'draw') {
                    if (!signaturePad.isEmpty()) {
                        document.getElementById('signature-input').value = signaturePad.toDataURL();
                    }
                }
            });
        });
    </script>
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }
    </style>
@endsection