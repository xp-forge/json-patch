<?php namespace text\json\patch\unittest;

use text\json\patch\ReplaceOperation;
use lang\IllegalArgumentException;

class ReplaceOperationTest extends OperationTest {

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "op"/')]
  public function missing_op() {
    new ReplaceOperation([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path() {
    new ReplaceOperation(['op' => 'replace']);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "value"/')]
  public function missing_value() {
    new ReplaceOperation(['op' => 'replace', 'path' => '/value']);
  }

  #[@test]
  public function changes_value() {
    $operation= new ReplaceOperation(['op' => 'replace', 'path' => '/value', 'value' => self::CHANGED]);

    $value= ['value' => self::ORIGINAL];
    $operation->apply($value);
    $this->assertEquals(['value' => self::CHANGED], $value);
  }

  #[@test]
  public function returns_success_on_changes() {
    $operation= new ReplaceOperation(['op' => 'replace', 'path' => '/value', 'value' => self::CHANGED]);

    $value= ['value' => self::ORIGINAL];
    $this->assertNull($operation->apply($value));
  }

  #[@test]
  public function returns_false_if_path_does_not_exist() {
    $operation= new ReplaceOperation(['op' => 'replace', 'path' => '/does-not-exist', 'value' => self::CHANGED]);

    $value= ['value' => self::ORIGINAL];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', $operation->apply($value));
  }

  #[@test]
  public function returns_success_even_when_unchanged() {
    $operation= new ReplaceOperation(['op' => 'replace', 'path' => '/value', 'value' => self::ORIGINAL]);

    $value= ['value' => self::ORIGINAL];
    $this->assertNull($operation->apply($value));
  }
}