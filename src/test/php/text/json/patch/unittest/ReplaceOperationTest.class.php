<?php namespace text\json\patch\unittest;

use text\json\patch\{Applied, ReplaceOperation};
use unittest\Assert;
use unittest\Test;

class ReplaceOperationTest extends OperationTest {

  #[Test]
  public function changes_value() {
    $operation= new ReplaceOperation('/value', self::CHANGED);

    $value= ['value' => self::ORIGINAL];
    $operation->applyTo($value);
    Assert::equals(['value' => self::CHANGED], $value);
  }

  #[Test]
  public function returns_success_on_changes() {
    $operation= new ReplaceOperation('/value', self::CHANGED);

    $value= ['value' => self::ORIGINAL];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
  }

  #[Test]
  public function returns_false_if_path_does_not_exist() {
    $operation= new ReplaceOperation('/does-not-exist', self::CHANGED);

    $value= ['value' => self::ORIGINAL];
    Assert::instance('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }

  #[Test]
  public function returns_success_even_when_unchanged() {
    $operation= new ReplaceOperation('/value', self::ORIGINAL);

    $value= ['value' => self::ORIGINAL];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
  }

  #[Test]
  public function can_replace_toplevel() {
    $operation= new ReplaceOperation('', self::CHANGED);

    $value= self::ORIGINAL;
    $operation->applyTo($value);
    Assert::equals(self::CHANGED, $value);
  }

  #[Test]
  public function dash_has_no_special_meaning_for_non_arrays() {
    $operation= new ReplaceOperation('/-', self::CHANGED);

    $value= ['-' => self::ORIGINAL];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
    Assert::equals(['-' => self::CHANGED], $value);
  }
}