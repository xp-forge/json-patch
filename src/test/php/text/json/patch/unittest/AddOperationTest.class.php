<?php namespace text\json\patch\unittest;

use text\json\patch\{AddOperation, Applied};

class AddOperationTest extends OperationTest {

  #[@test]
  public function adding_an_object_member() {
    $operation= new AddOperation('/baz', 'qux');

    $value= ['foo' => 'bar'];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $value);
  }

  #[@test]
  public function adding_an_array_element() {
    $operation= new AddOperation('/foo/1', 'qux');

    $value= ['foo' => ['bar', 'baz']];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => ['bar', 'qux', 'baz']], $value);
  }

  #[@test]
  public function adding_to_a_nonexistent_target() {
    $operation= new AddOperation('/baz/bat', 'qux');

    $value= ['foo' => 'bar'];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }

  #[@test]
  public function adding_an_array_value() {
    $operation= new AddOperation('/foo/-', ['abc', 'def']);

    $value= ['foo' => ['bar']];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => ['bar', ['abc', 'def']]], $value);
  }

  #[@test]
  public function adding_an_array_value_to_an_empty_array() {
    $operation= new AddOperation('/foo/-', 'bar');

    $value= ['foo' => []];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => ['bar']], $value);
  }

  #[@test]
  public function add_should_replace_existing_object_member() {
    $operation= new AddOperation('/baz', 'qux');

    $value= ['foo' => 'bar', 'baz' => 'bat'];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $value);
  }

  #[@test]
  public function dash_has_no_special_meaning_for_non_arrays() {
    $operation= new AddOperation('/-', self::CHANGED);

    $value= ['color' => 'green'];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['color' => 'green', '-' => self::CHANGED], $value);
  }
}