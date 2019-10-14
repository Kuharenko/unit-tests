@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <h2>Update Task</h2>
                </div>
                <a href="{{route('tasks.index')}}">Back</a>
                <div class="card">
                    <form method="POST" action="{{route('tasks.update', ['task'=>$task->id])}}">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" value="{{$task->title}}" class="form-control" id="title"
                                   aria-describedby="titleHelp"
                                   placeholder="Title">

                            @error('title')
                            <small id="titleHelp" class="form-text text-muted">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" cols="30" rows="10"
                                      aria-describedby="descriptionHelp" placeholder="Description">
                                {{$task->description}}
                            </textarea>
                            @error('description')
                            <small id="descriptionHelp" class="form-text text-muted">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
