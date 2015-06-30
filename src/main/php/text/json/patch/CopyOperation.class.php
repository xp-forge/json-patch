<?php namespace text\json\patch;

class CopyOperation extends Operation {

  public function apply(&$target) {
    return false;  // TBI
  }
}