<?php namespace text\json\patch;

use util\Objects;

/**
 * The "test" operation tests that a value at the target location is
 * equal to a specified value.
 *
 * @test  xp://text.json.patch.TestOperation
 */
class TestOperation extends Operation {
  private $value;

  /**
   * Creates a new test operation
   *
   * @param  [:var] $operation
   */
  public function __construct($operation) {
    parent::__construct($operation);
    $this->value= $this->requires($operation, 'value');
  }

  public function apply(&$target) {
    $ptr= $this->pointer($target, $this->path);
    return $ptr->resolves() && Objects::equal($ptr->value(), $this->value);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'('.$this->path().' == '.Objects::stringOf($this->value).')';
  }
}