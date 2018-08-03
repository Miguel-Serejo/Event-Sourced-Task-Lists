<?php

namespace App\Http\Controllers\History;

use App\Projectors\HistoryProjector;

use Spatie\EventProjector\Facades\Projectionist;

use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setState(int $eventId)
    {
      if ($eventId == 0) {
        return $this->reset();
      }
      $projector = Projectionist::addProjector(HistoryProjector::class)->getProjector(HistoryProjector::class);
      $projector->reset();
      $projector->setTargetEventId($eventId);
      Projectionist::replay(collect([$projector]));
      Session::put('history_current_event', $eventId);
      return redirect('history');
    }

    public function reset()
    {
      $projector = Projectionist::addProjector(HistoryProjector::class)->getProjector(HistoryProjector::class);
      $projector->reset();
      Session::put('history_current_event', 0);

      return redirect('history');
    }
}
