@extends('layouts.admin')

@section('content')
    <h4 class="mb-4">Vazifalar ro'yxati</h4>
    <div class="row">
        <!-- Cardlar -->
        @foreach ($tasks as $task)
            <div class="col-md-4">
                <div class="task-card">
                    <div class="task-title"><a href="{{ url('tasks/' . $task->id) }}">{{ $task->taskName }}</a></div>
                    <div class="task-description">{{ $task->description }}</div>
                </div>
            </div>
        @endforeach
    </div>
@endsection