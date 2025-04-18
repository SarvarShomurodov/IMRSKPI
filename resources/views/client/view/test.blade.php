@extends('layouts.client')

@section('content')

@php
    $tasks = $currentUserAssignment['tasks'] ?? [];

    // "Жарима" ni oxiriga suramiz
    $sortedTasks = collect($tasks)->sortBy(function ($task) {
        return $task['task_name'] === 'Жарима' ? 1 : 0;
    });
@endphp

<div class="row g-3 mt-3">
    @if($currentUserAssignment && isset($currentUserAssignment['tasks']))
        @foreach($sortedTasks as $task)
            <div class="col-md-3">
                <div class="card text-white h-100 shadow">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-chart-bar"></i> {{ $task['task_name'] }}</h6>
                        <p class="mb-0">BALL</p>
                        <!-- total_rating 0 bo'lsa ham ko'rsatish uchun -->
                        <h4>{{ number_format($task['total_rating'], 2, '.', '') ?: '0.00' }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>Hozirgi foydalanuvchi uchun topshiriqlar mavjud emas.</p>
    @endif

    <!-- Umumiy -->
    @if($currentUserAssignment && isset($currentUserAssignment['total_rating']))
        <div class="col-md-3">
            <div class="card text-white h-100 shadow border border-info">
                <div class="card-body">
                    <h6 class="card-title text-info"><i class="fas fa-award"></i> UMUMIY</h6>
                    <p class="mb-0">BALL</p>
                    <!-- total_rating 0 bo'lsa ham ko'rsatish uchun -->
                    <h4>{{ number_format($currentUserAssignment['total_rating'], 2, '.', '') ?: '0.00' }}</h4>
                </div>
            </div>
        </div>
    @else
        <p>Umumiy ball mavjud emas.</p>
    @endif
</div>

<hr class="my-4">

<div class="switch-wrapper">
    <label class="switch">
        <input type="checkbox" id="tableToggle" onchange="toggleKpiChart()">
        <span class="slider"></span>
    </label>
    <span class="switch-label"><b style="color: red">{{ auth()->user()->firstName }} {{ auth()->user()->lastName }}</b> oylik KPI natijalarini ko'rish ({{ now()->year }})</span>  
</div>

<!-- KPI Grafik (Initially Hidden) -->
@if(!empty($monthlyKpis))
    <div class="row mt-5" id="kpiChartContainer" style="display: none;">
        <div class="col-md-12">
            <canvas id="kpiChart" height="100"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const kpiCtx = document.getElementById('kpiChart').getContext('2d');

        const monthlyKpi = @json($monthlyKpis);

        const labels = monthlyKpi.map(item => item.month);
        const data = monthlyKpi.map(item => item.kpi);

        new Chart(kpiCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'KPI (%)',
                    data: data,
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true
                    },
                    legend: {
                        display: true,
                        labels: {
                            color: 'white'  // Legend (belgilar) rangini oq qilish
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 120,
                        title: {
                            display: true,
                            color: 'white'
                        },
                        ticks: {
                            color: 'white'  // Y o'qi raqamlari rangini oq qilish
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Oylar',
                            color: 'white'
                        },
                        ticks: {
                            color: 'white'  // X o'qi raqamlari rangini oq qilish
                        }
                    }
                }
            }
        });

        function toggleKpiChart() {
            const chartContainer = document.getElementById('kpiChartContainer');
            const checkbox = document.getElementById('tableToggle');
            
            if (checkbox.checked) {
                chartContainer.style.display = 'block';
            } else {
                chartContainer.style.display = 'none';
            }
        }
    </script>
@endif

@endsection
