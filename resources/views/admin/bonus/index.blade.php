@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Bonuslar ro'yxati</h2>
        <a class="btn btn-success" href="{{ route('admin.bonuses.create') }}">+ Bonus qo‘shish</a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nomi</th>
            <th>Bonus</th>
            <th>User Name</th>
            <th>Date</th>
            <th>Amallar</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bonuses as $bonus)
            <tr>
                <td>{{ $bonus->id }}</td>
                <td>{{ $bonus->name }}</td>
                <td>{{ $bonus->bonus }}</td>
                <td>{{ $bonus->user->lastName }} {{ $bonus->user->firstName }}</td>
                <td>{{ $bonus->given_date }}</td>
                <td>
                    <a href="{{ route('admin.bonuses.edit', $bonus->id) }}" class="btn btn-warning btn-sm">Tahrirlash</a>
                    <form action="{{ route('admin.bonuses.destroy', $bonus->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ishonchingiz komilmi?')">O‘chirish</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
