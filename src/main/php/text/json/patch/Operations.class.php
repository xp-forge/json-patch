<?php namespace text\json\patch;

use lang\XPClass;

abstract class Operations extends \lang\Enum {
  public static $ADD, $REMOVE, $REPLACE, $MOVE, $COPY, $TEST;

  static function __static() {
    self::$ADD= newinstance(__CLASS__, [1, 'ADD'], '{
      static function __static() { }
      public function newInstance($operation) { return new AddOperation($operation); }
    }');
    self::$REMOVE= newinstance(__CLASS__, [2, 'REMOVE'], '{
      static function __static() { }
      public function newInstance($operation) { return new RemoveOperation($operation); }
    }');
    self::$REPLACE= newinstance(__CLASS__, [3, 'REPLACE'], '{
      static function __static() { }
      public function newInstance($operation) { return new ReplaceOperation($operation); }
    }');
    self::$MOVE= newinstance(__CLASS__, [4, 'MOVE'], '{
      static function __static() { }
      public function newInstance($operation) { return new MoveOperation($operation); }
    }');
    self::$COPY= newinstance(__CLASS__, [5, 'COPY'], '{
      static function __static() { }
      public function newInstance($operation) { return new CopyOperation($operation); }
    }');
    self::$TEST= newinstance(__CLASS__, [6, 'TEST'], '{
      static function __static() { }
      public function newInstance($operation) { return new TestOperation($operation); }
    }');
  }

  /**
   * Creates a new operation instance
   *
   * @param  [:var] $operation Input as specified in the RFC
   * @return text.json.patch.Operation
   */
  public abstract function newInstance($operation);

  /**
   * Returns a operation member by a specified name, case-insensitively
   *
   * @param  string $name
   * @return text.json.patch.Operations
   */
  public static function named($name) {
    return parent::valueOf(new XPClass(__CLASS__), strtoupper($name));
  }
}