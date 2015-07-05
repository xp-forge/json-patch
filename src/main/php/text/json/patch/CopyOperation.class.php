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
    $this->from= $this->parse($this->requires($operation, 'from'));
  }

  public function apply(&$target) {
    $source= $this->pointer($target, $this->from);
    if (!$source->resolves()) return new PathDoesNotExist('/'.implode('/', $this->from));

    $ptr= $this->pointer($target, array_slice($this->path, 0, -1));
    $address= $ptr->address($this->path[sizeof($this->path) - 1]);
    return $this->modify($ptr, $address, $source->value());
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(copy /'.implode('/', $this->from).' -> '.$this->path().')';
  }
}