<?php namespace text\json\patch;

/**
 * Holds a JSON Patch document
 *
 * @see   http://tools.ietf.org/html/rfc6902
 * @test  xp://text.json.patch.unittest.ChangesTest
 * @test  xp://text.json.patch.unittest.ApplyTest
 */
class Changes extends \lang\Object {
  private $operations= [];

  /**
   * Creates a new changes instance
   *
   * @param  (text.json.patch.Operation|[:var])[] $operations
   */
  public function __construct(...$operations) {
    foreach ($operations as $operation) {
      if ($operation instanceof Operation) {
        $this->operations[]= $operation;
      } else {
        $this->operations[]= Operations::named($operation['op'])->newInstance($operation);
      }
    }
  }

  /** @return text.json.patch.Operation[] */
  public function operations() { return $this->operations; }

  /**
   * Returns the unique paths which will be changed
   *
   * @return string[]
   */
  public function to() {
    $paths= [];
    foreach ($this->operations as $operation) {
      $paths[$operation->path()]= true;
    }
    return array_keys($paths);
  }

  /**
   * Applies this changes to a given value. Stops after first unsuccessfull operation
   *
   * @param  var $value
   * @param  [:bool] A map with paths as keys and whether a change was performed
   */
  public function apply($value) {
    $successful= true;
    foreach ($this->operations as $operation) {
      if ($successful) {
        $successful= $operation->apply($value);
      }
    }
    return new Results($successful, $successful ? $value : null);
  }
}