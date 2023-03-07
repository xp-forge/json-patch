<?php namespace text\json\patch\unittest;

use text\json\patch\{Applied, TestOperation};
use test\{Assert, Test, Values};

class TestOperationTest extends OperationTest {

  #[Test]
  public function returns_success_on_changes() {
    $operation= new TestOperation('/value', self::ORIGINAL);

    $value= ['value' => self::ORIGINAL];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
  }

  #[Test]
  public function returns_false_if_path_does_not_exist() {
    $operation= new TestOperation('/does-not-exist', null);

    $value= ['value' => self::ORIGINAL];
    Assert::instance('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }

  #[Test]
  public function comparing_strings_and_numbers() {
    $operation= new TestOperation('/value', '10');

    $value= ['value' => 10];
    Assert::instance('text.json.patch.NotEquals', $operation->applyTo($value));
  }

  #[Test, Values([1, -1, 0.5, null, '', false, true])]
  public function test_with_toplevel($value) {
    $operation= new TestOperation('', $value);
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
  }

  #[Test, Values([[2, 2.0], [2.0, 2]])]
  public function numerically_equal($value, $compare) {
    $operation= new TestOperation('', $compare);
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
  }

  #[Test, Values([[['test' => true, 'color' => 'green']], [['color' => 'green', 'test' => true]]])]
  public function comparing_objects($compare) {
    $operation= new TestOperation('', $compare);

    $value= ['color' => 'green', 'test' => true];
    Assert::equals(Applied::$CLEANLY, $operation->applyTo($value));
  }

  #[Test, Values([['', false], [false, ''], [0, false], [false, 0], [0.0, false], [false, 0.0], [[], false], [false, []], [null, false], [false, null]])]
  public function falsy_values_do_not_compare($value, $compare) {
    $operation= new TestOperation('', $compare);
    Assert::instance('text.json.patch.NotEquals', $operation->applyTo($value));
  }

}