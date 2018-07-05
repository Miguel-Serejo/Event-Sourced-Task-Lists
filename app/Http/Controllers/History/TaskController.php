<?php

namespace App\Http\Controllers\History;

use App\Task;
use App\TaskList;
use App\Milestone;
use App\Statistic;

use Illuminate\Http\Request;

use Spatie\EventProjector\Models\StoredEvent;

use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @param int $eventId
  * @param \App\TaskList $list
  * @return \Illuminate\Http\Response
  */
  public function index(int $eventId, TaskList $list)
  {
    $current_event = Session::get('history_current_event', 0);
    if ($eventId !== $current_event) {
      $this->setState($eventId);
      $current_event = Session::get('history_current_event', 0);
    }
    $tasks = Session::get('tasks', collect())->where('task_list_uuid', $list->uuid);
    $statistics = Session::get('statistics', []);
    $events = StoredEvent::limit(10)->orderByDesc('id')->whereBetween('id', [$current_event-5, $current_event+5])->orWhere('id', StoredEvent::max('id'))->get();
    $milestones = Session::get('milestones', []);

    return view('history.tasks.index', [
      'tasks' => $tasks,
      'statistics' => $statistics,
      'events' => $events,
      'current_event' => $current_event,
      'milestones' => $milestones,
      'current_list' => $list->uuid,
    ]);
  }
}
