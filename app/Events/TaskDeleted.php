<?php

namespace App\Events;

use App\Traits\Undoable;

use Spatie\EventProjector\ShouldBeStored;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskDeleted implements ShouldBeStored
{
    use Dispatchable, Undoable;

    /** @var array */
    public $taskAttributes;

    /** @var array */
    //public $meta_data; // Commented out to avoid serialization issues

    /** @var string */
    public $undoEvent = TaskCreated::class;

    /** @var array */
    public $undoAttributes = ['taskAttributes'];

    /**
     * Create a new event instance.
     *
     * @param array $taskAttributes
     * @param array $meta_data
     * @return void
     */
    public function __construct(array $taskAttributes, array $meta_data = [])
    {
        $this->taskAttributes = $taskAttributes;
        $this->meta_data = $meta_data;
    }

}
