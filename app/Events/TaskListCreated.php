<?php

namespace App\Events;

use Spatie\EventProjector\ShouldBeStored;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskListCreated implements ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var array */
    public $taskListAttributes;

    /**
     * Create a new event instance.
     *
     * @param array $taskListAttributes
     * @return void
     */
    public function __construct(array $taskListAttributes)
    {
        $this->taskListAttributes = $taskListAttributes;
    }

}
