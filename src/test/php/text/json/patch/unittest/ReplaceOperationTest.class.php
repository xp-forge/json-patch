<?php namespace text\json\patch\unittest;

use text\json\patch\ReplaceOperation;
use text\json\patch\Applied;

class ReplaceOperationTest extends OperationTest {

  #[@test]
  public function changes_value() {
    $operation= new ReplaceOperation('/value', self::CHANGED);

    $value= ['value' => self::ORIGINAL];
    $operation->applyTo($value);
    $this->assertEquals(['value' => self::CHANGED], $value);
  }

  #[@test]
  public function returns_success_on_changes() {
    $operation= new ReplaceOperation('/value', self::CHANGED);

    $value= ['value' => self::ORIGINAL];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
  }

  #[@test]
  public function returns_false_if_path_does_not_exist() {
    $operation= new ReplaceOperation('/does-not-exist', self::CHANGED);

    $value= ['value' => self::ORIGINAL];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }

  #[@test]
  public function returns_success_even_when_unchanged() {
    $operation= new ReplaceOperation('/value', self::ORIGINAL);

    $value= ['value' => self::ORIGINAL];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
  }

  #[@test]
  public function can_replace_toplevel() {
    $operation= new ReplaceOperation('', self::CHANGED);

    $value= self::ORIGINAL;
    $operation->applyTo($value);
    $this->assertEquals(self::CHANGED, $value);
  }

  #[@test]
  public function dash_has_no_special_meaning_for_non_arrays() {
    $operation= new ReplaceOperation('/-', self::CHANGED);

    $value= ['-' => self::ORIGINAL];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['-' => self::CHANGED], $value);
  }
}