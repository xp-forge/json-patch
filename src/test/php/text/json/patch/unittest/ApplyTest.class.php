<?php namespace text\json\patch\unittest;

use text\json\patch\{Changes, Failure, PathDoesNotExist, Success};
use test\Assert;
use test\Test;

class ApplyTest {
  const ORIGINAL = 42;
  const CHANGED = 6100;

  #[Test]
  public function apply_replacement() {
    $changes= new Changes(['op' => 'replace', 'path' => '/value', 'value' => self::CHANGED]);
    $value= ['value' => self::ORIGINAL];

    Assert::equals(['value' => self::CHANGED], $changes->apply($value)->value());
  }

  #[Test]
  public function apply_replacement_if_test_succeeds() {
    $changes= new Changes(
      ['op' => 'test', 'path' => '/value', 'value' => self::ORIGINAL],
      ['op' => 'replace', 'path' => '/value', 'value' => self::CHANGED]
    );
    $value= ['value' => self::ORIGINAL];

    Assert::equals(['value' => self::CHANGED], $changes->apply($value)->value());
  }

  #[Test]
  public function apply_replacement_only_if_test_succeeds() {
    $changes= new Changes(
      ['op' => 'test', 'path' => '/non-existant', 'value' => null],
      ['op' => 'replace', 'path' => '/value', 'value' => self::CHANGED]
    );
    $value= ['value' => self::ORIGINAL];

    Assert::false($changes->apply($value)->successful());
  }

  #[Test]
  public function apply_returns_success() {
    $changes= new Changes(['op' => 'test', 'path' => '/value', 'value' => self::ORIGINAL]);
    $value= ['value' => self::ORIGINAL];

    Assert::equals(new Success($value), $changes->apply($value));
  }

  #[Test]
  public function apply_returns_failure() {
    $changes= new Changes(['op' => 'test', 'path' => '/non-existant', 'value' => null]);
    $value= ['value' => self::ORIGINAL];

    Assert::equals(new Failure(new PathDoesNotExist('/non-existant')), $changes->apply($value));
  }
}