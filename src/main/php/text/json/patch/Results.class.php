<?php namespace text\json\patch;

use util\Objects;

/**
 * Holds results from `Changes::apply()`.
 */
class Results extends \lang\Object {
  private $successful, $value;

  /**
   * Creates a new results instance
   *
   * @param  bool $successful
   * @param  var $value
   */
  public function __construct($successful, $value= null) {
    $this->successful= $successful;
    $this->value= $value;
  }

  /** @return bool */
  public function successful() { return $this->successful; }

  /** @return var */
  public function value() { return $this->value; }

  /** @return string */
  public function toString() {
    return nameof($this).'('.($this->successful ? 'success -> '.Objects::stringOf($this->value) : 'failure').')';
  }

  /**
   * Returns whether a given value is equal to this results instance
   *
   * @param  var $cmp
   * @return bool
   */
  public function equals($cmp) {
    return $cmp instanceof self && $this->successful === $cmp->successful && Objects::equal($this->value, $cmp->value);
  }
}