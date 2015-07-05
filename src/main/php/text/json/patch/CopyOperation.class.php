<?php namespace text\json\patch;

/**
 * The "copy" operation copies the value at a specified location to the
 * target location.
 *
 * The operation object MUST contain a "from" member, which is a string
 * containing a JSON Pointer value that references the location in the
 * target document to move the value from.
 *
 * The "from" location MUST exist for the operation to be successful.
 *
 * @test  xp://text.json.patch.unittest.CopyOperationTest
 */
class CopyOperation extends Operation {
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
    if ($from->exists()) {
      return $this->path->resolve($target)->add($from->value());
    }
    return new PathDoesNotExist($this->from);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(copy '.$this->from.' -> '.$this->path.')';
  }
}