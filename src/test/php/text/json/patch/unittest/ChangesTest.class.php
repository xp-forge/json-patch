<?php namespace text\json\patch\unittest;

use test\Assert;
use test\{Test, TestCase, Values};
use text\json\patch\{Changes, TestOperation};

class ChangesTest {

  /** @return iterable */
  private function creation() {
    yield [['op' => 'test', 'path' => '/a/b/c', 'value' => 'foo']];
    yield [['op' => 'remove', 'path' => '/a/b/c']];
    yield [['op' => 'add', 'path' => '/a/b/c', 'value' => [ 'foo', 'bar' ]]];
    yield [['op' => 'replace', 'path' => '/a/b/c', 'value' => 42]];
    yield [['op' => 'move', 'from' => '/a/b/c', 'path' => '/a/b/d']];
    yield [['op' => 'copy', 'from' => '/a/b/d', 'path' => '/a/b/e']];
    yield [new TestOperation('/a/b/c', null)];
  }

  #[Test]
  public function can_create_from_empty() {
    new Changes();
  }

  #[Test, Values(from: 'creation')]
  public function can_create_from($operation) {
    new Changes($operation);
  }

  #[Test]
  public function can_create_from_map_and_instance() {
    new Changes(
      ['op' => 'test', 'path' => '/a/b/c', 'value' => null],
      new TestOperation('/a/b/c', null)
    );
  }

  #[Test]
  public function empty_operations() {
    Assert::equals([], (new Changes())->operations());
  }

  #[Test]
  public function operations() {
    $operation= new TestOperation('/a/b/c', null);
    Assert::equals([$operation], (new Changes($operation))->operations());
  }

  #[Test]
  public function to_single_field() {
    Assert::equals(
      ['/a/b/c'],
      (new Changes(['op' => 'remove', 'path' => '/a/b/c']))->to()
    );
  }

  #[Test]
  public function to_multiple_fields() {
    Assert::equals(
      ['/a/b/c', '/a/b/d'],
      (new Changes(['op' => 'remove', 'path' => '/a/b/c'], ['op' => 'remove', 'path' => '/a/b/d']))->to()
    );
  }

  #[Test]
  public function to_uniques() {
    Assert::equals(
      ['/a/b/c'],
      (new Changes(['op' => 'test', 'path' => '/a/b/c', 'value' => null], ['op' => 'remove', 'path' => '/a/b/c']))->to()
    );
  }
}