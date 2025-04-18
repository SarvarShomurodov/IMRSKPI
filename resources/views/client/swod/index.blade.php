@extends('layouts.admin')

@section('content')
<form method="GET" action="{{ route('grafik') }}" class="row  justify-content-start mb-5">
    
    <div class="col-12 col-md-2 mb-2"> <!-- mb-2 o'zgartirildi -->
        <select name="position" class="form-select text-black">
            <option value="all" {{ request('position') == 'all' ? 'selected' : '' }}>Hammasi</option>
            @foreach ($positions as $pos)
                <option value="{{ $pos }}" {{ request('position') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12 col-md-2 mb-2"> <!-- mb-2 o'zgartirildi -->
        <input type="date" name="from_date" value="{{ request('from_date', $oneMonthAgo) }}" class="form-control">
    </div>

    <div class="col-12 col-md-2 mb-2"> <!-- mb-2 o'zgartirildi -->
        <input type="date" name="to_date" value="{{ request('to_date', $today) }}" class="form-control">
    </div>

    <div class="col-12 col-md-auto mb-2"> <!-- mb-2 o'zgartirildi -->
        <button type="submit" class="btn btn-primary">
            Filter
        </button>
    </div>
</form>




<h4 class="text-center mt-5 mb-4">
    Xodimlarning {{ $from ? \Carbon\Carbon::parse($from)->format('d-m-Y') : \Carbon\Carbon::parse($oneMonthAgo)->format('d-m-Y') }}  dan {{ $to ? \Carbon\Carbon::parse($to)->format('d-m-Y') : \Carbon\Carbon::parse($today)->format('d-m-Y') }}  gacha ishlagan KPI natijalari
</h4>

    <!-- Filter Form -->
    

    <!-- Chart -->
    <canvas id="kpiChart" width="100" height="150"></canvas>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
$staffUsersForChart = $staffUsers->map(function ($user) {
    return [
        'id' => $user->id,
        'firstName' => $user->firstName,
        'lastName' => $user->lastName,
        'roles' => $user->getRoleNames(),
    ];
});
@endphp

<script>
    const rawUsers = @json($staffUsersForChart);
    const assignments = @json($assignments);
    const tasks = @json($tasks);

    const taskNames = tasks.map(t => t.taskName);
    const taskIds = tasks.map(t => t.id);
    const taskColors = ['#023047', '#219ebc', '#8ecae6', '#ffb703', '#fb8500', '#8338ec', '#3a86ff', '#ff006e', '#06d6a0', '#ef476f', '#f72585', '#4cc9f0', '#ffbe0b', '#b5179e'];

    const filteredUsers = rawUsers.filter(user => {
        const roles = user.roles ?? [];
        return !roles.includes('Super Admin') && !roles.includes('Admin');
    });

    const labels = filteredUsers.map(user => `${user.lastName} ${user.firstName}`);
    const staffData = filteredUsers.map(user => {
        const data = Array(taskIds.length).fill(0);
        const userAssign = assignments[user.id];

        if (userAssign && userAssign.tasks) {
            userAssign.tasks.forEach(task => {
                const taskIndex = taskIds.indexOf(task.task_id);
                if (taskIndex !== -1) {
                    data[taskIndex] += task.rating;
                }
            });
        }
        return data;
    });

    const datasets = taskIds.map((taskId, i) => {
        return {
            label: taskNames[i],
            data: staffData.map(row => row[i]),
            backgroundColor: taskColors[i % taskColors.length],
            borderWidth: 1
        };
    });

    new Chart(document.getElementById('kpiChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            indexAxis: 'y',
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
                    stacked: true,
                    ticks: {
                        autoSkip: false,
                        font: {
                            weight: 'bold', // Bu yerda bold qilib berilyapti
                            size: 14
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
