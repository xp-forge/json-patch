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
  public function toString() {
    return nameof($this).'(success  -> '.Objects::stringOf($this->value).')';
  }

  /**
   * Returns whether a given value is equal to this results instance
   *
   * @param  var $cmp
   * @return bool
   */
  public function equals($cmp) {
    return $cmp instanceof self && Objects::equal($this->value, $cmp->value);
  }
}