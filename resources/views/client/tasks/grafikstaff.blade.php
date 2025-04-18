@extends('layouts.admin')

@section('content')
<div class=" mt-5">
    <h3 class="mb-4">{{ $user->firstName }} {{ $user->lastName }}-({{ $user->position }})</h3>

    <canvas id="kpiChart" height="170"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const kpiData = @json($kpiResults);

    const allTasksSet = new Set();
    kpiData.forEach(month => {
        Object.keys(month.task_ratings).forEach(taskName => {
            allTasksSet.add(taskName);
        });
    });
    const allTasks = Array.from(allTasksSet);

    const taskColors = [
        'rgba(255, 0, 0, 0.8)',       // Yorqin qizil
        'rgba(0, 128, 255, 0.8)',     // Yorqin ko‘k
        'rgba(255, 215, 0, 0.8)',     // Oltin sariq
        'rgba(0, 255, 128, 0.8)',     // Neon yashil
        'rgba(128, 0, 255, 0.8)',     // Yorqin binafsha
        'rgba(255, 102, 0, 0.8)',     // Yorqin to'q sariq
        'rgba(255, 20, 147, 0.8)',    // Deep pink
        'rgba(0, 255, 255, 0.8)'      // Cyan
    ];


    const datasets = allTasks.map((taskName, idx) => {
        return {
            label: taskName,
            data: kpiData.map(month => month.task_ratings[taskName] || 0),
            backgroundColor: taskColors[idx % taskColors.length],
            stack: 'combined'
        };
    });

    // Bonus dataset qo‘shamiz
    datasets.push({
        label: 'Bonus',
        data: kpiData.map(x => x.bonus),
        backgroundColor: 'rgba(75, 192, 192, 0.7)',
        stack: 'combined'
    });

    const ctx = document.getElementById('kpiChart').getContext('2d');
    const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: kpiData.map(x => x.month),
        datasets: datasets
    },
    options: {
        indexAxis: 'y', // <-- bu qatorni qo‘shish kifoya
        responsive: true,
        scales: {
            x: {
                stacked: true,
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Ballar'
                }
            },
            y: {
                stacked: true
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.raw;
                        const index = context.dataIndex;
                        const item = kpiData[index];
                        const totalWithBonus = item.total_with_bonus;

                        let contribution = 0;
                        if (totalWithBonus > 0 && item.kpi > 0) {
                            contribution = ((value / totalWithBonus) * item.kpi).toFixed(2);
                        }

                        return `${context.dataset.label}: ${value} (${contribution}% of KPI)`;
                    },
                    footer: function(context) {
                        const index = context[0].dataIndex;
                        const item = kpiData[index];
                        return 'KPI: ' + item.kpi + '%';
                    }
                }
            }
        }
    }
});

</script>
@endsection
