@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Loyihani o'zgartirish</h2>
        </div>
        <div class="pull-right">
          @can('create-user')
            <a class="btn btn-primary mb-2" href="{{ route('admin.projects.index') }}">&larr; Orqaga</a>
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
                <form action="{{ route('admin.projects.update', $project->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $project->name }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ $project->description }}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                    </div>
                    
                    <div class="mb-3">
                        <input type="submit" class="btn btn-primary" value="Update">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    

    
@endsection