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
    if ($from->exists()) {
      return $this->path->resolve($target)->add($from->value());
    }
    return new PathDoesNotExist($this->from);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(copy '.$this->from.' -> '.$this->path.')';
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