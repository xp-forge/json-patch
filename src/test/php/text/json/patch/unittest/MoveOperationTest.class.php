<?php namespace text\json\patch\unittest;

use text\json\patch\MoveOperation;
use text\json\patch\Applied;

class MoveOperationTest extends OperationTest {

  #[@test]
  public function moving_a_value() {
    $operation= new MoveOperation('/foo/waldo', '/qux/thud');

    $value= ['foo' => ['bar' => 'baz', 'waldo' => 'fred'], 'qux' => ['corge' => 'grault']];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => ['bar' => 'baz'], 'qux' => ['corge' => 'grault', 'thud' => 'fred']], $value);
  }

  #[@test]
  public function moving_an_array_element() {
    $operation= new MoveOperation('/foo/1', '/foo/3');

    $value= ['foo' => ['all', 'grass', 'cows', 'eat']];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => ['all', 'cows', 'eat', 'grass']], $value);
  }

  #[@test]
  public function moving_to_end_of_array() {
    $operation= new MoveOperation('/foo/1', '/foo/-');

    $value= ['foo' => ['all', 'grass', 'cows', 'eat']];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => ['all', 'cows', 'eat', 'grass']], $value);
  }
}