@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Tasklarni qo'shish</h2>
        </div>
        <div class="pull-right">
          @can('create-task')
            <a class="btn btn-primary mb-2" href="{{ route('admin.tasks.create') }}">Task qo'shish</a>
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
                <th scope="col">Description</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $task->taskName }}</td>
                    <td>{{ $task->description }}</td>
                    <td>
                        <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            @can('edit-task')
                                <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endcan

                            @can('delete-task')
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