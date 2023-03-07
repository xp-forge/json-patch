<?php namespace text\json\patch\unittest;

use lang\IllegalArgumentException;
use test\{Assert, Expect, Test};
use text\json\patch\{AddOperation, CopyOperation, MoveOperation, Operations, RemoveOperation, ReplaceOperation, TestOperation};

class OperationsTest {

  #[Test, Expect(IllegalArgumentException::class)]
  public function unknown_op() {
    Operations::named('UNKNOWN');
  }

  #[Test]
  public function add() {
    Assert::equals(
      new AddOperation('/path', 'value'),
      Operations::named('add')->newInstance(['path' => '/path', 'value' => 'value'])
    );
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "path"/')]
  public function missing_path_for_add() {
    Operations::named('add')->newInstance([]);
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "value"/')]
  public function missing_value_for_add() {
    Operations::named('add')->newInstance(['path' => '/path']);
  }

  #[Test]
  public function remove() {
    Assert::equals(
      new RemoveOperation('/path'),
      Operations::named('remove')->newInstance(['path' => '/path'])
    );
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "path"/')]
  public function missing_path_for_remove() {
    Operations::named('remove')->newInstance([]);
  }

  #[Test]
  public function replace() {
    Assert::equals(
      new ReplaceOperation('/path', 'value'),
      Operations::named('replace')->newInstance(['path' => '/path', 'value' => 'value'])
    );
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "path"/')]
  public function missing_path_for_replace() {
    Operations::named('replace')->newInstance([]);
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "value"/')]
  public function missing_value_for_replace() {
    Operations::named('replace')->newInstance(['path' => '/path']);
  }

  #[Test]
  public function move() {
    Assert::equals(
      new MoveOperation('/source', '/path'),
      Operations::named('move')->newInstance(['path' => '/path', 'from' => '/source'])
    );
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "from"/')]
  public function missing_path_for_move() {
    Operations::named('move')->newInstance([]);
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "from"/')]
  public function missing_from_for_move() {
    Operations::named('move')->newInstance(['path' => '/path']);
  }

  #[Test]
  public function copy() {
    Assert::equals(
      new CopyOperation('/source', '/path'),
      Operations::named('copy')->newInstance(['path' => '/path', 'from' => '/source'])
    );
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "from"/')]
  public function missing_path_for_copy() {
    Operations::named('copy')->newInstance([]);
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "from"/')]
  public function missing_from_for_copy() {
    Operations::named('copy')->newInstance(['path' => '/path']);
  }

  #[Test]
  public function test() {
    Assert::equals(
      new TestOperation('/path', 'value'),
      Operations::named('test')->newInstance(['path' => '/path', 'value' => 'value'])
    );
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "path"/')]
  public function missing_path_for_test() {
    Operations::named('test')->newInstance([]);
  }

  #[Test, Expect(class: IllegalArgumentException::class, message: '/Missing member "value"/')]
  public function missing_value_for_test() {
    Operations::named('test')->newInstance(['path' => '/path']);
  }
}