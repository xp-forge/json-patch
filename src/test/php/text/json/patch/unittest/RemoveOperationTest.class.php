<?php namespace text\json\patch\unittest;

use text\json\patch\RemoveOperation;
use lang\IllegalArgumentException;

class RemoveOperationTest extends OperationTest {

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "op"/')]
  public function missing_op() {
    new RemoveOperation([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path() {
    new RemoveOperation(['op' => 'remove']);
  }

  #[@test]
  public function removing_an_object_member() {
    $operation= new RemoveOperation(['op' => 'remove', 'path' => '/baz']);

    $value= ['baz' => 'qux', 'foo' => 'bar'];
    $this->assertTrue($operation->apply($value));
    $this->assertEquals(['foo' => 'bar'], $value);
  }

  #[@test]
  public function removing_an_array_element() {
    $operation= new RemoveOperation(['op' => 'remove', 'path' => '/foo/1']);

    $value= ['foo' => ['bar', 'qux', 'baz']];
    $this->assertTrue($operation->apply($value));
    $this->assertEquals(['foo' => ['bar', 'baz']], $value);
  }

  #[@test]
  public function removing_non_existant_object_member_fails() {
    $operation= new RemoveOperation(['op' => 'remove', 'path' => '/baz']);

    $value= ['foo' => 'bar'];
    $this->assertFalse($operation->apply($value));
  }

  #[@test]
  public function removing_non_existant_array_index_fails() {
    $operation= new RemoveOperation(['op' => 'remove', 'path' => '/foo/1']);

    $value= ['foo' => ['bar']];
    $this->assertFalse($operation->apply($value));
  }
}