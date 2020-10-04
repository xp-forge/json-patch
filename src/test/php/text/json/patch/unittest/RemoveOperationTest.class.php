<?php namespace text\json\patch\unittest;

use text\json\patch\{Applied, RemoveOperation};
use unittest\Test;

class RemoveOperationTest extends OperationTest {

  #[Test]
  public function removing_an_object_member() {
    $operation= new RemoveOperation('/baz');

    $value= ['baz' => 'qux', 'foo' => 'bar'];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => 'bar'], $value);
  }

  #[Test]
  public function removing_an_array_element() {
    $operation= new RemoveOperation('/foo/1');

    $value= ['foo' => ['bar', 'qux', 'baz']];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals(['foo' => ['bar', 'baz']], $value);
  }

  #[Test]
  public function removing_non_existant_object_member_fails() {
    $operation= new RemoveOperation('/baz');

    $value= ['foo' => 'bar'];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }

  #[Test]
  public function removing_non_existant_array_index_fails() {
    $operation= new RemoveOperation('/foo/1');

    $value= ['foo' => ['bar']];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', $operation->applyTo($value));
  }

  #[Test]
  public function cannot_remove_whole_document() {
    $operation= new RemoveOperation('');

    $value= null;
    $this->assertInstanceOf('text.json.patch.TypeConflict', $operation->applyTo($value));
  }

  #[Test]
  public function dash_has_no_special_meaning_for_non_arrays() {
    $operation= new RemoveOperation('/-');

    $value= ['-' => 4];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals([], $value);
  }

  #[Test]
  public function shorten_array() {
    $operation= new RemoveOperation('/-');

    $value= [1, 2, 3];
    $this->assertEquals(Applied::$CLEANLY, $operation->applyTo($value));
    $this->assertEquals([1, 2], $value);
  }
}