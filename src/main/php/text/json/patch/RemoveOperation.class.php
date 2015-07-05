<?php namespace text\json\patch;

/**
 * The "remove" operation removes the value at the target location.
 * The target location MUST exist for the operation to be successful.
 *
 * @test  xp://text.json.patch.unittest.RemoveOperationTest
 */
class RemoveOperation extends Operation {

  public function apply(&$target) {
    return $this->path->resolve($target)->remove();
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(remove '.$this->path.')';
  }
}