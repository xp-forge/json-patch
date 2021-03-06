<?php namespace text\json\patch;

use util\Objects;

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
   * Creates a new test operation
   *
   * @param  string $from The source path
   * @param  string $to The target path
   */
  public function __construct($from, $to) {
    parent::__construct($to);
    $this->from= new Pointer($from);
  }

  /**
   * Apply this operation to a given target reference
   *
   * @param  var $target
   * @return text.json.path.Error
   */
  public function applyTo(&$target) {
    $from= $this->from->resolve($target);
    $to= $this->path->resolve($target);
    $value= $from->value();

    return $from->remove()->then(function() use($to, $value) { return $to->add($value); });
  }

  /** @return string */
  public function hashCode() {
    return 'M'.Objects::hashOf($this->path.$this->value);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(move '.$this->from.' -> '.$this->path.')';
  }

  /**
   * Returns whether a given value is equal to this results instance
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare([$this->path, $this->from], [$value->path, $value->from])
      : 1
    ;
  }
}