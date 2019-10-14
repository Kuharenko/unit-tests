@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <h2>Single Task</h2>
                </div>
                <a href="{{route('tasks.index')}}">Back</a>
                <div class="card">
                    <div class="card-header">{{$task->title}}</div>

                    <div class="card-body">
                        {{$task->description}}
                    </div>

                    <a href="{{route('tasks.edit',['task'=>$task->id])}}" class="btn btn-primary btn-lg @if(auth()->user() && $task->user_id == auth()->user()->id) active @else disabled @endif" role="button" aria-pressed="true">Edit</a>
                </div>
            </div>
        </div>
    </div>
@endsection
