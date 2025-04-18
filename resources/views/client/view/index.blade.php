@extends('layouts.client')

@section('content')

<div class="row g-3 mt-3">
    <!-- Example Card -->
    @if($currentUserAssignment && isset($currentUserAssignment['tasks']))
        @foreach($currentUserAssignment['tasks'] as $task)
            <div class="col-md-3">
                <div class="card text-white h-100 shadow">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-chart-bar"></i> {{ $task['task_name'] }}</h6>
                        <p class="mb-0 ">BALL</p>
                        <h4>{{ number_format($task['total_rating'], 2, '.', '')}}</h4>
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
                    <h4>{{ number_format($currentUserAssignment['total_rating'], 2, '.', '')  }}</h4>
                </div>
            </div>
        </div>
    @else
        <p>Umumiy ball mavjud emas.</p>
    @endif
</div>

@endsection
