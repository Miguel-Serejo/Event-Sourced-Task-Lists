<?php

namespace App\Projectors;

use App\TaskList;

use App\Events\TaskListCreated;
use App\Events\TaskListDeleted;
use App\Events\TaskListMarkedComplete;
use App\Events\TaskListMarkedIncomplete;

use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class TaskListProjector implements Projector
{
  use ProjectsEvents;

  /*
  * Here you can specify which event should trigger which method.
  */
  protected $handlesEvents = [
    TaskListCreated::class,
    TaskListDeleted::class,
  ];

  public function streamEventsBy()
  {
    return 'taskListAttributes.uuid';
  }

  public function resetState()
   {
     TaskList::truncate();
   }

  public function onTaskListCreated(TaskListCreated $event)
  {
    return TaskList::create($event->taskListAttributes);
  }

  public function onTaskListDeleted(TaskListDeleted $event) : void
  {
    TaskList::where('uuid', $event->taskListAttributes['uuid'])->delete();
  }

}
