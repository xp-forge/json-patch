<?php namespace text\json\patch\unittest;

use text\json\patch\TestOperation;
use lang\IllegalArgumentException;

class TestOperationTest extends OperationTest {

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "op"/')]
  public function missing_op() {
    new TestOperation([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path() {
    new TestOperation(['op' => 'replace']);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "value"/')]
  public function missing_value() {
    new TestOperation(['op' => 'replace', 'path' => '/value']);
  }

  #[@test]
  public function returns_success_on_changes() {
    $operation= new TestOperation(['op' => 'test', 'path' => '/value', 'value' => self::ORIGINAL]);

    $value= ['value' => self::ORIGINAL];
    $this->assertTrue($operation->apply($value));
  }

  #[@test]
  public function returns_false_if_path_does_not_exist() {
    $operation= new TestOperation(['op' => 'replace', 'path' => '/does-not-exist', 'value' => null]);

    $value= ['value' => self::ORIGINAL];
    $this->assertFalse($operation->apply($value));
  }

  #[@test]
  public function comparing_strings_and_numbers() {
    $operation= new TestOperation(['op' => 'replace', 'path' => '/value', 'value' => '10']);

    $value= ['value' => 10];
    $this->assertFalse($operation->apply($value));
  }
}