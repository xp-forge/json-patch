<?php namespace text\json\patch\unittest;

use text\json\patch\{Applied, CopyOperation};
use unittest\Assert;
use unittest\Test;

class CopyOperationTest extends OperationTest {

  #[Test]
  public function copying_a_value() {
    $operation= new CopyOperation('/a/b/c', '/a/b/e');

    $value= ['a' => ['b' => ['c' => 'value']]];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
    Assert::equals(['a' => ['b' => ['c' => 'value', 'e' => 'value']]], $value);
  }
}