<?php namespace text\json\patch\unittest;

use text\json\patch\AddOperation;
use text\json\patch\Operation;
use lang\IllegalArgumentException;

class AddOperationTest extends OperationTest {

  #[@test]
  public function adding_an_object_member() {
    $operation= new AddOperation('/baz', 'qux');

    $value= ['foo' => 'bar'];
    $this->assertNull($operation->applyTo($value));
    $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $value);
  }

  #[@test]
  public function adding_an_array_element() {
    $operation= new AddOperation('/foo/1', 'qux');

    $value= ['foo' => ['bar', 'baz']];
    $this->assertNull($operation->applyTo($value));
    $this->assertEquals(['foo' => ['bar', 'qux', 'baz']], $value);
  }

  #[@test]
  public function adding_to_a_nonexistent_target() {
    $operation= new AddOperation('/baz/bat', 'qux');

    $value= ['foo' => 'bar'];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }

  #[@test]
  public function adding_an_array_value() {
    $operation= new AddOperation('/foo/-', ['abc', 'def']);

    $value= ['foo' => ['bar']];
    $this->assertNull($operation->applyTo($value));
    $this->assertEquals(['foo' => ['bar', ['abc', 'def']]], $value);
  }
}