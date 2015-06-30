<?php namespace text\json\patch;

class MoveOperation extends Operation {

  public function apply(&$target) {
    return false;  // TBI
  }
}