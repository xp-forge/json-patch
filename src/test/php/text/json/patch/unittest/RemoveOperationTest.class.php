<?php namespace text\json\patch\unittest;

use text\json\patch\RemoveOperation;
use lang\IllegalArgumentException;

class RemoveOperationTest extends OperationTest {

  #[@test]
  public function removing_an_object_member() {
    $operation= new RemoveOperation('/baz');

    $value= ['baz' => 'qux', 'foo' => 'bar'];
    $this->assertNull($operation->applyTo($value));
    $this->assertEquals(['foo' => 'bar'], $value);
  }

  #[@test]
  public function removing_an_array_element() {
    $operation= new RemoveOperation('/foo/1');

    $value= ['foo' => ['bar', 'qux', 'baz']];
    $this->assertNull($operation->applyTo($value));
    $this->assertEquals(['foo' => ['bar', 'baz']], $value);
  }

  #[@test]
  public function removing_non_existant_object_member_fails() {
    $operation= new RemoveOperation('/baz');

    $value= ['foo' => 'bar'];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }

  #[@test]
  public function removing_non_existant_array_index_fails() {
    $operation= new RemoveOperation('/foo/1');

    $value= ['foo' => ['bar']];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }
}