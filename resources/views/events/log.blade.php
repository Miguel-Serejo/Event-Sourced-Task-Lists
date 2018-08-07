<h1>Event Log</h1>
<h6 class="text-muted">Hover for details</h6>
<a href="{{ route('undo') }}">Undo</a>
<table class="table table-hover table-responsive">
  <thead>
    <th>id</th>
    <th>event</th>
    <th>timestamp</th>
    <th>replay</th>
  </thead>
  <tbody>
    @forelse ($events as $event)
      <tr title="{{var_export($event->event_properties, true)}}">
        <td>{{$event->id}}</td>
        <td>{{substr($event->event_class, strrpos($event->event_class, '\\')+1)}}</td>
        <td>{{$event->created_at}}</td>
        <td>
          <a href="{{route('history.' . str_replace('history.', '', Route::currentRouteName()), array_merge(['event' => $event->id], $routeParams ?? []))}}">
            @if ($event->id < $current_event)
              <i class="fas fa-history"></i>
            @elseif ($event->id > $current_event)
              <i class="fas fa-redo"></i>
            @endif
          </a>
        </td>
      </tr>
      @if ($loop->first)
        <tr>
          <td colspan="4"></td>
        </tr>
      @endif
      @if ($loop->last)
        <tr>
          <td></td>
          <td>Initial State</td>
          <td></td>
          <td>
            @if ($current_event > 0)
              <a href="{{route('history.' . str_replace('history.', '', Route::currentRouteName()), array_merge(['event' => 0], $routeParams ?? []))}}" method="get">
                <i class="fas fa-history"></i>
              </a>
            @endif
          </td>
        </tr>
      @endif
    @empty
      <tr>
        <td colspan=4>Nothing has happened.</td>
      </tr>
    @endforelse
  </tbody>
</table>
