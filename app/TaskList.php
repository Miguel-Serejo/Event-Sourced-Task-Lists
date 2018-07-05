<?php

namespace App;

use App\Events\TaskListCreated;
use App\Events\TaskListDeleted;

use Ramsey\Uuid\Uuid;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskList extends Model
{
  protected $guarded = [];

  protected $primaryKey = 'uuid';

  protected $keyType = 'uuid';

  public $increments = false;

  public function getRouteKeyName() : string
  {
    return 'uuid';
  }

  public static function createWithAttributes(array $attributes) : void
  {
    $attributes['uuid'] = Uuid::uuid4()->toString();
    event(new TaskListCreated($attributes));
  }

  public function erase() : void
  {
    if ( $this->exists ) {
      event(new TaskListDeleted($this->getAttributes()));
    }
  }

  public function tasks() : HasMany
  {
    return $this->hasMany(Task::class);
  }
}
