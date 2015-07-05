<?php namespace text\json\patch;

abstract class Applied extends \lang\Object {
  public static $CLEANLY;

  static function __static() {
    self::$CLEANLY= newinstance(self::class, [], '{
      static function __static() { }
      public function isError() { return false; }
    }');
  }

  public abstract function isError();

  public function then($func) {
    return $this->isError() ? $this : $func();
  }
}