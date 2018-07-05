<?php

namespace App;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskMarkedComplete;
use App\Events\TaskMarkedIncomplete;

use Ramsey\Uuid\Uuid;

use Illuminate\Support\Arr;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  protected $guarded = [];

  protected $primaryKey = 'uuid';
  protected $keyType = 'uuid';
  public $increments = false;

  protected $dates = [
    'created_at',
    'completed_at',
  ];

  public function getRouteKeyName() : string
  {
    return 'uuid';
  }

  public static function createWithAttributes(array $attributes) : void
  {
    $attributes['uuid'] = Uuid::uuid4()->toString();
    event(new TaskCreated($attributes));
  }

  public function markComplete() : void
  {
    if ( ! $this->isComplete()) {
      event(new TaskMarkedComplete(array_merge($this->getAttributes(), ['completed_at' => now()->toDateTimeString()])));
    }
  }

  public function erase() : void
  {
    if ( $this->exists ) {
      event(new TaskDeleted($this->getAttributes()));
    }
  }

  public function isComplete() :bool
  {
    return $this->completed_at !== null;
  }

}
