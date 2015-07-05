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
    $address= $this->path->resolve($target);
    if ($address->exists()) {
      $value= $address->value();
      return Objects::equal($value, $this->value) ? null : new NotEquals($value, $this->value);
    } else {
      return new PathDoesNotExist($this->path());
    }
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(test '.$this->path.' == '.Objects::stringOf($this->value).')';
  }
}