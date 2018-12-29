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
  public function hashCode() {
    return '-'.Objects::hashOf($this->value);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(failure  -> '.$this->error->message().')';
  }

  /**
   * Comparison
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self ? Objects::compare($this->error, $value->error) : 1;
  }
}