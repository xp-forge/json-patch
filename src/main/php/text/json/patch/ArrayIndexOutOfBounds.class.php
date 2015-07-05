<?php namespace text\json\patch;

class ArrayIndexOutOfBounds extends Error {
  private $index;

  /**
   * Creates a new "array index out of bounds" error
   *
   * @param  int $index
   */
  public function __construct($index) {
    $this->index= $index;
  }

  /** @return string */
  public function message() { return 'Array index '.$this->index.' out of bounds'; }
}