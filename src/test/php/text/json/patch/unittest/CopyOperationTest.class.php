<?php namespace text\json\patch\unittest;

use text\json\patch\CopyOperation;
use text\json\patch\Applied;

class CopyOperationTest extends OperationTest {

  #[@test]
  public function copying_a_value() {
    $operation= new CopyOperation('/a/b/c', '/a/b/e');

    $value= ['a' => ['b' => ['c' => 'value']]];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['a' => ['b' => ['c' => 'value', 'e' => 'value']]], $value);
  }
}