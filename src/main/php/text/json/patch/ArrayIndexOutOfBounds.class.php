<?php namespace text\json\patch;

class ArrayIndexOutOfBounds extends Error {
  private $index;

  public function __construct($index) {
    $this->index= $index;
  }

  public function message() { return 'Array index '.$this->index.' out of bounds'; }
}