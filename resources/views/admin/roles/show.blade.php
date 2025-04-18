@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Rol Tafsilotlari</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary mb-2" href="{{ route('admin.roles.index') }}">&larr; Orqaga</a>
        </div>
    </div>
</div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $role->name }}
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>Permissions:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        @if ($role->name=='Super Admin')
                            <span class="badge bg-primary">All</span>
                        @else
                            @forelse ($rolePermissions as $permission)
                                <span class="badge bg-primary">{{ $permission->name }}</span>
                            @empty
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection