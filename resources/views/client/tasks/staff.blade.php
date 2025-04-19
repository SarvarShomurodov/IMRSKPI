@extends('layouts.admin')
<style>
    .td{
        color: #9a9a9a;
    }
</style>
@section('content')
    <h4 class="mb-5"><b>Xodimlar ro'yxati</h4>
    <div class="table-container" style="max-height: 650px; overflow-y: auto;">
        <table class="table table-bordered mt-5" id="myTable2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ismi</th>
                    <th>Berilgan loyiha</th>
                    <th>Lavozim</th>
                    <th>Telfon raqam</th>
                    <th>KPI natija</th>
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
                        <td><strong>{{ $staffUser->firstName }} {{ $staffUser->lastName }}</strong></td>
                        <td> {{ $staffUser->project ? $staffUser->project->name : 'Loyiha biriktirilmagan' }} </td>
                        <td> {{ $staffUser->position }} </td>
                        <td> {{ $staffUser->phone }} </td>
                        <td>
                            <a href="{{ route('accounts.staff.kpi', $staffUser->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-chart-line"></i> 
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

