@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Userlarni qo'shish</h2>
        </div>
        <div class="pull-right">
          @can('create-user')
            <a class="btn btn-primary mb-2" href="{{ route('admin.users.index') }}">&larr; Orqaga</a>
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
                <form action="{{ route('admin.users.store') }}" method="post">
                    @csrf

                     {{-- First Name --}}
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="{{ old('firstName') }}">
                    </div>
                
                    {{-- Last Name --}}
                    <div class="mb-3 ">
                        <label for="lastName" class="form-label">Last Name:</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="{{ old('lastName') }}">
                    </div>

                    <div class="mb-3 ">
                        <label for="email" class="form-label">Email Address:</label>
                          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                    </div>

                    <div class="mb-3 ">
                        <label for="phone" class="form-label">Phone numer:</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                    </div>
                    {{-- Position --}}
                    <div class="mb-3 ">
                        <label for="position" class="form-label">Position:</label>
                            <select class="form-select form-select-sm" id="position" name="position">
                                <option value="Yetakchi mutaxasis" {{ old('position') == 'Yetakchi mutaxasis' ? 'selected' : '' }}>Yetakchi mutaxasis</option>
                                <option value="Bosh mutaxasis" {{ old('position') == 'Bosh mutaxasis' ? 'selected' : '' }}>Bosh mutaxasis</option>
                                <option value="Loyiha raxbari" {{ old('position') == 'Loyiha raxbari' ? 'selected' : '' }}>Loyiha raxbari</option>
                                <option value="Loyiha rahbari o`rinbosari" {{ old('position') == 'Loyiha rahbari o`rinbosari' ? 'selected' : '' }}>Loyiha rahbari o`rinbosari</option>
                                <option value="Markaz rahbari" {{ old('position') == 'Markaz rahbari' ? 'selected' : '' }}>Markaz rahbari</option>
                            </select>
                    </div>

                    {{-- Salary --}}
                    <div class="mb-3 ">
                        <label for="salary" class="form-label">Salary:</label>
                            <input type="number" step="0.01" class="form-control" id="salary" name="salary" value="{{ old('salary') }}">
                    </div>

                    {{-- Last Date --}}
                    <div class="mb-3 ">
                        <label for="lastDate" class="form-label">Last Working Date:</label>
                            <input type="date" class="form-control" id="lastDate" name="lastDate" value="{{ old('lastDate') }}">
                    </div>

                    <div class="mb-3 ">
                        <label for="project_id" class="form-label">Project:</label>
                            <select name="project_id" class="form-control" id="project_id" class="form-control" required>
                                <option value="">--------</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                    </div>

                    <div class="mb-3 ">
                        <label for="password" class="form-label">Password:</label>
                          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                    </div>

                    <div class="mb-3 ">
                        <label for="roles" class="form-label">Roles:</label>       
                            <select class="form-select @error('roles') is-invalid @enderror" multiple aria-label="Roles" id="roles" name="roles[]">
                                @forelse ($roles as $role)

                                    @if ($role!='Super Admin')
                                        <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
                                        {{ $role }}
                                        </option>
                                    @else
                                        @if (Auth::user()->hasRole('Super Admin'))   
                                            <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
                                            {{ $role }}
                                            </option>
                                        @endif
                                    @endif

                                @empty

                                @endforelse
                            </select>
                            @if ($errors->has('roles'))
                                <span class="text-danger">{{ $errors->first('roles') }}</span>
                            @endif
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary me-2" value="Add User">
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection