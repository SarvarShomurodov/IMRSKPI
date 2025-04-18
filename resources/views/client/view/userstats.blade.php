@extends('layouts.client')

@section('content')

    <button id="toggleChart" class="btn btn-primary mt-3 mb-2"><i class="fas fa-link"></i> Siz bilan bir xil positiondagi xodimlar</button>
    <h3 id="chartTitle" class="text-white mt-2 mb-4">Barcha xodimlar (KPI grafik)</h3>
    <canvas id="allUsersChart" width="100" height="150"></canvas>
    <canvas id="samePositionChart" width="100" height="150" style="display: none;"></canvas>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $allUsers = $staffUsers->map(fn($user) => [
        'id' => $user->id,
        'firstName' => $user->firstName,
        'lastName' => $user->lastName,
        'roles' => $user->getRoleNames(),
    ]);

    $samePositionUsers = $samePositionUsers->map(fn($user) => [
        'id' => $user->id,
        'firstName' => $user->firstName,
        'lastName' => $user->lastName,
    ]);

    $currentUserPosition = auth()->user()->position;
    $currentUserId = auth()->id();
@endphp

<script>
    const tasks = @json($tasks);
    const taskIds = tasks.map(t => t.id);
    const taskNames = tasks.map(t => t.taskName);
    const taskColors = [
        '#FF6633', '#FF33FF', '#00B3E6', '#E6B333', '#3366E6',
        '#B34D4D', '#809900', '#FF1A66', '#66E64D', '#4DB3FF',
        '#1AB399', '#E666FF', '#6680B3', '#FF4D4D', '#99E6E6',
        '#6666FF', '#FFB399', '#00FF99', '#FF9966', '#CCFF1A'
    ];

    const allUsers = @json($allUsers).filter(user => user.id !== 1 && user.id !== 2);
    const sameUsers = @json($samePositionUsers).filter(user => user.id !== 1 && user.id !== 2);
    const assignments = @json($assignments);
    const currentUserId = @json($currentUserId);

    function buildChartData(users) {
        const userScores = users.map(user => {
            let total = 0;
            const userAssign = assignments[user.id];
            if (userAssign && userAssign.tasks) {
                userAssign.tasks.forEach(task => {
                    total += task.rating;
                });
            }
            return { ...user, total };
        });

        userScores.sort((a, b) => b.total - a.total);

        const labels = userScores.map(user => `${user.lastName} ${user.firstName}`);
        const dataMatrix = userScores.map(user => {
            const data = Array(taskIds.length).fill(0);
            const userAssign = assignments[user.id];
            if (userAssign && userAssign.tasks) {
                userAssign.tasks.forEach(task => {
                    const index = taskIds.indexOf(task.task_id);
                    if (index !== -1) data[index] += task.rating;
                });
            }
            return data;
        });

        const datasets = taskIds.map((taskId, i) => ({
            label: taskNames[i],
            data: dataMatrix.map(row => row[i]),
            backgroundColor: taskColors[i % taskColors.length],
            borderWidth: 1
        }));

        return { labels, datasets, sortedUsers: userScores };
    }

    function tickColorCallback(users, currentUserId) {
        return function(context) {
            const index = context.index;
            const userId = users[index]?.id;
            return userId === currentUserId ? 'red' : 'white';
        };
    }

    const allChart = buildChartData(allUsers);
    const sameChart = buildChartData(sameUsers);

    const allUsersChart = new Chart(document.getElementById('allUsersChart'), {
        type: 'bar',
        data: allChart,
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                x: {
                    stacked: true,
                    beginAtZero: true,
                    title: { display: true, text: 'Ballar', color: 'white' },
                    ticks: { precision: 0, color: 'white' }
                },
                y: {
                    stacked: true,
                    ticks: {
                        precision: 0,
                        color: tickColorCallback(allChart.sortedUsers, currentUserId),
                        font: {
                            weight: 'bold', // Bu yerda bold qilib berilyapti
                            size: 14
                        }
                    }
                }
            }
        }
    });

    const samePositionChart = new Chart(document.getElementById('samePositionChart'), {
        type: 'bar',
        data: sameChart,
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                x: {
                    stacked: true,
                    beginAtZero: true,
                    title: { display: true, text: 'Ballar', color: 'white' },
                    ticks: { precision: 0, color: 'white' }
                },
                y: {
                    stacked: true,
                    ticks: {
                        precision: 0,
                        color: tickColorCallback(sameChart.sortedUsers, currentUserId),
                        font: {
                            weight: 'bold', // Bu yerda bold qilib berilyapti
                            size: 14
                        }
                    }
                }
            }
        }
    });

    let isSamePositionView = false;
    let positionName = "{{ $currentUserPosition }}";

    document.getElementById('toggleChart').addEventListener('click', function () {
        const allUsersChartCanvas = document.getElementById('allUsersChart');
        const samePositionChartCanvas = document.getElementById('samePositionChart');
        const toggleButton = document.getElementById('toggleChart');
        const chartTitle = document.getElementById('chartTitle');

        if (isSamePositionView) {
            allUsersChartCanvas.style.display = 'block';
            samePositionChartCanvas.style.display = 'none';
            toggleButton.innerHTML = '<i class="fas fa-link"></i> Siz bilan bir xil positiondagi xodimlar';
            chartTitle.textContent = 'Barcha xodimlar (KPI grafik)';
        } else {
            allUsersChartCanvas.style.display = 'none';
            samePositionChartCanvas.style.display = 'block';
            toggleButton.innerHTML = '<i class="fas fa-users"></i> Barcha xodimlar';
            chartTitle.textContent = `Siz bilan bir xil positiondagi xodimlar (${positionName})`;
        }

        isSamePositionView = !isSamePositionView;
    });
</script>
@endsection
