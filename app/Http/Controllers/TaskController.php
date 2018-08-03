<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskList;
use App\Milestone;
use App\Statistic;

use Illuminate\Http\Request;

use Spatie\EventProjector\Models\StoredEvent;

class TaskController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @param \App\TaskList $list
  * @return \Illuminate\Http\Response
  */
  public function index(TaskList $list)
  {
    $tasks = Task::where('task_list_uuid', $list->uuid)->get();
    $statistics = Statistic::get();
    $events = StoredEvent::limit(10)->orderByDesc('id')->get();
    $milestones = Milestone::get();
    $current_event = StoredEvent::max('id');

    return view('tasks.index', [
      'tasks' => $tasks,
      'statistics' => $statistics,
      'events' => $events,
      'current_event' => $current_event,
      'milestones' => $milestones,
      'current_list' => $list->uuid,
    ]);
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\TaskList  $list
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request, TaskList $list)
  {
    $this->validate(
      $request,
      [
        'title' => 'required|string|max:255',
        'comment' => 'nullable|string|max:255',
      ]);
    Task::createWithAttributes([
      'title' => $request->input('title'),
      'comment' => $request->input('comment'),
      'task_list_uuid' => $list->uuid,
    ]);
    return back();
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\TaskList  $list
  * @param  \App\Task  $task
  * @return \Illuminate\Http\Response
  */
  public function destroy(TaskList $list, Task $task)
  {
    $task->erase();
    return back();
  }

  /**
  * Complete a collection of tasks
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function complete(Request $request)
  {
    $tasks = $request->get('tasks', []);
    foreach ($tasks as $taskUuid) {
      Task::where('uuid', $taskUuid)->first()->markComplete();
    }
    return back();
  }

  /**
  * Complete a collection of tasks
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function uncomplete(Request $request)
  {
    $tasks = $request->get('tasks', []);
    foreach ($tasks as $taskUuid) {
      Task::where('uuid', $taskUuid)->first()->markIncomplete();
    }
    return back();
  }

  public function seed(TaskList $list)
  {
    $new_task_number = 10; //rand(3, 10);
    $new_tasks = factory(Task::class, $new_task_number)->make();
    foreach ($new_tasks as $new_task) {
      Task::createWithAttributes([
          'title' => $new_task->title,
          'comment' => $new_task->comment,
          'task_list_uuid' => $list->uuid,
      ]);
    }
    foreach(Task::where('task_list_uuid', $list->uuid)->inRandomOrder()->take(rand(1, $new_task_number))->get() as $task_to_complete) {
      $task_to_complete->markComplete();
    }

    foreach(Task::where('task_list_uuid', $list->uuid)->inRandomOrder()->take(rand(1, $new_task_number))->get() as $task_to_delete) {
      $task_to_delete->erase();
    }
    return back();
  }

  }
