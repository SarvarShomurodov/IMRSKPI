@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>SubTask qo'shish</h2>
        </div>
        <div class="pull-right">
          @can('create-subtask')
            <a class="btn btn-primary mb-2" href="{{ route('admin.subtasks.index') }}">&larr; Orqaga</a>
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
            <form action="{{ route('admin.subtasks.store') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="task_id" class="form-label">Task</label>
                    <select class="form-control @error('task_id') is-invalid @enderror" id="task_id" name="task_id">
                        <option value="">Select Task</option>
                        @foreach ($tasks as $task)
                            <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>{{ $task->taskName }}</option>
                        @endforeach
                    </select>
                    @error('task_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <textarea class="form-control @error('title') is-invalid @enderror" id="title" name="title">{{ old('title') }}</textarea>
                    {{-- <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}"> --}}
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}">
                    @error('category')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="min" class="form-label">Min</label>
                    <input type="number" class="form-control @error('min') is-invalid @enderror" id="min" name="min" value="{{ old('min') }}" step="any">
                    @error('min')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                

                <div class="mb-3">
                    <label for="max" class="form-label">Max</label>
                    <input type="number" class="form-control @error('max') is-invalid @enderror" id="max" name="max" value="{{ old('max') }}">
                    @error('max')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <input type="submit" class="btn btn-primary" value="Add SubTask">
                </div>
                
            </form>
        </div>
    </div>
</div>    

@endsection
