<?php

namespace App\Events;

use Spatie\EventProjector\ShouldBeStored;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MilestoneAchieved implements ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var array */
    public $milestoneAttributes;

    /**
     * Create a new event instance.
     *
     * @param array $milestoneAttributes
     * @return void
     */
    public function __construct(array $milestoneAttributes)
    {
        $this->milestoneAttributes = $milestoneAttributes;
    }

}
