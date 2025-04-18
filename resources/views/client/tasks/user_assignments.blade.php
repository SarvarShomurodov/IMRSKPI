@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>{{ $user->name }} uchun topshiriq baholari</h2>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Dan:</label>
            <input type="date" name="from_date" value="{{ $from }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label>Gacha:</label>
            <input type="date" name="to_date" value="{{ $to }}" class="form-control">
        </div>
        <div class="col-md-4 mt-4">
            <button type="submit" class="btn btn-primary mt-2">Filtrlash</button>
        </div>
    </form>

    @if(count($assignments) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sana</th>
                    <th>Subtask nomi</th>
                    <th>Baho</th>
                    <th>Izoh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->date }}</td>
                        <td>{{ $assignment->subtask->title ?? 'Nomaʼlum' }}</td>
                        <td>{{ $assignment->rating }}</td>
                        <td>{{ $assignment->comment }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Hech qanday baho topilmadi.</p>
    @endif
</div>
@endsection
