@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>TaskAssign tahrirlash</h2>
        </div>
        <div class="pull-right">
          @can('edit-taskassign')
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
            <form action="{{ route('admin.task_assignments.update', $taskAssignment->id) }}" method="post">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="subtask_id" class="form-label">Vazifalar</label>
                    <select class="form-control @error('subtask_id') is-invalid @enderror" id="subtask_id" name="subtask_id">
                        <option value="">SubTaskni tanlang</option>
                        @foreach ($subtasks as $subtask)
                            <option value="{{ $subtask->id }}" {{ (old('subtask_id', $taskAssignment->subtask_id) == $subtask->id) ? 'selected' : '' }}>
                                {{ $subtask->title }} 
                            </option>
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
                            <option value="{{ $user->id }}" {{ (old('user_id', $taskAssignment->user_id) == $user->id) ? 'selected' : '' }}>
                                {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="rating" class="form-label">Baxo</label>
                    <input type="number" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating" value="{{ old('rating', $taskAssignment->rating) }}">
                    @error('rating')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment">{{ old('comment', $taskAssignment->comment) }}</textarea>
                    @error('comment')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="addDate" class="form-label">Add Date:</label>
                    <input type="date" class="form-control" id="addDate" name="addDate" value="{{ old('addDate', $taskAssignment->addDate) }}">
                </div>

                <div class="mb-3">
                    <input type="submit" class="btn btn-success" value="Saqlash">
                </div>
                
            </form>
        </div>
    </div>
</div>    

@endsection
