<?php namespace text\json\patch\unittest;

use text\json\patch\{AddOperation, Applied};
use unittest\Assert;
use unittest\Test;

class AddOperationTest extends OperationTest {

  #[Test]
  public function adding_an_object_member() {
    $operation= new AddOperation('/baz', 'qux');

    $value= ['foo' => 'bar'];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
    Assert::equals(['foo' => 'bar', 'baz' => 'qux'], $value);
  }

  #[Test]
  public function adding_an_array_element() {
    $operation= new AddOperation('/foo/1', 'qux');

    $value= ['foo' => ['bar', 'baz']];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
    Assert::equals(['foo' => ['bar', 'qux', 'baz']], $value);
  }

  #[Test]
  public function adding_to_a_nonexistent_target() {
    $operation= new AddOperation('/baz/bat', 'qux');

    $value= ['foo' => 'bar'];
    Assert::instance('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }

  #[Test]
  public function adding_an_array_value() {
    $operation= new AddOperation('/foo/-', ['abc', 'def']);

    $value= ['foo' => ['bar']];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
    Assert::equals(['foo' => ['bar', ['abc', 'def']]], $value);
  }

  #[Test]
  public function adding_an_array_value_to_an_empty_array() {
    $operation= new AddOperation('/foo/-', 'bar');

    $value= ['foo' => []];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
    Assert::equals(['foo' => ['bar']], $value);
  }

  #[Test]
  public function add_should_replace_existing_object_member() {
    $operation= new AddOperation('/baz', 'qux');

    $value= ['foo' => 'bar', 'baz' => 'bat'];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
    Assert::equals(['foo' => 'bar', 'baz' => 'qux'], $value);
  }

  #[Test]
  public function dash_has_no_special_meaning_for_non_arrays() {
    $operation= new AddOperation('/-', self::CHANGED);

    $value= ['color' => 'green'];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
    Assert::equals(['color' => 'green', '-' => self::CHANGED], $value);
  }
}