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
   * Creates a new replace operation
   *
   * @param  [:var] $operation
   */
  public function __construct($operation) {
    parent::__construct($operation);
    $this->value= $this->requires($operation, 'value');
  }

  public function apply(&$target) {
    return $this->path->resolve($target)->modify($this->value);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(replace '.$this->path.' => '.Objects::stringOf($this->value).')';
  }
}