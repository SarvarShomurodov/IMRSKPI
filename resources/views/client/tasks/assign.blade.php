<!-- resources/views/client/tasks/assign.blade.php -->

@extends('layouts.admin')

@section('content')
    <h4 class="mb-5"><b>{{ $task->taskName }}</b> - {{ $staffUser->firstName }} {{ $staffUser->lastName }}</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered" id="myTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Vazifalar (Ball)</th>
                <th>Baho</th>
                <th>Sana</th>
                <th>Izoh</th>
                {{-- <th>Amal</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($subtasks as $index => $subtask)
            <tr>
                <form method="POST" action="{{ route('tasks.storeRating', [$task->id, $staffUser->id]) }}">
                    @csrf
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <h6><strong>{{ $subtask->title }}</strong> ({{ $subtask->min }} - {{ $subtask->max }})</h6>
                        <input type="hidden" name="subtask_id" value="{{ $subtask->id }}">
                    </td>
                    <td>
                        <input type="number" name="rate" class="form-control" placeholder="Ball" 
                               min="{{ $subtask->min }}" max="{{ $subtask->max }}" step="0.01" required>
                    </td>
                    <td>
                        <input type="date" name="date" class="form-control" required>
                    </td>
                    <td>
                        <textarea name="comment" class="form-control" rows="2"></textarea>
                        <button type="submit" class="btn btn-success mt-1">Saqlash</button>
                    </td>
                </form>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
