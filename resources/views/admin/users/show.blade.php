@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Foydalanuvchi Tafsilotlari</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary mb-2" href="{{ route('admin.users.index') }}">&larr; Orqaga</a>
        </div>
    </div>
</div>

<div class="col-md-8">
    <div class="card">
        <div class="card-body">
            <table class="table ">
                <tr>
                    <th>First Name:</th>
                    <td>{{ $user->firstName }}</td>
                </tr>
                <tr>
                    <th>Last Name:</th>
                    <td>{{ $user->lastName }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Position:</th>
                    <td>{{ $user->position }}</td>
                </tr>
                <tr>
                    <th>Salary:</th>
                    <td>{{ $user->salary }}</td>
                </tr>
                <tr>
                    <th>Last Working Date:</th>
                    <td>{{ $user->lastDate ? \Carbon\Carbon::parse($user->lastDate)->format('d-m-Y') : 'Mavjud emas' }}</td>
                </tr>
                <tr>
                    <th>Project:</th>
                    <td>{{ $user->project->name ?? 'Tayinlanmagan' }}</td>
                </tr>
                <tr>
                    <th>Roles:</th>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge bg-info text-white">{{ $role->name }}</span>
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
