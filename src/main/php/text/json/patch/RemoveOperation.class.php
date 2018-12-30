<?php namespace text\json\patch;

use util\Objects;

/**
 * The "remove" operation removes the value at the target location.
 * The target location MUST exist for the operation to be successful.
 *
 * @test  xp://text.json.patch.unittest.RemoveOperationTest
 */
class RemoveOperation extends Operation {

  /**
   * Apply this operation to a given target reference
   *
   * @param  var $target
   * @return text.json.path.Error
   */
  public function applyTo(&$target) {
    return $this->path->resolve($target)->remove();
  }

  /** @return string */
  public function hashCode() {
    return 'R'.Objects::hashOf($this->path);
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(remove '.$this->path.')';
  }

  /**
   * Returns whether a given value is equal to this results instance
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare($this->path, $value->path)
      : 1
    ;
  }
}