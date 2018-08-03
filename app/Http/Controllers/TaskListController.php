<?php

namespace App\Http\Controllers;

use App\TaskList;
use App\Milestone;
use App\Statistic;

use Illuminate\Http\Request;

use Spatie\EventProjector\Models\StoredEvent;

use Spatie\EventProjector\Facades\Projectionist;

use Illuminate\Support\Facades\DB;

class TaskListController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    $taskLists = TaskList::withCount('tasks')->get();
    $statistics = Statistic::get();
    $events = StoredEvent::limit(10)->orderByDesc('id')->get();
    $milestones = Milestone::get();
    $current_event = StoredEvent::max('id');

    return view('task_lists.index', [
      'taskLists' => $taskLists,
      'statistics' => $statistics,
      'events' => $events,
      'milestones' => $milestones,
      'current_event' => $current_event,
    ]);
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $this->validate(
      $request,
      [
        'name' => 'required|string|max:255',
      ]);

    TaskList::createWithAttributes([
      'name' => $request->input('name'),
    ]);
    return back();
  }

  public function seed()
  {
    $new_taskList_number = 10;
    $new_taskLists = factory(TaskList::class, $new_taskList_number)->make();
    foreach ($new_taskLists as $new_taskList) {
      TaskList::createWithAttributes([
          'name' => $new_taskList->name,
          'uuid' => $new_taskList->uuid,
      ]);
    }

    foreach(TaskList::inRandomOrder()->take(rand(1, $new_taskList_number))->get() as $taskList_to_delete) {
      $taskList_to_delete->erase();
    }
    return back();
  }

  public function reset()
  {
    foreach(Projectionist::getProjectors() as $projector) {
      $projector->reset();
    }

    DB::table('stored_events')->truncate();
    DB::table('projector_statuses')->truncate();
    return redirect('/');
  }
}
