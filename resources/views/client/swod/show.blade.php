@extends('layouts.admin')

@section('content')
    <h4 class="mb-4">Foydalanuvchi: {{ $user->firstName }} {{ $user->lastName }}</h4>

    @if ($assignments->isEmpty())
        <div class="alert alert-warning">Bu foydalanuvchiga topshiriqlar biriktirilmagan.</div>
    @else
        <table class="table table-bordered" id="myTable2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subtask nomi</th>
                    <th>Vazifalar</th>
                    <th>Ball (Rating)</th>
                    <th>Izoh</th>
                    <th>Sana</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assignments as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->subtask->title }} ({{ $item->subtask->min }} - {{ $item->subtask->max }})</td>
                        <td>{{ $item->subtask->task->taskName }}</td>
                        <td>{{ $item->rating }}</td>
                        <td>{{ $item->comment }}</td>
                        <td>{{ $item->addDate }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Ortga</a>
@endsection
