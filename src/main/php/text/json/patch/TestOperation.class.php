<?php namespace text\json\patch;

use util\Objects;

/**
 * The "test" operation tests that a value at the target location is
 * equal to a specified value.
 *
 * @test  xp://text.json.patch.TestOperation
 */
class TestOperation extends Operation {
  private $value;

  /**
   * Creates a new test operation
   *
   * @param  string $path The path
   * @param  var $value The value to compare against
   */
  public function __construct($path, $value) {
    parent::__construct($path);
    $this->value= $value;
  }

  /**
   * Apply this operation to a given target reference
   *
   * @param  var $target
   * @return text.json.path.Error
   */
  public function applyTo(&$target) {
    $address= $this->path->resolve($target);
    if ($address->exists()) {
      $value= $address->value();
      if (is_int($value) && is_double($this->value)) {
        $equal= (double)$value === $this->value;
      } else if (is_double($value) && is_int($this->value)) {
        $equal= $value === (double)$this->value;
      } else {
        $equal= Objects::equal($value, $this->value);
      }
      return $equal ? Applied::$CLEANLY : new NotEquals($value, $this->value);
    } else {
      return new PathDoesNotExist($this->path());
    }
  }

  /** @return string */
  public function toString() {
    return nameof($this).'(test '.$this->path.' == '.Objects::stringOf($this->value).')';
  }

  /**
   * Returns whether a given value is equal to this results instance
   *
   * @param  var $cmp
   * @return bool
   */
  public function equals($cmp) {
    return $cmp instanceof self && Objects::equal($this->value, $cmp->value);
  }
}