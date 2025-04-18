@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>TaskAssign qo'shish</h2>
        </div>
        <div class="pull-right">
          @can('create-taskassign')
            <a class="btn btn-primary mb-2" href="{{ route('admin.task_assignments.index') }}">&larr; Orqaga</a>
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
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.task_assignments.store') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="subtask_id" class="form-label">Vazifalar</label>
                    <select class="form-control @error('subtask_id') is-invalid @enderror" id="subtask_id" name="subtask_id">
                        <option value="">SubTaskni tanlang</option>
                        @foreach ($subtasks as $subtask)
                            <option value="{{ $subtask->id }}" {{ old('subtask_id') == $subtask->id ? 'selected' : '' }}>{{ $subtask->title }}</option>
                        @endforeach
                    </select>
                    @error('subtask_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
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

                <div class="mb-3">
                    <label for="rating" class="form-label">Baxo</label>
                    <input type="number" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating" value="{{ old('rating') }}">
                    @error('rating')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment">{{ old('comment') }}</textarea>
                        @if ($errors->has('comment'))
                            <span class="text-danger">{{ $errors->first('comment') }}</span>
                        @endif
                </div>

                <div class="mb-3 ">
                    <label for="addDate" class="form-label">Add Date:</label>
                    <input type="date" class="form-control" id="addDate" name="addDate" value="{{ old('addDate') }}">
                </div>

                <div class="mb-3">
                    <input type="submit" class="btn btn-primary" value="Add SubTask">
                </div>
                
            </form>
        </div>
    </div>
</div>    

@endsection
