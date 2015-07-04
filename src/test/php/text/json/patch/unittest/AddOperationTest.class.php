<?php namespace text\json\patch\unittest;

use text\json\patch\AddOperation;
use text\json\patch\Operation;
use lang\IllegalArgumentException;

class AddOperationTest extends OperationTest {

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "op"/')]
  public function missing_op() {
    new AddOperation([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path() {
    new AddOperation(['op' => 'add']);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "value"/')]
  public function missing_value() {
    new AddOperation(['op' => 'add', 'path' => '/value']);
  }

  #[@test]
  public function adding_an_object_member() {
    $operation= new AddOperation(['op' => 'add', 'path' => '/baz', 'value' => 'qux']);

    $value= ['foo' => 'bar'];
    $this->assertTrue($operation->apply($value));
    $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $value);
  }

  #[@test]
  public function adding_an_array_element() {
    $operation= new AddOperation(['op' => 'add', 'path' => '/foo/1', 'value' => 'qux']);

    $value= ['foo' => ['bar', 'baz']];
    $this->assertTrue($operation->apply($value));
    $this->assertEquals(['foo' => ['bar', 'qux', 'baz']], $value);
  }

  #[@test]
  public function adding_to_a_nonexistent_target() {
    $operation= new AddOperation(['op' => 'add', 'path' => '/baz/bat', 'value' => 'qux']);

    $value= ['foo' => 'bar'];
    $this->assertFalse($operation->apply($value));
  }

  #[@test]
  public function adding_an_array_value() {
    $operation= new AddOperation(['op' => 'add', 'path' => '/foo/-', 'value' => ['abc', 'def']]);

    $value= ['foo' => ['bar']];
    $this->assertTrue($operation->apply($value));
    $this->assertEquals(['foo' => ['bar', ['abc', 'def']]], $value);
  }
}