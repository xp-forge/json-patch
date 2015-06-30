<?php namespace text\json\patch;

class RemoveOperation extends Operation {

  public function apply(&$target) {
    return false;  // TBI
  }
}