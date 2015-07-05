<?php namespace text\json\patch;

use util\Objects;

/**
 * The "replace" operation replaces the value at the target location
 * with a new value. The operation object MUST contain a "value" member
 * whose content specifies the replacement value.
 *
 * @test  xp://text.json.patch.unittest.ReplaceOperationTest
 */
class ReplaceOperation extends Operation {
  private $value;

  /**
   * Creates a new test operation
   *
   * @param  string $path The path
   * @param  var $value The value to replace
   */
  public function __construct($path, $value) {
    parent::__construct($path);
    $this->value= $value;
  }

  /**
   * Apply this operation to a given target reference
   *
   * @param  var $target
   * @return text.json.path.Error
   */
  public function applyTo(&$target) {
    return $this->path->resolve($target)->modify($this->value);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(replace '.$this->path.' => '.Objects::stringOf($this->value).')';
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