<?php namespace text\json\patch\unittest;

use text\json\patch\CopyOperation;
use lang\IllegalArgumentException;

class CopyOperationTest extends OperationTest {

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "op"/')]
  public function missing_op() {
    new CopyOperation([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path() {
    new CopyOperation(['op' => 'copy']);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "from"/')]
  public function missing_from() {
    new CopyOperation(['op' => 'copy', 'path' => '/target']);
  }

  #[@test]
  public function copying_a_value() {
    $operation= new CopyOperation(['op' => 'copy', 'from' => '/a/b/c', 'path' => '/a/b/e']);

    $value= ['a' => ['b' => ['c' => 'value']]];
    $this->assertNull($operation->apply($value));
    $this->assertEquals(['a' => ['b' => ['c' => 'value', 'e' => 'value']]], $value);
  }
}