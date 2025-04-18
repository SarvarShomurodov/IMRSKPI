@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>TaskAssignlarni qo'shish</h2>
        </div>
        <div class="pull-right">
          @can('create-taskassign')
            <a class="btn btn-primary mb-2" href="{{ route('admin.task_assignments.create') }}">TaskAssign qo'shish</a>
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
                <th scope="col">FISH</th>
                <th scope="col">Vazifalar</th>
                <th scope="col">Baxo</th>
                <th scope="col">Sana</th>
                <th scope="col">Comment</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $assignment->user->firstName }} {{ $assignment->user->lastName }}</td>
                    <td>{{ $assignment->subtask->title }}</td>
                    <td>{{ $assignment->rating }}</td>
                    {{-- <td>{{ $assignment->rating }}</td> --}}
                    <td>{{ $assignment->addDate }}</td>
                    <td>{{ $assignment->comment }}</td>
                    <td>
                        <form action="{{ route('admin.task_assignments.destroy', $assignment->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            @can('edit-subtask')
                                <a href="{{ route('admin.task_assignments.edit', $assignment->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>
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