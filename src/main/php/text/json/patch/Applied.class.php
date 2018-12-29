<?php namespace text\json\patch;

/**
 * Results from operations' applyTo() method call.
 *
 * @see  xp://text.json.patch.Operation#applyTo() 
 */
abstract class Applied {
  public static $CLEANLY;

  static function __static() {
    self::$CLEANLY= newinstance(self::class, [], '{
      static function __static() { }
      public function isError() { return false; }
    }');
  }

  /** @return bool */
  public abstract function isError();

  /**
   * Executes a given closure if this represents a success.
   *
   * @param  function(): text.json.Applied $closure
   */
  public function then(callable $closure) {
    return $this->isError() ? $this : $closure();
  }
}