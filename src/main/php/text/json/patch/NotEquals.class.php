<?php namespace text\json\patch;

use util\Objects;

class NotEquals extends Error {
  private $target, $comparison;

  public function __construct($target, $comparison) {
    $this->target= $target;
    $this->comparison= $comparison;
  }

  /** @return string */
  public function message() {
    return Objects::stringOf($this->target).' is not equal to '.Objects::stringOf($this->comparison);
  }
}