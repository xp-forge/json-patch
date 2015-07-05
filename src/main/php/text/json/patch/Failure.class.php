<?php namespace text\json\patch;

use util\Objects;

/**
 * Indicates failure result
 */
class Failure extends Results {
  private $error;

  /**
   * Creates a new success instance
   *
   * @param  var $error
   */
  public function __construct($error) {
    $this->error= $error;
  }

  /** @return bool */
  public function successful() { return false; }

  /** @return var */
  public function error() { return $this->error; }

  /** @return string */
  public function toString() {
    return nameof($this).'(failure  -> '.$this->error->message().')';
  }

  /**
   * Returns whether a given error is equal to this results instance
   *
   * @param  var $cmp
   * @return bool
   */
  public function equals($cmp) {
    return $cmp instanceof self && $this->error->equals($cmp->error);
  }
}