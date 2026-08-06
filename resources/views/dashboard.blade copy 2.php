@extends('layouts.app')

@section('title', 'แผงควบคุมระบบ')
@section('page_title', 'แผงควบคุมหลัก (Dashboard)')

@section('content')

    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
        <div class="mb-5">
            <h2 class="text-xl font-semibold text-slate-800" style="letter-spacing:-0.02em">ภาพรวมระบบจัดซื้อ</h2>
            <p class="text-sm text-slate-500 mt-0.5">สรุปภาพรวม งานค้างอยู่ที่ไหนมากสุด สถิติในปีเฉลี่ย</p>
        </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

        <!-- Total Requests -->
        <div class="rounded-lg border px-6 py-5" style="background:oklch(0.96 0.01 250); border-color:oklch(0.88 0.04 250)">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 flex-shrink-0" style="color:oklch(0.45 0.08 250)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                <p class="text-xs font-medium" style="color:oklch(0.45 0.08 250)">คำขอทั้งหมด</p>
            </div>
            <p class="text-3xl font-semibold tabular-nums" style="letter-spacing:-0.03em; color:oklch(0.30 0.12 250)">{{ $stats['total'] }}</p>
            <p class="text-xs mt-2" style="color:oklch(0.55 0.06 250)">รายการในระบบ</p>
        </div>

        <!-- Pending Approvals -->
        <div class="rounded-lg border px-6 py-5" style="background:oklch(0.97 0.03 75); border-color:oklch(0.88 0.09 75)">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 flex-shrink-0" style="color:oklch(0.48 0.12 65)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-medium" style="color:oklch(0.48 0.12 65)">อยู่ระหว่างดำเนินการ</p>
            </div>
            <p class="text-3xl font-semibold tabular-nums" style="letter-spacing:-0.03em; color:oklch(0.50 0.17 55)">{{ $stats['pending'] }}</p>
            @if($stats['pending'] > 0)
            <p class="text-xs mt-2 tabular-nums" style="color:oklch(0.55 0.12 60)">
                เฉลี่ย <span class="font-semibold">{{ $stats['avg_pending_days'] }}</span> วัน
                &middot; นานสุด <span class="font-semibold">{{ $stats['oldest_pending_days'] }}</span> วัน
            </p>
            @else
            <p class="text-xs mt-2" style="color:oklch(0.58 0.10 65)">รอการตรวจสอบ / จัดซื้อ</p>
            @endif
        </div>

        <!-- Completed -->
        <div class="rounded-lg border px-6 py-5" style="background:oklch(0.96 0.03 160); border-color:oklch(0.86 0.08 160)">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 flex-shrink-0" style="color:oklch(0.42 0.10 160)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-medium" style="color:oklch(0.42 0.10 160)">อนุมัติจัดซื้อแล้ว</p>
            </div>
            <p class="text-3xl font-semibold tabular-nums" style="letter-spacing:-0.03em; color:oklch(0.42 0.16 155)">{{ $stats['completed'] }}</p>
            <p class="text-xs mt-2" style="color:oklch(0.52 0.08 160)">รายการที่อนุมัติแล้ว</p>
        </div>

    </div>

    <!-- Bottleneck Analysis (Full Width) -->
    <div class="bg-white rounded-lg border border-slate-200 mb-5 flex flex-col">
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-700">วิเคราะห์ความล่าช้า</h3>
            <span class="text-xs text-slate-400">Bottleneck Analysis</span>
        </div>

        @php
            $maxStage = null;
            $maxValue = 0;
            foreach ($statusTracker as $stageLabel => $stageValue) {
                if ($stageValue > $maxValue) {
                    $maxValue = $stageValue;
                    $maxStage = $stageLabel;
                }
            }
            $stageCount = count($statusTracker);
        @endphp

        <div class="p-5">
            @if($maxValue == 0)
                <p class="text-sm text-slate-400 text-center py-8">ไม่มีรายการค้างอยู่ในขั้นตอนใด</p>
            @else
                @foreach($statusTracker as $stageLabel => $stageValue)
                    @php
                        $isBottleneck = ($stageLabel === $maxStage) && $stageValue > 0;
                        $barWidth = $maxValue > 0 ? round(($stageValue / $maxValue) * 100) : 0;
                        $isLast = $loop->last;
                    @endphp
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center flex-shrink-0">
                            <span class="w-2.5 h-2.5 rounded-full {{ $isBottleneck ? 'bg-amber-500' : 'bg-slate-300' }}"></span>
                            @unless($isLast)
                                <span class="w-px flex-1 bg-slate-200" style="min-height: 22px"></span>
                            @endunless
                        </div>
                        <div class="flex-1 flex items-center justify-between gap-3 {{ $isLast ? '' : 'pb-3' }}">
                            <span class="text-xs {{ $isBottleneck ? 'text-amber-700 font-semibold' : 'text-slate-600' }}">{{ $stageLabel }}</span>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="w-28 h-1.5 rounded overflow-hidden {{ $isBottleneck ? 'bg-amber-100' : 'bg-slate-100' }}">
                                    <div class="h-full rounded {{ $isBottleneck ? 'bg-amber-500' : 'bg-slate-300' }}" style="width: {{ $barWidth }}%"></div>
                                </div>
                                <span class="text-xs font-mono tabular-nums {{ $isBottleneck ? 'text-amber-700 font-semibold' : 'text-slate-500' }}" style="min-width: 14px; text-align:right">{{ $stageValue }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($maxStage)
                    <div class="mt-2 pt-3 border-t border-slate-100 flex items-center gap-2 text-xs text-amber-700">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>คอขวดปัจจุบัน: <span class="font-semibold">{{ $maxStage }}</span> มีรายการค้างอยู่ {{ $maxValue }} รายการ</span>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
        <!-- Status Overview -->
        <div class="bg-white rounded-lg border border-slate-200 flex flex-col">
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-700">ภาพรวมสถานะ</h3>
                <span class="text-xs text-slate-400">Status Overview</span>
            </div>
            <div class="relative flex-grow min-h-[220px] w-full flex items-center justify-center p-4">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Budget Utilization -->
        <div class="bg-white rounded-lg border border-slate-200 flex flex-col">
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-700">การใช้งบประมาณ</h3>
                <span class="text-xs text-slate-400">Budget Utilization</span>
            </div>
            <div class="relative flex-grow min-h-[220px] w-full flex flex-col items-center justify-center p-4">
                <canvas id="budgetChart"></canvas>
                <div class="absolute mt-6 text-center pointer-events-none">
                    <p class="text-[11px] text-slate-500 font-medium">ใช้ไปแล้ว</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $budget && ($budget->remaining_budget + $stats['budget_spent']) > 0 ? number_format(($stats['budget_spent'] / ($budget->remaining_budget + $stats['budget_spent'])) * 100, 1) : 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    @if($licenseAlertsCount > 0)
        <!-- License Expiry Alert -->
        <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-8 h-8 rounded-md bg-red-100 flex items-center justify-center text-red-600 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-red-900">ลิขสิทธิ์ซอฟต์แวร์ใกล้หมดอายุ</p>
                    <p class="text-xs text-red-700 mt-0.5">ตรวจพบ {{ $licenseAlertsCount }} รายการจะหมดอายุภายใน 30 วัน กรุณาตรวจสอบและดำเนินการทันที</p>
                </div>
            </div>
            <a href="{{ route('licenses.index') }}" class="flex-shrink-0 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md transition-colors duration-150 whitespace-nowrap">ตรวจสอบรายการ</a>
        </div>
    @endif

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stats = @json($stats);
            const budgetSpent = parseFloat(stats.budget_spent) || 0;
            const remainingBudget = {{ $budget ? $budget->remaining_budget : 0 }};

            // 1. Status Overview Chart
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['เสร็จสิ้น', 'กำลังดำเนินการ', 'ยกเลิก'],
                    datasets: [{
                        data: [stats.completed, stats.pending, stats.rejected],
                        backgroundColor: ['#10b981', '#3b82f6', '#f43f5e'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { family: 'Sarabun' }, boxWidth: 12 } }
                    },
                    cutout: '70%'
                }
            });

            // 2. Budget Utilization Chart (Half-Doughnut)
            const ctxBudget = document.getElementById('budgetChart').getContext('2d');
            new Chart(ctxBudget, {
                type: 'doughnut',
                data: {
                    labels: ['งบที่ใช้ไป', 'งบคงเหลือ'],
                    datasets: [{
                        data: [budgetSpent, remainingBudget],
                        backgroundColor: ['#3b82f6', '#e2e8f0'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    rotation: -90,
                    circumference: 180,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed !== null) {
                                        label += new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB' }).format(context.parsed);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection