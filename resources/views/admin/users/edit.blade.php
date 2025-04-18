@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Foydalanuvchini tahrirlash</h2>
            <a class="btn btn-secondary" href="{{ route('admin.users.index') }}">&larr; Orqaga</a>
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
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" value="{{ old('firstName', $user->firstName) }}">
                </div>
                
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" value="{{ old('lastName', $user->lastName) }}">
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address:</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                
                <div class="mb-3 ">
                    <label for="phone" class="form-label">Phone numer:</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="mb-3">
                    <label for="position" class="form-label">Position:</label>
                    <select class="form-select" id="position" name="position">
                        <option value="Yetakchi mutaxasis" {{ $user->position == 'Yetakchi mutaxasis' ? 'selected' : '' }}>Yetakchi mutaxasis</option>
                        <option value="Bosh mutaxasis" {{ $user->position == 'Bosh mutaxasis' ? 'selected' : '' }}>Bosh mutaxasis</option>
                        <option value="Loyiha raxbari" {{ $user->position == 'Loyiha raxbari' ? 'selected' : '' }}>Loyiha raxbari</option>
                        <option value="Loyiha rahbari o`rinbosari" {{ $user->position == 'Loyiha rahbari o`rinbosari' ? 'selected' : '' }}>Loyiha rahbari o`rinbosari</option>
                        <option value="Markaz rahbari" {{ $user->position == 'Markaz rahbari' ? 'selected' : '' }}>Markaz rahbari</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="salary" class="form-label">Salary:</label>
                    <input type="number" step="0.01" class="form-control" id="salary" name="salary" value="{{ old('salary', $user->salary) }}">
                </div>
                
                <div class="mb-3">
                    <label for="lastDate" class="form-label">Last Working Date:</label>
                    <input type="date" class="form-control" id="lastDate" name="lastDate" value="{{ old('lastDate', $user->lastDate) }}">
                </div>
                
                <div class="mb-3">
                    <label for="project_id" class="form-label">Project:</label>
                    <select class="form-select" name="project_id" id="project_id">
                        <option value="">--------</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ $user->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="roles" class="form-label">Roles:</label>
                    <select class="form-select" multiple name="roles[]" id="roles">
                        @foreach ($roles as $role)
                            @if ($role != 'Super Admin' || Auth::user()->hasRole('Super Admin'))
                                <option value="{{ $role }}" {{ in_array($role, $user->roles->pluck('name')->toArray()) ? 'selected' : '' }}>{{ $role }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('roles')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">New Password (Optional):</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="text-muted">Agar parolni o'zgartirmoqchi bo'lsangiz, kiriting.</small>
                </div>
                
                <button type="submit" class="btn btn-primary">Update User</button>
            </form>
        </div>
    </div>
</div>
@endsection
