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
   * Creates a new test operation
   *
   * @param  string $from The source path
   * @param  string $path The target path
   */
  public function __construct($from, $path) {
    parent::__construct($path);
    $this->from= new Pointer($from);
  }

  /**
   * Apply this operation to a given target reference
   *
   * @param  var $target
   * @return text.json.path.Error
   */
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
    return nameof($this).'(move '.$this->from.' -> '.$this->path.')';
  }

  /**
   * Returns whether a given value is equal to this results instance
   *
   * @param  var $cmp
   * @return bool
   */
  public function equals($cmp) {
    return $cmp instanceof self && $this->path->equals($cmp->path) && $this->from->equals($cmp->from);
  }
}