<?php namespace text\json\patch;

use util\Objects;

/**
 * The "add" operation performs one of the following functions,
 * depending upon what the target location references:
 * 
 * o If the target location specifies an array index, a new value is
 *   inserted into the array at the specified index.
 * 
 * o If the target location specifies an object member that does not
 *   already exist, a new member is added to the object.
 * 
 * o If the target location specifies an object member that does exist,
 *   that member's value is replaced.
 *
 * @test  xp://text.json.patch.unittest.AddOperationTest
 */
class AddOperation extends Operation {
  private $value;

  /**
   * Creates a new test operation
   *
   * @param  string $path The path
   * @param  var $value The value to add
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
    return $this->path->resolve($target)->add($this->value);
  }

  /** @return string */
  public function hashCode() {
    return 'A'.Objects::hashOf($this->path.$this->value);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(add '.$this->path.' => '.Objects::stringOf($this->value).')';
  }

  /**
   * Returns whether a given value is equal to this results instance
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare($this->path, $value->path)
      : 1
    ;
  }
}