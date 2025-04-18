@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center mb-3">
        <h2>Bonus qo‘shish</h2>
        <a class="btn btn-primary" href="{{ route('admin.bonuses.index') }}">&larr; Orqaga</a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="col-md-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.bonuses.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nomi</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="bonus" class="form-label">Bonus</label>
                    <input type="text" class="form-control @error('bonus') is-invalid @enderror" 
                           id="bonus" name="bonus" value="{{ old('bonus') }}">
                    @error('bonus') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="user_id" class="form-label">User</label>
                    <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                        <option value="">Userni tanlang</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->email }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 ">
                    <label for="given_date" class="form-label">Add Date:</label>
                    <input type="date" class="form-control" id="given_date" name="given_date" value="{{ old('given_date') }}">
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-success" value="Qo‘shish">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
