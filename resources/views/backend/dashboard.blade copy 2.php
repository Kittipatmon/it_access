@extends('layouts.admin')

@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">IT Access Overview</h2>
            <p class="text-sm text-slate-400">สรุปสถานะการร้องขอสิทธิในระบบ (ข้อมูลเดือน {{ $months[$selectedMonth] ?? 'ทั้งหมด' }} {{ $selectedYear }})</p>
        </div>

        <form action="{{ route('backend.dashboard') }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
            <select name="month" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0 cursor-pointer">
                <option value="all" {{ $selectedMonth == 'all' ? 'selected' : '' }}>ทุกเดือน</option>
                @foreach($months as $val => $name)
                    <option value="{{ $val }}" {{ $selectedMonth == $val ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <div class="w-px h-4 bg-slate-200"></div>
            <select name="year" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0 cursor-pointer">
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            <button type="submit" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                <i class="fa-solid fa-rotate"></i>
            </button>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pending</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['pending'] }}</h3>
                </div>
                <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <p class="text-[9px] text-amber-600 font-bold mt-3">รอพิจารณา</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">IT Action</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['it_pending'] }}</h3>
                </div>
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-gears text-lg"></i>
                </div>
            </div>
            <p class="text-[9px] text-blue-600 font-bold mt-3">รอ IT ดำเนินการ</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Completed</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['completed'] }}</h3>
                </div>
                <div class="w-10 h-10 bg-green-50 text-green-500 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-check-double text-lg"></i>
                </div>
            </div>
            <p class="text-[9px] text-green-600 font-bold mt-3">เสร็จสมบูรณ์</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rejected</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['rejected'] }}</h3>
                </div>
                <div class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-ban text-lg"></i>
                </div>
            </div>
            <p class="text-[9px] text-red-600 font-bold mt-3">ปฏิเสธแล้ว</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Staff</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ \App\Models\User::count() }}</h3>
                </div>
                <div class="w-10 h-10 bg-slate-50 text-slate-500 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
            <p class="text-[9px] text-slate-600 font-bold mt-3">พนักงานทั้งหมด</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Status Distribution Chart -->
        <div class="lg:col-span-1 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="mb-4">
                <h3 class="font-bold text-slate-800">Status Distribution</h3>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest">สัดส่วนสถานะคำร้อง</p>
            </div>
            <div class="h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Monthly Trends Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="mb-4">
                <h3 class="font-bold text-slate-800">Request Trends</h3>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest">แนวโน้มคำร้องรายเดือน</p>
            </div>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Department Load Chart -->
        <div class="lg:col-span-3 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="mb-4">
                <h3 class="font-bold text-slate-800">Top Departments</h3>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest">แผนกที่มีคำร้องสูงสุด 5 อันดับ</p>
            </div>
            <div class="h-64">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-list-ul text-blue-600"></i>
                Recent Requests
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-4 font-bold">Request No</th>
                        <th class="px-8 py-4 font-bold">Requester</th>
                        <th class="px-8 py-4 font-bold">Status</th>
                        <th class="px-8 py-4 font-bold">Current Step</th>
                        <th class="px-8 py-4 font-bold text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($allRequests->take(10) as $request)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-5 text-sm font-bold text-blue-600">{{ $request->request_no }}</td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-medium text-slate-700">{{ $request->firstname }} {{ $request->lastname }}</div>
                            <div class="text-[10px] text-slate-400 uppercase font-bold">{{ $request->department_name }}</div>
                        </td>
                        <td class="px-8 py-5">
                            @if($request->status === 'completed')
                                <span class="px-2 py-0.5 rounded-full bg-green-600 text-white text-[10px] font-bold uppercase shadow-sm">เสร็จสมบูรณ์</span>
                            @elseif($request->status === 'approved' && $request->it_status == 'completed')
                                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-bold uppercase border border-blue-200">รอผู้ใช้งานยืนยัน</span>
                            @elseif($request->status === 'approved')
                                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-bold uppercase">อนุมัติแล้ว</span>
                            @elseif($request->status === 'rejected')
                                <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[10px] font-bold uppercase">ปฏิเสธ</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-600 text-[10px] font-bold uppercase">รอดำเนินการ</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-500">
                            @if($request->status === 'completed')
                                <span class="inline-flex items-center gap-1 text-green-600 font-bold text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    เสร็จสมบูรณ์
                                </span>
                            @elseif($request->status === 'approved' && $request->it_status == 'completed')
                                <span class="inline-flex items-center gap-1 text-orange-500 font-bold text-xs animate-pulse">
                                    รอผู้ใช้งานยืนยัน
                                </span>
                            @elseif($request->status === 'approved')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-bold text-[11px] animate-pulse border border-blue-200 shadow-sm">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                    เจ้าหน้าที่ IT ดำเนินการ
                                </span>
                            @elseif($request->status === 'rejected')
                                <span class="text-red-400 text-xs font-medium">ถูกยกเลิก</span>
                            @else
                                {{ $request->steps->where('status', 'pending')->first()->step_name ?? '-' }}
                            @endif
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-400 text-right">{{ $request->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
document.addEventListener('turbo:load', function() {
    // 1. Status Chart
    try {
        const statusEl = document.getElementById('statusChart');
        if (statusEl) {
            new Chart(statusEl, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'IT Action', 'Completed', 'Rejected'],
                    datasets: [{
                        data: {!! json_encode($chartData['status']) !!},
                        backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, font: { size: 10 } } }
                    },
                    cutout: '70%'
                }
            });
        }
    } catch (e) { console.error('Status Chart Error:', e); }

    // 2. Trend Chart
    try {
        const trendEl = document.getElementById('trendChart');
        if (trendEl) {
            new Chart(trendEl, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['monthly']->keys()) !!},
                    datasets: [{
                        label: 'Requests',
                        data: {!! json_encode($chartData['monthly']->values()) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.05)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false }, border: { display: false } },
                        x: { grid: { display: false }, border: { display: false } }
                    }
                }
            });
        }
    } catch (e) { console.error('Trend Chart Error:', e); }

    // 3. Department Chart
    try {
        const deptEl = document.getElementById('deptChart');
        if (deptEl) {
            new Chart(deptEl, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['depts']->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($chartData['depts']->values()) !!},
                        backgroundColor: '#94a3b8',
                        borderRadius: 8,
                        barThickness: 20
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, border: { display: false } },
                        y: { grid: { display: false }, border: { display: false } }
                    }
                }
            });
        }
    } catch (e) { console.error('Dept Chart Error:', e); }
});
</script>
@endsection
