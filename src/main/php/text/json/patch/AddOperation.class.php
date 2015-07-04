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
    $ptr= $this->pointer($target, array_slice($this->path, 0, -1));
    $key= $this->path[sizeof($this->path) - 1];
    $value= $ptr->value();

    if ('-' === $key) {
      $value[]= $this->value;
      return $ptr->modify($value);
    } else if (is_numeric($key)) {
      $pos= (int)$key;
      return $ptr->modify(array_merge(array_slice($value, 0, $pos), [$this->value], array_slice($value, $pos)));
    } else {
      $value[$key]= $this->value;
      return $ptr->modify($value);
    }
  }
}