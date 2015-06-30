<?php namespace text\json\patch;

class AddOperation extends Operation {

  public function apply(&$target) {
    return false;  // TBI
  }
}