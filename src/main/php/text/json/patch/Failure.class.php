<?php namespace text\json\patch;

/**
 * Indicates failure result
 */
class Failure extends Results {
  private $error;

  /**
   * Creates a new success instance
   *
   * @param  text.json.patch.Error $error
   */
  public function __construct($error) {
    $this->error= $error;
  }

  /** @return bool */
  public function successful() { return false; }

  /** @return text.json.patch.Error */
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