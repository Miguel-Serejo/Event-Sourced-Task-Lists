<?php
namespace App\Events;

use Spatie\EventProjector\ShouldBeStored;

use Spatie\EventProjector\Models\StoredEvent as BaseEvent;

use Spatie\EventProjector\EventSerializers\EventSerializer;

class StoredEvent extends BaseEvent
{
  public static function createForEvent(ShouldBeStored $event): BaseEvent
  {
      $storedEvent = new static();
      $storedEvent->event_class = get_class($event);
      $storedEvent->meta_data = $event->meta_data;
      unset($event->meta_data);
      $storedEvent->attributes['event_properties'] = app(EventSerializer::class)->serialize(clone $event);
      $event->meta_data = $storedEvent->meta_data;
      $storedEvent->created_at = now();

      $storedEvent->save();

      return $storedEvent;
  }
}
