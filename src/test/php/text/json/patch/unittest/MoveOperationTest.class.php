<?php namespace text\json\patch\unittest;

use text\json\patch\MoveOperation;
use lang\IllegalArgumentException;

class MoveOperationTest extends OperationTest {

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "op"/')]
  public function missing_op() {
    new MoveOperation([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path() {
    new MoveOperation(['op' => 'move']);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "from"/')]
  public function missing_from() {
    new MoveOperation(['op' => 'move', 'path' => '/target']);
  }

  #[@test]
  public function moving_a_value() {
    $operation= new MoveOperation(['op' => 'move', 'from' => '/foo/waldo', 'path' => '/qux/thud']);

    $value= ['foo' => ['bar' => 'baz', 'waldo' => 'fred'], 'qux' => ['corge' => 'grault']];
    $this->assertTrue($operation->apply($value));
    $this->assertEquals(['foo' => ['bar' => 'baz'], 'qux' => ['corge' => 'grault', 'thud' => 'fred']], $value);
  }

  #[@test]
  public function moving_an_array_element() {
    $operation= new MoveOperation(['op' => 'move', 'from' => '/foo/1', 'path' => '/foo/3']);

    $value= ['foo' => ['all', 'grass', 'cows', 'eat']];
    $this->assertTrue($operation->apply($value));
    $this->assertEquals(['foo' => ['all', 'cows', 'eat', 'grass']], $value);
  }
}