@extends('layouts.app')

@section('title', 'Executive Analytics')
@section('page-title', 'Executive HR Intelligence & Analytics')
@section('page-subtitle', 'Visualisasi data strategis: tren absensi, alokasi anggaran payroll, dan utilisasi cuti lintas divisi.')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Anggaran Gaji Pokok</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 font-mono">Rp {{ number_format($totalPayrollBudget, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Alokasi anggaran gaji bulanan seluruh divisi</p>
        </div>

        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Total Tenaga Kerja Aktif</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-emerald-600">{{ $totalEmployees }} <span class="text-xs text-slate-400 font-normal">Karyawan</span></h3>
            <p class="text-[11px] text-slate-400 mt-1">Tersebar di {{ $totalDepartments }} unit departemen</p>
        </div>

        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Total Cuti Terpakai</span>
                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-indigo-600">{{ $totalMonthlyLeaves }} <span class="text-xs text-slate-400 font-normal">Hari Kerja</span></h3>
            <p class="text-[11px] text-slate-400 mt-1">Akumulasi cuti yang disetujui HRD</p>
        </div>
    </div>

    <!-- Charts Row 1: Line Chart & Donut Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Line Chart: Attendance Trends (8 Cols) -->
        <div class="lg:col-span-8 saas-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Tren Absensi & Ketepatan Waktu (6 Bulan Terakhir)</h4>
                    <p class="text-xs text-slate-500">Perbandingan jumlah kehadiran tepat waktu vs keterlambatan</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1.5 text-emerald-700 font-bold">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Tepat Waktu
                    </span>
                    <span class="flex items-center gap-1.5 text-amber-700 font-bold">
                        <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span> Terlambat
                    </span>
                </div>
            </div>

            <div class="relative h-72 w-full">
                <canvas id="attendanceLineChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart: Payroll Budget Distribution (4 Cols) -->
        <div class="lg:col-span-4 saas-card p-6 flex flex-col justify-between">
            <div class="mb-4 pb-3 border-b border-slate-100">
                <h4 class="text-sm font-bold text-slate-900">Distribusi Anggaran Payroll</h4>
                <p class="text-xs text-slate-500">Persentase pengeluaran gaji per divisi</p>
            </div>

            <div class="relative h-64 w-full flex items-center justify-center">
                <canvas id="payrollDonutChart"></canvas>
            </div>

            <p class="text-[11px] text-slate-400 text-center pt-2">
                * Dihitung berdasarkan total gaji pokok karyawan aktif per divisi.
            </p>
        </div>
    </div>

    <!-- Charts Row 2: Bar Chart Headcount & Leave Usage -->
    <div class="saas-card p-6">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Distribusi Karyawan & Hari Cuti per Departemen</h4>
                <p class="text-xs text-slate-500">Perbandingan jumlah staf dan total hari cuti yang telah disetujui</p>
            </div>
        </div>

        <div class="relative h-72 w-full">
            <canvas id="departmentBarChart"></canvas>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Line Chart Attendance Trends
        const ctxLine = document.getElementById('attendanceLineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [
                    {
                        label: 'Tepat Waktu',
                        data: {!! json_encode($presentCounts) !!},
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#059669',
                        pointRadius: 4
                    },
                    {
                        label: 'Terlambat',
                        data: {!! json_encode($lateCounts) !!},
                        borderColor: '#d97706',
                        backgroundColor: 'rgba(217, 119, 6, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#d97706',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                    }
                }
            }
        });

        // 2. Donut Chart Payroll Budget
        const ctxDonut = document.getElementById('payrollDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($deptLabels) !!},
                datasets: [{
                    data: {!! json_encode($deptPayrollSums) !!},
                    backgroundColor: [
                        '#2563eb', // IT (Blue)
                        '#059669', // HR (Emerald)
                        '#7c3aed', // Finance (Violet)
                        '#d97706', // Operations (Amber)
                        '#e11d48'  // Marketing (Rose)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Plus Jakarta Sans', size: 10 },
                            boxWidth: 12,
                            padding: 12
                        }
                    }
                },
                cutout: '65%'
            }
        });

        // 3. Bar Chart Department Comparison
        const ctxBar = document.getElementById('departmentBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($deptLabels) !!},
                datasets: [
                    {
                        label: 'Jumlah Karyawan',
                        data: {!! json_encode($deptHeadcounts) !!},
                        backgroundColor: '#3b82f6',
                        borderRadius: 6
                    },
                    {
                        label: 'Total Cuti Disetujui (Hari)',
                        data: {!! json_encode($deptLeaveCounts) !!},
                        backgroundColor: '#818cf8',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
