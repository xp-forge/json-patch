<?php namespace text\json\patch;

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
   * Creates a new add operation
   *
   * @param  [:var] $operation
   */
  public function __construct($operation) {
    parent::__construct($operation);
    $this->value= $this->requires($operation, 'value');
  }

  public function apply(&$target) {
    return $this->path->resolve($target)->add($this->value);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(add '.$this->path().' => '.Objects::stringOf($this->value).')';
  }
}