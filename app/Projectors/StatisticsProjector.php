<?php

namespace App\Projectors;

use App\Statistic;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskListCreated;
use App\Events\TaskListDeleted;
use App\Events\TaskMarkedComplete;
use App\Events\TaskMarkedIncomplete;

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
        TaskMarkedIncomplete::class,
    ];

    public function resetState()
   {
       Statistic::truncate();
   }

    public function onTaskCreated(StoredEvent $storedEvent) {
      if( $storedEvent->meta_data['undo'] ) {
        Statistic::where('name', 'Tasks Deleted')->first()->decrement('value');
      } else {
        Statistic::firstOrCreate(['name' => 'Tasks Created'], ['value' => 0])->increment('value');
      }
      Statistic::firstOrCreate(['name' => 'Active Tasks'], ['value' => 0])->increment('value');
    }

    public function onTaskDeleted(StoredEvent $storedEvent) {
      $model_already_deleted = StoredEvent::where('event_class', TaskDeleted::class)
                                          ->where('event_properties->taskAttributes->uuid', '=', $storedEvent->event->taskAttributes['uuid'])
                                          ->whereNull('meta_data->undone')
                                          ->where('id', '<', $storedEvent->id)
                                          ->exists();
      if ( $model_already_deleted ) {
        return;
      }
      Statistic::firstOrCreate(['name' => 'Active Tasks'], ['value' => 0])->decrement('value');

      if( $storedEvent->meta_data['undo'] ) {
        Statistic::where('name', 'Tasks Created')->first()->decrement('value');
      } else {
        Statistic::firstOrCreate(['name' => 'Tasks Deleted'], ['value' => 0])->increment('value');
      }
    }

    public function onTaskListCreated(StoredEvent $storedEvent) {
      if( $storedEvent->meta_data['undo'] ) {
        Statistic::where('name', 'Task Lists Deleted')->first()->decrement('value');
      } else {
        Statistic::firstOrCreate(['name' => 'Task Lists Created'], ['value' => 0])->increment('value');
      }
      Statistic::firstOrCreate(['name' => 'Active Task Lists'], ['value' => 0])->increment('value');
    }

    public function onTaskListDeleted(StoredEvent $storedEvent) {
      $model_already_deleted = StoredEvent::where('event_class', TaskListDeleted::class)
                                          ->where('event_properties->taskListAttributes->uuid', '=', $storedEvent->event->taskListAttributes['uuid'])
                                          ->whereNull('meta_data->undone')
                                          ->where('id', '<', $storedEvent->id)
                                          ->exists();
      if ( $model_already_deleted ) {
        return; // Duplicate event, do nothing
      }

      Statistic::firstOrCreate(['name' => 'Active Task Lists'], ['value' => 0])->decrement('value');

      if ( $storedEvent->meta_data['undo'] ) {
        Statistic::where('name', 'Task Lists Created')->first()->decrement('value');
      } else {
        Statistic::firstOrCreate(['name' => 'Task Lists Deleted'], ['value' => 0])->increment('value');
      }
    }

    public function onTaskMarkedComplete(StoredEvent $storedEvent) {
      $last_task_complete_event = StoredEvent::where('event_class', TaskMarkedComplete::class)
                                             ->where('event_properties->taskAttributes->uuid', '=', $storedEvent->event->taskAttributes['uuid'])
                                             ->where('id', '<', $storedEvent->id)
                                             ->orderByDesc('id')
                                             ->first();

      if ($last_task_complete_event === null) {
        //Task was never completed, complete now
        Statistic::firstOrCreate(['name' => 'Tasks Completed'], ['value' => 0])->increment('value');
        return;
      }
      $last_task_incomplete_event = StoredEvent::where('event_class', TaskMarkedIncomplete::class)
                                               ->where('event_properties->taskAttributes->uuid', '=', $storedEvent->event->taskAttributes['uuid'])
                                               ->where('id', '<', $storedEvent->id)
                                               ->orderByDesc('id')
                                               ->first();
      if ( $last_task_incomplete_event !== null && $last_task_complete_event->id < $last_task_incomplete_event->id  ) {
        //Task was completed, then marked as incomplete, and hasn't been completed again since
        Statistic::firstOrCreate(['name' => 'Tasks Completed'], ['value' => 0])->increment('value');
      }
    }

    public function onTaskMarkedIncomplete(StoredEvent $storedEvent) {
      $last_task_complete_event = StoredEvent::where('event_class', TaskMarkedComplete::class)
                                             ->where('event_properties->taskAttributes->uuid', '=', $storedEvent->event->taskAttributes['uuid'])
                                             ->where('id', '<', $storedEvent->id)
                                             ->orderByDesc('id')
                                             ->first();

      if ($last_task_complete_event === null) {
        //Task was never completed, nothing to do
        return;
      }
      $last_task_incomplete_event = StoredEvent::where('event_class', TaskMarkedIncomplete::class)
                                               ->where('event_properties->taskAttributes->uuid', '=', $storedEvent->event->taskAttributes['uuid'])
                                               ->where('id', '<', $storedEvent->id)
                                               ->orderByDesc('id')
                                               ->first();
      if ($last_task_incomplete_event === null || $last_task_complete_event->id > $last_task_incomplete_event->id) {
        //Task was completed, and hasn't been marked incomplete since
        //This means that the Tasks Completed statistic exists and is positive
        Statistic::where('name', 'Tasks Completed')->first()->decrement('value');
      }
    }

}
