<?php

namespace App\Projectors;

use App\Task;
use App\TaskList;
use App\Milestone;
use App\Statistic;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskListCreated;
use App\Events\TaskListDeleted;
use App\Events\MilestoneAchieved;
use App\Events\TaskMarkedComplete;

use Illuminate\Support\Carbon;

use Spatie\EventProjector\Models\StoredEvent;

use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

use Illuminate\Support\Facades\Session;

class HistoryProjector implements Projector
{
  use ProjectsEvents;

  protected $targetEventId;

  protected $handlesEvents = [
    TaskCreated::class,
    TaskDeleted::class,
    TaskListCreated::class,
    TaskListDeleted::class,
    TaskMarkedComplete::class,
    MilestoneAchieved::class,
  ];

  /**
  * @return mixed
  */
  public function getTargetEventId()
  {
    return $this->targetEventId;
  }

  /**
  * @param mixed $targetEventId
  *
  * @return static
  */
  public function setTargetEventId($targetEventId)
  {
    $this->targetEventId = $targetEventId;
    return $this;
  }

  public function resetState()
  {
    Session::forget(['tasks', 'milestones', 'statistics', 'taskLists']);
  }


  public function onTaskCreated(StoredEvent $storedEvent) : void
  {
    if ($this->getTargetEventId() < $storedEvent->id) {
      return;
    }
    $tasks = Session::get('tasks', collect());

    $newTask = new Task([
      'title' => $storedEvent->event->taskAttributes['title'],
      'comment' => $storedEvent->event->taskAttributes['comment'],
      'uuid' => $storedEvent->event->taskAttributes['uuid'],
      'task_list_uuid' => $storedEvent->event->taskAttributes['task_list_uuid'],
    ]);

    $tasks->push($newTask);

    Session::put('tasks', $tasks);

    $this->incrementStatistic('Tasks Created');
    $this->incrementStatistic('Active Tasks');
  }

  public function onTaskDeleted(StoredEvent $storedEvent) : void
  {
    if ($this->getTargetEventId() < $storedEvent->id) {
      return;
    }
    $tasks = Session::get('tasks', collect());
    $tasks = $tasks->reject(function ($item) use ($storedEvent){
      return $item->uuid == $storedEvent->event->taskAttributes['uuid'];
    });
    Session::put('tasks', $tasks);

    $this->incrementStatistic('Tasks Deleted');
    $this->decrementStatistic('Active Tasks');
  }

  public function onTaskListCreated(StoredEvent $storedEvent) : void
  {
    if ($this->getTargetEventId() < $storedEvent->id) {
      return;
    }
    $taskLists = Session::get('taskLists', collect());

    $newTaskList = new TaskList([
      'name' => $storedEvent->event->taskListAttributes['name'],
      'uuid' => $storedEvent->event->taskListAttributes['uuid'],
    ]);

    $taskLists->push($newTaskList);

    Session::put('taskLists', $taskLists);
    $this->incrementStatistic('Task Lists Created');
    $this->incrementStatistic('Active Task Lists');
  }

  public function onTaskListDeleted(StoredEvent $storedEvent) : void
  {
    if ($this->getTargetEventId() < $storedEvent->id) {
      return;
    }
    $taskLists = Session::get('taskLists', collect());
    $taskLists = $taskLists->reject(function ($item) use ($storedEvent){
      return $item->uuid == $storedEvent->event->taskListAttributes['uuid'];
    });
    Session::put('taskLists', $taskLists);
    $this->incrementStatistic('Task Lists Deleted');
    $this->decrementStatistic('Active Task Lists');
  }

  public function onTaskMarkedComplete(StoredEvent $storedEvent) : void
  {
    if ($this->getTargetEventId() < $storedEvent->id) {
      return;
    }
    $tasks = Session::get('tasks', collect());
    $task = $tasks->where('uuid', $storedEvent->event->taskAttributes['uuid'])->first();
    $task->completed_at = $storedEvent->event->taskAttributes['completed_at'];

    Session::put('tasks', $tasks);

    $this->incrementStatistic('Tasks Completed');
  }

  public function onMilestoneAchieved(StoredEvent $storedEvent) : void
  {
    if ($this->getTargetEventId() < $storedEvent->id) {
      return;
    }
    $milestones = Session::get('milestones', collect());

    $milestone = new Milestone([
      'text' => $storedEvent->event->milestoneAttributes['text'],
      'timestamp' => Carbon::createFromTimestamp($storedEvent->event->milestoneAttributes['timestamp']),
    ]);
    $milestones->push($milestone);

    Session::put('milestones', $milestones);

    $this->incrementStatistic('Milestones Achieved');
  }

  private function incrementStatistic(string $name, int $value = 1) : void
  {
    $statistics = Session::get('statistics', collect());
    $statistic = $statistics->where('name', $name)->first();
    if ($statistic == null) {
      $statistic = new Statistic([
        'name' => $name,
        'value' => 0,
      ]);
      $statistics->push($statistic);
    }
    $statistic->value += $value;
    Session::put('statistics', $statistics);
  }

  private function decrementStatistic(string $name, int $value = 1) : void
  {
    $this->incrementStatistic($name, $value * -1);
  }
}
