<?php
namespace App\Traits;

trait Undoable {

  public function undo() {
    $attributes = [];
    foreach ($this->undoAttributes as $attribute) {
      $attributes[] = $this->$attribute;
    }
    $attributes[] = ['undo' => true];
    event(new $this->undoEvent(...$attributes));
  }
}
