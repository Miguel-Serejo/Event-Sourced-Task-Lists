<?php

namespace App\Http\Controllers\History;

use Spatie\EventProjector\Models\StoredEvent;

use Illuminate\Support\Facades\Session;

class TaskListController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @param int $eventId
  * @return \Illuminate\Http\Response
  */
  public function index(int $eventId)
  {
    $current_event = Session::get('history_current_event', 0);
    if ($eventId !== $current_event) {
      $this->setState($eventId);
      $current_event = Session::get('history_current_event', 0);
    }
    $taskLists = Session::get('taskLists', []);
    $statistics = Session::get('statistics', []);
    $events = StoredEvent::limit(10)->orderByDesc('id')->whereBetween('id', [$current_event-5, $current_event+5])->orWhere('id', StoredEvent::max('id'))->get();
    $milestones = Session::get('milestones', []);
    $tasks = Session::get('tasks', collect());

    return view('history.task_lists.index', [
      'taskLists' => $taskLists,
      'tasks' => $tasks,
      'statistics' => $statistics,
      'events' => $events,
      'milestones' => $milestones,
      'current_event' => $current_event,
    ]);
  }

}
