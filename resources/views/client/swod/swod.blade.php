@extends('layouts.admin')

@section('content')

<style>
    .sticky-col-left {
        min-width: 20px;
        white-space: nowrap;
    }

    .sticky-col {
        position: sticky;
        left: 0;
        background: white;
        z-index: 3;
        min-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<h4 class="mb-4">SWOD Tahliliy ma'lumotlar jadvali</h4>

<form method="GET" action="{{ route('task.swod') }}" class="row justify-content-end mb-4">
    <div class="col-md-2 mb-2">
        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
    </div>
    <div class="col-md-2 mb-2">
        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
    </div>
    <div class="col-md-2 mb-2">
        <select name="position" class="form-control">
            <option value="">Barcha pozitsiyalar</option>
            @foreach ($positions as $position)
                <option value="{{ $position }}" {{ request('position') == $position ? 'selected' : '' }}>
                    {{ $position }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-auto mb-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

@if(request('from_date') && request('to_date')) 
    @php
        $fromDate = \Carbon\Carbon::parse(request('from_date'));
        $toDate = \Carbon\Carbon::parse(request('to_date'));

        // Agar from_date 26-sanasidan keyingi oyning 25-sanasigacha bo'lsa
        if ($fromDate->day >= 26 && $toDate->day <= 25) {
            // from_date ni keyingi oyga o'zgartiramiz va oy nomini olamiz
            $monthName = ucfirst($fromDate->addMonth()->locale('en')->monthName); // Oyning nomini olish va bosh harfini katta qilish
            $message = "$monthName uchun KPI";
        } else {
            // Aks holda, sana oralig'ini ko'rsatish
            $message = "{$fromDate->format('d-m-Y')} dan {$toDate->format('d-m-Y')} gacha";
        }
    @endphp
    <h3 class="mb-4">{{ $message }}</h3>
@endif

@php
    $totalBonusAll = $assignments->sum('bonus');
    $totalWithBonusAll = $assignments->sum('total_with_bonus');
    $totalRating = $assignments->sum('total_rating');

    // Filter staffUsers based on the selected position
    if (request('position')) {
        $staffUsers = $staffUsers->where('position', request('position'));
    }
@endphp

<div class="table-container" style="max-height: 650px; overflow-y: auto;">
    <table class="table table-bordered" id="myTable2">
        <thead>
            <tr>
                <th>№</th>
                <th class="sticky-col">FISH</th>
                <th>Lavozim</th>
                <th>Loyiha nomi</th>
                @foreach ($tasks as $task)
                    <th>{{ $task->taskName }}</th>
                @endforeach
                <th>Institut bo'yicha o'rtacha ball</th>
                <th>Bonussiz umumiy baho ({{ $totalRating }})</th>
                <th>Bonus ({{ $totalBonusAll }})</th>
                <th>Jami ({{ $totalWithBonusAll }})</th>
                <th>KPI</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($staffUsers as $user)
                @php
                    $roles = $user->getRoleNames();
                    if ($roles->contains('Admin') || $roles->contains('Super Admin')) continue;

                    $data = $assignments[$user->id] ?? [
                        'total_rating' => 0,
                        'bonus' => 0,
                        'total_with_bonus' => 0,
                        'kpi' => 0,
                        'globalAvg' => 0,
                        'tasks' => collect()
                    ];

                    $taskRatings = [];

                    foreach ($data['tasks'] as $taskData) {
                        $taskRatings[$taskData['task_id']] = ($taskRatings[$taskData['task_id']] ?? 0) + $taskData['rating'];
                    }
                @endphp
                <tr>
                    <td class="sticky-col-left">{{ $i++ }}</td>
                    <td class="sticky-col">
                        <a href="{{ route('client-task.show', $user->id) }}" style="text-decoration: none">
                            {{ $user->firstName }} {{ $user->lastName }}
                        </a>
                    </td>
                    <td>{{ $user->position }}</td>
                    <td>{{ $user->project->name ?? '' }}</td>
                    @foreach ($tasks as $task)
                        <td>
                            <a href="{{ route('client-task.task-details', [
                                'user' => $user->id,
                                'task' => $task->id,
                                'from_date' => request('from_date'),
                                'to_date' => request('to_date')
                            ]) }}" style="text-decoration: none">
                                {{ $taskRatings[$task->id] ?? 0 }}
                            </a>
                        </td>
                    @endforeach
                    <td>{{ number_format($data['globalAvg'], 2) }}</td>
                    <td>{{ $data['total_rating'] }}</td>
                    <td>{{ $data['bonus'] }}</td>
                    <td>{{ $data['total_with_bonus'] }}</td>
                    <td>{{ $data['kpi'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
