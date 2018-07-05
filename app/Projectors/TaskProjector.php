<?php

namespace App\Projectors;

use App\Task;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskMarkedComplete;

use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class TaskProjector implements Projector
{
  use ProjectsEvents;

  /*
  * Here you can specify which event should trigger which method.
  */
  protected $handlesEvents = [
    TaskMarkedComplete::class,
    TaskCreated::class,
    TaskDeleted::class,
  ];

  public function streamEventsBy()
  {
    return 'taskAttributes.uuid';
  }

  public function resetState()
     {
         Task::truncate();
     }

  public function onTaskMarkedComplete(TaskMarkedComplete $event) : void
  {
    $task = Task::where('uuid', $event->taskAttributes['uuid'])->update([
      'completed_at' => now(),
    ]);
  }

  public function onTaskCreated(TaskCreated $event)
  {
    return Task::create($event->taskAttributes);
  }

  public function onTaskDeleted(TaskDeleted $event) : void
  {
    Task::where('uuid', $event->taskAttributes['uuid'])->delete();
  }

}
