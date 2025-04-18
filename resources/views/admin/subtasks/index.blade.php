@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>SubTasklarni qo'shish</h2>
        </div>
        <div class="pull-right">
          @can('create-subtask')
            <a class="btn btn-primary mb-2" href="{{ route('admin.subtasks.create') }}">SubTask qo'shish</a>
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
                <th scope="col">Task Name</th>
                <th scope="col">Title</th>
                <th scope="col">Category</th>
                <th scope="col">Min</th>
                <th scope="col">Max</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subtasks as $task)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $task->task->taskName }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->category }}</td>
                    <td>{{ $task->min }}</td>
                    <td>{{ $task->max }}</td>
                    <td>
                        <form action="{{ route('admin.subtasks.destroy', $task->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            @can('edit-subtask')
                                <a href="{{ route('admin.subtasks.edit', $task->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endcan

                            @can('delete-subtask')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Do you want to delete this project?');"><i class="bi bi-trash"></i> Delete</button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="4">
                        <span class="text-danger">
                            <strong>No Project Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
            </table>
        {{-- <div class="table-responsive">
    </div> --}}
</div>
@endsection