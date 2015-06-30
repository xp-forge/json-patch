<?php namespace text\json\patch\unittest;

use text\json\patch\Changes;
use text\json\patch\TestOperation;

class ChangesTest extends \unittest\TestCase {

  #[@test]
  public function can_create_from_empty() {
    new Changes();
  }

  #[@test, @values([
  #  [['op' => 'test', 'path' => '/a/b/c', 'value' => 'foo']],
  #  [['op' => 'remove', 'path' => '/a/b/c']],
  #  [['op' => 'add', 'path' => '/a/b/c', 'value' => [ 'foo', 'bar' ]]],
  #  [['op' => 'replace', 'path' => '/a/b/c', 'value' => 42]],
  #  [['op' => 'move', 'from' => '/a/b/c', 'path' => '/a/b/d']],
  #  [['op' => 'copy', 'from' => '/a/b/d', 'path' => '/a/b/e']],
  #  [new TestOperation(['op' => 'test', 'path' => '/a/b/c', 'value' => null])]
  #])]
  public function can_create_from($operation) {
    new Changes($operation);
  }

  #[@test]
  public function can_create_from_map_and_instance() {
    new Changes(
      ['op' => 'test', 'path' => '/a/b/c', 'value' => null],
      new TestOperation(['op' => 'test', 'path' => '/a/b/c', 'value' => null]
    ));
  }

  #[@test]
  public function empty_operations() {
    $this->assertEquals([], (new Changes())->operations());
  }

  #[@test]
  public function operations() {
    $operation= new TestOperation(['op' => 'test', 'path' => '/a/b/c', 'value' => null]);
    $this->assertEquals([$operation], (new Changes($operation))->operations());
  }

  #[@test]
  public function to_single_field() {
    $this->assertEquals(
      ['/a/b/c'],
      (new Changes(['op' => 'remove', 'path' => '/a/b/c']))->to()
    );
  }

  #[@test]
  public function to_multiple_fields() {
    $this->assertEquals(
      ['/a/b/c', '/a/b/d'],
      (new Changes(['op' => 'remove', 'path' => '/a/b/c'], ['op' => 'remove', 'path' => '/a/b/d']))->to()
    );
  }

  #[@test]
  public function to_uniques() {
    $this->assertEquals(
      ['/a/b/c'],
      (new Changes(['op' => 'test', 'path' => '/a/b/c', 'value' => null], ['op' => 'remove', 'path' => '/a/b/c']))->to()
    );
  }
}