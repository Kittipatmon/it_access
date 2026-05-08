@extends('layouts.admin')

@section('breadcrumb', 'จัดการข้อมูลพนักงาน')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">User Management (Employees)</h2>
                <p class="text-sm text-slate-400">จัดการข้อมูลบัญชีผู้ใช้ (พนักงาน)</p>
            </div>
            <div class="flex space-x-3">
                <button
                    class="flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Import Excel
                </button>
            </div>
        </div>

        <!-- Filters & Search -->
        <form action="{{ route('backend.users.index') }}" method="GET"
            class="bg-white p-4 rounded-xl border border-slate-200 flex flex-wrap gap-4 items-center">
            <div class="relative flex-1 min-w-[200px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="ค้นหาชื่อ, รหัสพนักงาน, อีเมล..."
                    class="pl-10 w-full bg-slate-50 border-none rounded-lg text-sm py-2 focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm">
                ค้นหา
            </button>
            @if($search)
                <a href="{{ route('backend.users.index') }}"
                    class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                    ล้างตัวกรอง
                </a>
            @endif
        </form>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead
                        class="bg-slate-50 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-4 font-bold">Name</th>
                            <th class="px-8 py-4 font-bold">Employee ID</th>
                            <th class="px-8 py-4 font-bold">Department</th>
                            <th class="px-8 py-4 font-bold text-center">Signature</th>
                            <th class="px-8 py-4 font-bold">Status</th>
                            <th class="px-8 py-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-8 py-5 text-sm font-medium text-slate-700">{{ $user->firstname }}
                                    {{ $user->lastname }}</td>
                                <td class="px-8 py-5 text-sm text-slate-500">{{ $user->emp_code }}</td>
                                <td class="px-8 py-5 text-sm text-slate-500 font-bold">{{ $user->department_rel->name ?? '-' }}
                                </td>
                                <td class="px-8 py-5 text-sm text-center">
                                    @if($user->signature)
                                        <div class="flex items-center justify-center">
                                            <div class="w-10 h-6 bg-slate-50 border border-slate-200 rounded flex items-center justify-center overflow-hidden">
                                                <img src="{{ asset('storage/signatures/' . $user->signature) }}" alt="Sig" class="max-h-full w-auto">
                                            </div>
                                            <span class="ml-2 text-green-500 font-bold text-[10px]">มีแล้ว</span>
                                        </div>
                                    @else
                                        <span class="text-slate-300 italic text-[10px]">ยังไม่มี</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-sm">
                                    @if($user->status == 'active')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-600 border border-green-100 uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> ปกติ
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> ระงับใช้งาน
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('backend.users.signature', $user->id) }}" 
                                           class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="จัดการลายเซ็น">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <button class="p-1.5 text-slate-400 hover:bg-slate-50 rounded-lg transition" title="แก้ไขผู้ใช้">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-slate-400 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-slate-300"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    ไม่พบข้อมูลพนักงาน
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-8 py-4 bg-slate-50 flex justify-between items-center border-t border-slate-100">
                <span class="text-xs text-slate-400">
                    แสดง {{ $users->firstItem() ?? 0 }} ถึง {{ $users->lastItem() ?? 0 }} จากทั้งหมด {{ $users->total() }}
                    รายการ
                </span>
                <div>
                    {{ $users->appends(['search' => $search])->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>
@endsection