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
    if (empty($this->path)) {         // Replace whole document
      return $this->pointer($target, [])->modify($this->value);
    }

    $ptr= $this->pointer($target, array_slice($this->path, 0, -1));

    $address= $ptr->address($this->path[sizeof($this->path) - 1]);
    if (null === $address) return false;

    $value= $ptr->value();
    if (!is_array($value)) return false;

    if (true === $address) {          // Add to array
      $value[]= $this->value;
      return $ptr->modify($value);
    } else if (is_int($address)) {    // Array offset
      if ($address < 0 || $address > sizeof($value)) return false;
      return $ptr->modify(array_merge(array_slice($value, 0, $address), [$this->value], array_slice($value, $address)));
    } else {                          // Object member
      if (0 === key($value)) return false;
      $value[$address]= $this->value;
      return $ptr->modify($value);
    }
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(add '.$this->path().' => '.Objects::stringOf($this->value).')';
  }
}