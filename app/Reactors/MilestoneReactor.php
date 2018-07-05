<?php

namespace App\Reactors;

use App\Milestone;

use App\Events\TaskCreated;
use App\Events\MilestoneAchieved;
use App\Events\TaskMarkedComplete;

use Spatie\EventProjector\Models\StoredEvent;

use Spatie\EventProjector\EventHandlers\EventHandler;
use Spatie\EventProjector\EventHandlers\HandlesEvents;

class MilestoneReactor implements EventHandler
{
    use HandlesEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        TaskCreated::class,
        TaskMarkedComplete::class,
    ];


    public function onTaskCreated(StoredEvent $storedEvent)
    {
      $created_task_count = StoredEvent::where('event_class', TaskCreated::class)->count();
      if ( $created_task_count % 10 == 0 ) {
        Milestone::achieve([
          'text' => 'Created ' . $created_task_count . ' tasks!',
          'timestamp' => now()->getTimestamp(),
        ]);
      }
    }

    public function onTaskMarkedComplete(StoredEvent $storedEvent)
    {
      $completed_task_count = StoredEvent::where('event_class', TaskMarkedComplete::class)->count();
      if ( $completed_task_count % 10 == 0 ) {
        Milestone::achieve([
          'text' => 'Completed ' . $completed_task_count . ' tasks!',
          'timestamp' => now()->getTimestamp(),
        ]);
      }
    }

}
