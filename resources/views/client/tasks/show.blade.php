@extends('layouts.admin')

@section('content')
    <h4 class="mb-5"><b>{{ $task->taskName }}</b>-baholash uchun xodimlar ro'yxati</h4>
    <table class="table table-bordered mt-5" id="myTable2">
        <thead>
            <tr>
                <th>#</th>
                <th>Ismi</th>
                <th>Berilgan loyiha</th>
                <th>Baxolash</th>
                <th>Lavozim</th>
                <th>Telfon raqam</th>
                <th>Umumiy baxosi</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($staffUsers as $index => $staffUser)
                @php
                    $roles = $staffUser->getRoleNames();
                @endphp

                @if ($roles->contains('Super Admin') || $roles->contains('Admin'))
                    @continue
                @endif
                <tr>
                    <th>{{ $i++ }}</th>
                    <td>{{ $staffUser->firstName }} {{ $staffUser->lastName }}</td>
                    <td> {{ $staffUser->project ? $staffUser->project->name : 'Loyiha biriktirilmagan' }} </td>
                    <td>
                        <form action="{{ route('tasks.assign', [$task->id, $staffUser->id]) }}" method="GET">
                            @csrf
                            <button class="btn btn-info" type="submit">Baho berish</button>
                        </form>
                    </td>
                    <td> {{ $staffUser->position }} </td>
                    <td> {{ $staffUser->phone }} </td>
                    <td>{{ isset($ratings[$staffUser->id]) ? $ratings[$staffUser->id] : '0,0' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

