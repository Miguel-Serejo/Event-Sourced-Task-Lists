<?php

namespace App\Events;

use Spatie\EventProjector\ShouldBeStored;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskMarkedComplete implements ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var array */
    public $taskAttributes;

    /**
     * Create a new event instance.
     *
     * @param array $taskAttributes
     * @return void
     */
    public function __construct(array $taskAttributes)
    {
        $this->taskAttributes = $taskAttributes;
    }
}
