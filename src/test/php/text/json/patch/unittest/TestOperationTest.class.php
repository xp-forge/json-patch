<?php namespace text\json\patch\unittest;

use text\json\patch\TestOperation;
use lang\IllegalArgumentException;

class TestOperationTest extends OperationTest {

  #[@test]
  public function returns_success_on_changes() {
    $operation= new TestOperation('/value', self::ORIGINAL);

    $value= ['value' => self::ORIGINAL];
    $this->assertNull($operation->apply($value));
  }

  #[@test]
  public function returns_false_if_path_does_not_exist() {
    $operation= new TestOperation('/does-not-exist', null);

    $value= ['value' => self::ORIGINAL];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', $operation->apply($value));
  }

  #[@test]
  public function comparing_strings_and_numbers() {
    $operation= new TestOperation('/value', '10');

    $value= ['value' => 10];
    $this->assertInstanceOf('text.json.patch.NotEquals', $operation->apply($value));
  }
}