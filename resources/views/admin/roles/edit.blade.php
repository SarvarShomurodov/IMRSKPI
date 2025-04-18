@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Rollarni o'zgartirish</h2>
        </div>
        <div class="pull-right">
          @can('create-user')
            <a class="btn btn-info mb-2" href="{{ route('admin.roles.index') }}">&larr; Orqaga</a>
          @endcan
        </div>
    </div>
  </div>
  @if (session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
  @endif
    <div class="col-md-4">

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.roles.update', $role->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $role->name }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="permissions" class="form-label">Permissions</label>       
                        <select class="form-select @error('permissions') is-invalid @enderror" multiple aria-label="Permissions" id="permissions" name="permissions[]" style="height: 210px;">
                            @forelse ($permissions as $permission)
                                <option value="{{ $permission->id }}" {{ in_array($permission->id, $rolePermissions ?? []) ? 'selected' : '' }}>
                                    {{ $permission->name }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                        @if ($errors->has('permissions'))
                            <span class="text-danger">{{ $errors->first('permissions') }}</span>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary me-2" value="Update Role">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    

    
@endsection