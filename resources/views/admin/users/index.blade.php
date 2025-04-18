@extends('layouts.admin')

@section('content')
<div class="row">
  <div class="col-lg-12 margin-tb">
      <div class="pull-left">
          <h2>Userlarni boshqarish</h2>
      </div>
      <div class="pull-right">
        @can('create-user')
          <a class="btn btn-primary mb-2" href="{{ route('admin.users.create') }}">User qo'shish</a>
        @endcan
      </div>
  </div>
</div>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="myTable">
                <thead>
                    <tr>
                    <th scope="col">№</th>
                    <th scope="col">Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Email</th>
                    <th scope="col">Roles</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $user->firstName }} {{ $user->lastName }}</td>
                        <td>{{ $user->position }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse ($user->getRoleNames() as $role)
                                <span class="badge bg-primary">{{ $role }}</span>
                            @empty
                            @endforelse
                        </td>
                        <td>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
    
                                {{-- <a href="{{ route('users.show', $user->id) }}" class="btn btn-warning"><i class="bi bi-eye"></i> Show</a> --}}
    
                                @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                                    @if (Auth::user()->hasRole('Super Admin'))
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                                    @endif
                                @else
                                    @can('edit-user')
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>   
                                    @endcan
    
                                    @can('delete-user')
                                        @if (Auth::user()->id!=$user->id)
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Do you want to delete this user?');"><i class="bi bi-trash"></i> Delete</button>
                                        @endif
                                    @endcan
                                @endif
    
                            </form>
                        </td>
                    </tr>
                    @empty
                        <td colspan="5">
                            <span class="text-danger">
                                <strong>No User Found!</strong>
                            </span>
                        </td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    
@endsection