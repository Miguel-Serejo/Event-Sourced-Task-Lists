@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row mx-3">
      <div class="col-12 col-xl-4">
        @include('events.log')
      </div>
      <div class="col-12 col-xl-5">
        <div class="row">
          <div class="col">
            <a href="{{route('lists.index')}}">Leave History View<a>
          </div>
        </div>
        <table class="table table-hover">
          <thead>
            <th>Id</th>
            <th>Name</th>
            <th>Tasks</th>
          </thead>
          @forelse ($taskLists as $taskList)
            <tr>
              <td title="{{$taskList->uuid}}">{{substr($taskList->uuid, 0, 8)}}</td>
              <td><a href="{{route('history.tasks.index', ['event' => $current_event, 'listUuid' => $taskList->uuid])}}">{{$taskList->name}}</a></td>
              <td>
                {{$tasks->where('task_list_uuid', $taskList->uuid)->count()}}
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
