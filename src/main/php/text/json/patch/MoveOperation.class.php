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
    $this->from= $this->parse($this->requires($operation, 'from'));
  }

  public function apply(&$target) {
    $from= $this->pointer($target, array_slice($this->from, 0, -1));

    $key= $from->address($this->from[sizeof($this->from) - 1]);
    $source= $from->to($key);
    if (!$source->resolves()) return new PathDoesNotExist('/'.implode('/', $this->from));
    $to= $this->pointer($target, array_slice($this->path, 0, -1));
    $address= $to->address($this->path[sizeof($this->path) - 1]);
    if (!$to->resolves() || null === $address) return new PathDoesNotExist($this->path());

    // Remove
    $value= $from->value();
    if (true === $key) {
      return new ArrayIndexOutOfBounds('Cannot remove from "-" array index');
    } else if (is_int($key)) {
      $from->modify(array_merge(array_slice($value, 0, $key), array_slice($value, $key + 1)));
    } else {
      unset($value[$key]);
      $from->modify($value);
    }

    return $this->modify($to, $address, $source->value());
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(move /'.implode('/', $this->from).' -> '.$this->path().')';
  }
}