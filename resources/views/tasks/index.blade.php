@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <h2>All Tasks</h2>
                </div>

                @if (auth()->user())
                    <a href="{{route('tasks.create')}}" class="btn btn-primary btn-lg active" role="button"
                       aria-pressed="true">Create</a>
                @endif
                @foreach($tasks as $task)
                    <div class="card">
                        <div class="card-header">{{$task->title}}</div>

                        <div class="card-body">
                            {{$task->description}}
                        </div>

                        <a href="{{route('tasks.show',['task'=>$task->id])}}">Show</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
