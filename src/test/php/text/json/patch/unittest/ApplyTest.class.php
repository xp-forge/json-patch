<?php namespace text\json\patch\unittest;

use text\json\patch\Changes;
use text\json\patch\Results;

class ApplyTest extends \unittest\TestCase {
  const ORIGINAL = 42;
  const CHANGED = 6100;

  #[@test]
  public function apply_replacement() {
    $changes= new Changes(['op' => 'replace', 'path' => '/value', 'value' => self::CHANGED]);
    $value= ['value' => self::ORIGINAL];

    $this->assertEquals(['value' => self::CHANGED], $changes->apply($value)->value());
  }

  #[@test]
  public function apply_replacement_if_test_succeeds() {
    $changes= new Changes(
      ['op' => 'test', 'path' => '/value', 'value' => self::ORIGINAL],
      ['op' => 'replace', 'path' => '/value', 'value' => self::CHANGED]
    );
    $value= ['value' => self::ORIGINAL];

    $this->assertEquals(['value' => self::CHANGED], $changes->apply($value)->value());
  }

  #[@test]
  public function apply_replacement_only_if_test_succeeds() {
    $changes= new Changes(
      ['op' => 'test', 'path' => '/non-existant', 'value' => null],
      ['op' => 'replace', 'path' => '/value', 'value' => self::CHANGED]
    );
    $value= ['value' => self::ORIGINAL];

    $this->assertFalse($changes->apply($value)->successful());
  }

  #[@test]
  public function apply_returns_success() {
    $changes= new Changes(['op' => 'test', 'path' => '/value', 'value' => self::ORIGINAL]);
    $value= ['value' => self::ORIGINAL];

    $this->assertEquals(new Results(true, $value), $changes->apply($value));
  }

  #[@test]
  public function apply_returns_failure() {
    $changes= new Changes(['op' => 'test', 'path' => '/non-existant', 'value' => null]);
    $value= ['value' => self::ORIGINAL];

    $this->assertEquals(new Results(false), $changes->apply($value));
  }
}