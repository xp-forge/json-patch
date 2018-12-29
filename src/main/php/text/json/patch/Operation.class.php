<?php namespace text\json\patch;

use lang\Value;

/**
 * Operation objects MUST have exactly one "op" member, whose value
 * indicates the operation to perform.  Its value MUST be one of "add",
 * "remove", "replace", "move", "copy", or "test"; other values are
 * errors.  The semantics of each object is defined below.
 *
 * @see   xp://text.json.Operations
 */
abstract class Operation implements Value {
  protected $path;

  /**
   * Creates a new operation
   *
   * @param  [:var] $operation
   */
  public function __construct($path) {
    $this->path= new Pointer($path);
  }

  /** @return text.json.patch.Pointer */
  public function path() { return $this->path; }

  /**
   * Apply this operation to a given target and return result
   *
   * @param  var $target
   * @return text.json.patch.Applied
   */
  public abstract function applyTo(&$target);
}