@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row mx-3">
      <div class="col-12 col-xl-4">
        @include('events.log', ['routeParams' => ['list' => $current_list]])
      </div>
      <div class="col-12 col-xl-5">
        <div class="row">
          <div class="col">
            <a href="{{route('lists.index')}}">Lists</a>
            »
            {{$current_list}}
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <form action="{{route('tasks.seed', ['listUuid' => $current_list])}}" method="post">
              @csrf
              <button type="submit" class="btn btn-primary">Seed</button>
            </form>
          </div>
        </div>
        <form class="form-inline my-3" action="{{route('tasks.store', ['listUuid' => $current_list])}}" method="POST">
          @csrf
          <div class="form-group">
            <input type="text" class="form-control" id="newtaskTitle" name="title" placeholder="Title">
          </div>
          <div class="form-group mx-5">
            <input type="text" class="form-control" id="newtaskComment" name="comment" placeholder="Comment">
          </div>
          <button type="submit" class="btn btn-primary">Create task</button>
        </form>
        <table class="table table-hover">
          <thead>
            <th></th>
            <th>id</th>
            <th>title</th>
            <th>comment</th>
            <th>completed</th>
            <th>delete</th>
          </thead>
          @foreach ($tasks as $task)
            <tr>
              <td>
                  <input form="selected" type="checkbox" value="{{$task->uuid}}" name="tasks[]">
              </td>
              <td title="{{$task->uuid}}">{{substr($task->uuid, 0, 8)}}</td>
              <td>{{$task->title}}</td>
              <td>{{$task->comment}}</td>
              <td>{{$task->isComplete() ? "Yes" : "No"}}</td>
              <td>
                <form id="delete-{{$task->uuid}}" action="{{route('tasks.destroy', ['listUuid' => $current_list, 'task' => $task->uuid])}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button type="submit" name="button" class="btn btn-danger">X</button>
                </form>
              </td>
            </tr>
          @endforeach
        </table>
        <form id="selected" method="post">
          @csrf
          <button class="btn btn-primary" type="submit" formaction="{{route('tasks.complete', ['listUuid' => $current_list])}}">Mark Complete</button>
          <button class="btn btn-primary" type="submit" formaction="{{route('tasks.uncomplete', ['listUuid' => $current_list])}}">Mark Incomplete</button>
        </form>
      </div>
      <div class="col-12 col-xl-2">
        @include('partials.statistics')
        @include('partials.milestones')
      </div>
    </div>
  </div>
@endsection
