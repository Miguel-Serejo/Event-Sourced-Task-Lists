<?php

namespace App\Projectors;

use App\Milestone;

use App\Events\MilestoneAchieved;

use Illuminate\Support\Carbon;

use Spatie\EventProjector\Projectors\ProjectsEvents;
use Spatie\EventProjector\Projectors\QueuedProjector;

class MilestoneProjector implements QueuedProjector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        MilestoneAchieved::class,
    ];


    public function onMilestoneAchieved(MilestoneAchieved $event)
    {
      Milestone::create([
        'text' => $event->milestoneAttributes['text'],
        'timestamp' => Carbon::createFromTimestamp($event->milestoneAttributes['timestamp']),
      ]);
    }

    public function resetState()
   {
       Milestone::truncate();
   }

}
