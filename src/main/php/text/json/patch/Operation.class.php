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
  const SUCCESS = 1;
  const FAILURE = 0;
  const UNCHANGED = -1;

  private $path;

  /**
   * Creates a new operation
   *
   * @param  [:var] $operation
   * @throws lang.IllegalArgumentException
   */
  public function __construct($operation) {
    $this->requires($operation, 'op');
    $this->path= $this->requires($operation, 'path');
    if ('/' !== $operation['path']{0}) {
      throw new IllegalArgumentException('Malformed path, must start with "/"');
    }
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

  /** @return string */
  public function path() { return $this->path; }

  /** @return string[] */
  public function elements() { return explode('/', substr($this->path, 1)); }

  /**
   * Apply this operation to a given target and return whether the operation was successful.
   *
   * @param  var $value
   * @return bool
   */
  public abstract function apply(&$target);
}