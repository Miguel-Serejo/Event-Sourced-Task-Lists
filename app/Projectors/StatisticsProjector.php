<?php

namespace App\Projectors;

use App\Statistic;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskListCreated;
use App\Events\TaskListDeleted;
use App\Events\TaskMarkedComplete;

use Spatie\EventProjector\Models\StoredEvent;

use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class StatisticsProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        TaskCreated::class,
        TaskDeleted::class,
        TaskListCreated::class,
        TaskListDeleted::class,
        TaskMarkedComplete::class,
    ];

    public function resetState()
   {
       Statistic::truncate();
   }

    public function onTaskCreated(TaskCreated $event) {
      Statistic::firstOrCreate(['name' => 'Tasks Created'], ['value' => 0])->increment('value');
      Statistic::firstOrCreate(['name' => 'Active Tasks'], ['value' => 0])->increment('value');
    }

    public function onTaskDeleted(StoredEvent $storedEvent) {
      $model_already_deleted = StoredEvent::where('event_class', TaskDeleted::class)
                                          ->where('event_properties->taskAttributes[uuid]', '=', $storedEvent->event->taskAttributes['uuid'])
                                          ->where('id', '<', $storedEvent->id)
                                          ->exists();
      if ( ! $model_already_deleted ) {
        Statistic::firstOrCreate(['name' => 'Tasks Deleted'], ['value' => 0])->increment('value');
        Statistic::firstOrCreate(['name' => 'Active Tasks'], ['value' => 0])->decrement('value');
      }
    }

    public function onTaskListCreated(TaskListCreated $event) {
      Statistic::firstOrCreate(['name' => 'Task Lists Created'], ['value' => 0])->increment('value');
      Statistic::firstOrCreate(['name' => 'Active Task Lists'], ['value' => 0])->increment('value');
    }

    public function onTaskListDeleted(StoredEvent $storedEvent) {
      $model_already_deleted = StoredEvent::where('event_class', TaskListDeleted::class)
                                          ->where('event_properties->taskAttributes[uuid]', '=', $storedEvent->event->taskListAttributes['uuid'])
                                          ->where('id', '<', $storedEvent->id)
                                          ->exists();
      if ( ! $model_already_deleted ) {
        Statistic::firstOrCreate(['name' => 'Task Lists Deleted'], ['value' => 0])->increment('value');
        Statistic::firstOrCreate(['name' => 'Active Task Lists'], ['value' => 0])->decrement('value');
      }
    }

    public function onTaskMarkedComplete(StoredEvent $storedEvent) {
      $model_already_complete = StoredEvent::where('event_class', TaskMarkedComplete::class)
                                          ->where('event_properties->taskAttributes[uuid]', '=', $storedEvent->event->taskAttributes['uuid'])
                                          ->where('id', '<', $storedEvent->id)
                                          ->exists();
      if ( ! $model_already_complete ) {
        Statistic::firstOrCreate(['name' => 'Tasks Completed'], ['value' => 0])->increment('value');
      }
    }

}
