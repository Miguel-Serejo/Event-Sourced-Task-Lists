@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row mx-3">
      <div class="col-12 col-xl-4">
        @include('events.log')
      </div>
      <div class="col-12 col-xl-5">
        <div class="row">
          <div class="col-6">
            <form action="{{route('lists.reset')}}" method="post" class="form">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-muted">Reset All</button>
            </form>
          </div>
          <div class="col-6">
            <form action="{{route('lists.seed')}}" method="post">
              @csrf
              <button type="submit" class="btn btn-primary">Seed</button>
            </form>
          </div>
        </div>
        <form class="form-inline my-3" action="{{route('lists.store')}}" method="POST">
          @csrf
          <div class="form-group mr-5">
            <input type="text" class="form-control" id="newListName" name="name" placeholder="Name">
          </div>
          <button type="submit" class="btn btn-primary">Create Task List</button>
        </form>
        <table class="table table-hover">
          <thead>
            <th>id</th>
            <th>name</th>
            <th>tasks</th>
            <th>delete</th>
          </thead>
          @forelse ($taskLists as $taskList)
            <tr>
              <td title="{{$taskList->uuid}}">{{substr($taskList->uuid, 0, 8)}}</td>
              <td><a href="{{route('tasks.index', ['listUuid' => $taskList->uuid])}}">{{$taskList->name}}</a></td>
              <td>{{$taskList->tasks_count}}</td>
              <td>
                <form id="delete-{{$taskList->uuid}}" action="{{route('lists.destroy', ['taskList' => $taskList->uuid])}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button type="submit" name="button" class="btn btn-danger">X</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan=4>There are no lists.</td>
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
