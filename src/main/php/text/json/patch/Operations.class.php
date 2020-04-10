<?php namespace text\json\patch;

use lang\{Enum, IllegalArgumentException, XPClass};

/**
 * Operations factory
 *
 * @test  xp://text.json.patch.unittest.OperationsTest
 */
abstract class Operations extends Enum {
  public static $ADD, $REMOVE, $REPLACE, $MOVE, $COPY, $TEST;

  static function __static() {
    self::$ADD= new class(1, 'ADD') extends Operations {
      static function __static() { }
      public function newInstance($op) {
        return new AddOperation($this->requires($op, 'path'), $this->requires($op, 'value'));
      }
    };
    self::$REMOVE= new class(2, 'REMOVE') extends Operations {
      static function __static() { }
      public function newInstance($op) {
        return new RemoveOperation($this->requires($op, 'path'));
      }
    };
    self::$REPLACE= new class(3, 'REPLACE') extends Operations {
      static function __static() { }
      public function newInstance($op) {
        return new ReplaceOperation($this->requires($op, 'path'), $this->requires($op, 'value'));
      }
    };
    self::$MOVE= new class(4, 'MOVE') extends Operations {
      static function __static() { }
      public function newInstance($op) {
        return new MoveOperation($this->requires($op, 'from'), $this->requires($op, 'path'));
      }
    };
    self::$COPY= new class(5, 'COPY') extends Operations {
      static function __static() { }
      public function newInstance($op) {
        return new CopyOperation($this->requires($op, 'from'), $this->requires($op, 'path'));
      }
    };
    self::$TEST= new class(6, 'TEST') extends Operations {
      static function __static() { }
      public function newInstance($op) {
        return new TestOperation($this->requires($op, 'path'), $this->requires($op, 'value'));
      }
    };
  }

  /**
   * Verifies a given member exists in the input and returns its value on success.
   *
   * @param  [:var] $op
   * @param  string $member
   * @return var
   * @throws lang.IllegalArgumentException If there is no member
   */
  protected function requires($op, $member) {
    if (!array_key_exists($member, $op)) {
      throw new IllegalArgumentException('Missing member "'.$member.'" in input');
    }
    return $op[$member];
  }

  /**
   * Creates a new operation instance
   *
   * @param  [:var] $op Input as specified in the RFC
   * @return text.json.patch.Operation
   */
  public abstract function newInstance($op);

  /**
   * Returns a operation member by a specified name, case-insensitively
   *
   * @param  string $name
   * @return text.json.patch.Operations
   */
  public static function named($name) {
    return parent::valueOf(new XPClass(self::class), strtoupper($name));
  }
}