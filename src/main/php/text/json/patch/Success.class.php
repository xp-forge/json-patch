<?php namespace text\json\patch;

use util\Objects;

/**
 * Indicates success result
 */
class Success extends Results {
  private $value;

  /**
   * Creates a new success instance
   *
   * @param  var $value
   */
  public function __construct($value) {
    $this->value= $value;
  }

  /** @return bool */
  public function successful() { return true; }

  /** @return var */
  public function value() { return $this->value; }

  /** @return string */
  public function hashCode() {
    return '+'.Objects::hashOf($this->value);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(success  -> '.Objects::stringOf($this->value).')';
  }

  /**
   * Comparison
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self ? Objects::compare($this->value, $value->value) : 1;
  }
}