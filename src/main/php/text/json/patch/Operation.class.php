<?php namespace text\json\patch;

use lang\IllegalArgumentException;

/**
 * Operation objects MUST have exactly one "op" member, whose value
 * indicates the operation to perform.  Its value MUST be one of "add",
 * "remove", "replace", "move", "copy", or "test"; other values are
 * errors.  The semantics of each object is defined below.
 *
 * @see   xp://text.json.Operations
 */
abstract class Operation extends \lang\Object {
  protected $path;

  /**
   * Creates a new operation
   *
   * @param  [:var] $operation
   * @throws lang.IllegalArgumentException
   */
  public function __construct($operation) {
    $this->requires($operation, 'op');
    $this->path= $this->parse($this->requires($operation, 'path'));
  }

  /** @return string */
  public function path() { return '/'.implode('/', $this->path); }

  /**
   * Parses a path
   *
   * @param  string $path
   * @return string[]
   * @throws lang.IllegalArgumentException If the path does not start with "/"
   */
  protected function parse($path) {
    if ('' === $path) {
      return [];
    } else if (1 === strspn($path, '/')) {
      return explode('/', substr($path, 1));
    }

    throw new IllegalArgumentException('Malformed path, must either be empty or start with "/"');
  }

  /**
   * Verifies a given member exists in the input and returns its value on success.
   *
   * @param  [:var] $operation
   * @param  string $member
   * @return var
   * @throws lang.IllegalArgumentException If there is no member
   */
  protected function requires($operation, $member) {
    if (!array_key_exists($member, $operation)) {
      throw new IllegalArgumentException('Missing member "'.$member.'" in input');
    }
    return $operation[$member];
  }

  /**
   * Returns a pointer
   *
   * @param  var $value
   * @param  string[] $path
   * @return text.json.patch.Pointer
   */
  protected function pointer(&$value, $path) {
    $pointer= new Pointer($value);
    foreach ($path as $element) {
      $pointer= $pointer->to($element);
    }
    return $pointer;
  }

  /**
   * Apply this operation to a given target and return whether the operation was successful.
   *
   * @param  var $value
   * @return string Any errors that occurred
   */
  public abstract function apply(&$target);
}