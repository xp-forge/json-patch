<?php namespace text\json\patch;

/**
 * The "remove" operation removes the value at the target location.
 * The target location MUST exist for the operation to be successful.
 *
 * @test  xp://text.json.patch.unittest.RemoveOperationTest
 */
class RemoveOperation extends Operation {


  public function apply(&$target) {
    $ptr= $this->pointer($target, array_slice($this->path, 0, -1));
    $key= $this->path[sizeof($this->path) - 1];

    if ($ptr->to($key)->resolves()) {
      $value= $ptr->value();
      if (is_numeric($key)) {
        $pos= (int)$key;
        return $ptr->modify(array_merge(array_slice($value, 0, $pos), array_slice($value, $pos + 1)));
      } else {
        unset($value[$key]);
        return $ptr->modify($value);
      }
    }
    return false;
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(remove '.$this->path().')';
  }
}