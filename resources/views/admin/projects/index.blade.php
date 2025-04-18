@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Loyihalarni qo'shish</h2>
        </div>
        <div class="pull-right">
          @can('create-project')
            <a class="btn btn-primary mb-2" href="{{ route('admin.projects.create') }}">Loyiha qo'shish</a>
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
                @forelse ($projects as $project)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->description }}</td>
                    <td>
                        <form action="{{ route('admin.projects.destroy', $project->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            @can('edit-project')
                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endcan

                            @can('delete-project')
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
        <div class="table-responsive">
    </div>
</div>
@endsection