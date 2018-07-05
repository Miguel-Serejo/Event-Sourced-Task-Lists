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
            <a href="{{route('history.lists.index', ['event' => $current_event])}}">Lists</a>
            »
            {{$current_list}}
            <a href="{{route('tasks.index', ['list' => $current_list])}}">(Live)<a>
          </div>
        </div>
        <table class="table table-hover">
          <thead>
            <th>id</th>
            <th>title</th>
            <th>comment</th>
            <th>completed</th>
          </thead>
          @forelse ($tasks as $task)
            <tr>
              <td title="{{$task->uuid}}">{{substr($task->uuid, 0, 8)}}</td>
              <td>{{$task->title}}</td>
              <td>{{$task->comment}}</td>
              <td>{{$task->isComplete() ? "Yes" : "No"}}</td>
            </tr>
          @empty
            <tr>
              <td colspan=4>There are no tasks in this list.</td>
            </tr>
          @endforelse
        </table>
      </div>
      <div class="col-12 col-xl-2">
        @include('partials.statistics')
        @include('partials.milestones')
      </div>
    </div>
  </div>
@endsection
