<?php namespace text\json\patch;

/**
 * The "move" operation removes the value at a specified location and
 * adds it to the target location.
 *
 * The operation object MUST contain a "from" member, which is a string
 * containing a JSON Pointer value that references the location in the
 * target document to move the value from.
 *
 * The "from" location MUST exist for the operation to be successful.
 *
 * @test  xp://text.json.patch.unittest.MoveOperationTest
 */
class MoveOperation extends Operation {
  private $from;

  /**
   * Creates a new replace operation
   *
   * @param  [:var] $operation
   */
  public function __construct($operation) {
    parent::__construct($operation);
    $this->from= new Pointer($this->requires($operation, 'from'));
  }

  public function apply(&$target) {
    $from= $this->from->resolve($target);
    $to= $this->path->resolve($target);
    $value= $from->value();

    $error= $from->remove();
    if (null === $error) {
      return $to->add($value);  
    } else {
      return $error;
    }
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(move /'.$this->from.' -> '.$this->path.')';
  }
}