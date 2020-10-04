<?php namespace text\json\patch\unittest;

use lang\IllegalArgumentException;
use text\json\patch\{AddOperation, CopyOperation, MoveOperation, Operations, RemoveOperation, ReplaceOperation, TestOperation};
use unittest\{Expect, Test};

class OperationsTest extends \unittest\TestCase {

  #[Test, Expect(IllegalArgumentException::class)]
  public function unknown_op() {
    Operations::named('UNKNOWN');
  }

  #[Test]
  public function add() {
    $this->assertEquals(
      new AddOperation('/path', 'value'),
      Operations::named('add')->newInstance(['path' => '/path', 'value' => 'value'])
    );
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "path"/'])]
  public function missing_path_for_add() {
    Operations::named('add')->newInstance([]);
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "value"/'])]
  public function missing_value_for_add() {
    Operations::named('add')->newInstance(['path' => '/path']);
  }

  #[Test]
  public function remove() {
    $this->assertEquals(
      new RemoveOperation('/path'),
      Operations::named('remove')->newInstance(['path' => '/path'])
    );
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "path"/'])]
  public function missing_path_for_remove() {
    Operations::named('remove')->newInstance([]);
  }

  #[Test]
  public function replace() {
    $this->assertEquals(
      new ReplaceOperation('/path', 'value'),
      Operations::named('replace')->newInstance(['path' => '/path', 'value' => 'value'])
    );
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "path"/'])]
  public function missing_path_for_replace() {
    Operations::named('replace')->newInstance([]);
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "value"/'])]
  public function missing_value_for_replace() {
    Operations::named('replace')->newInstance(['path' => '/path']);
  }

  #[Test]
  public function move() {
    $this->assertEquals(
      new MoveOperation('/source', '/path'),
      Operations::named('move')->newInstance(['path' => '/path', 'from' => '/source'])
    );
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "from"/'])]
  public function missing_path_for_move() {
    Operations::named('move')->newInstance([]);
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "from"/'])]
  public function missing_from_for_move() {
    Operations::named('move')->newInstance(['path' => '/path']);
  }

  #[Test]
  public function copy() {
    $this->assertEquals(
      new CopyOperation('/source', '/path'),
      Operations::named('copy')->newInstance(['path' => '/path', 'from' => '/source'])
    );
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "from"/'])]
  public function missing_path_for_copy() {
    Operations::named('copy')->newInstance([]);
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "from"/'])]
  public function missing_from_for_copy() {
    Operations::named('copy')->newInstance(['path' => '/path']);
  }

  #[Test]
  public function test() {
    $this->assertEquals(
      new TestOperation('/path', 'value'),
      Operations::named('test')->newInstance(['path' => '/path', 'value' => 'value'])
    );
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "path"/'])]
  public function missing_path_for_test() {
    Operations::named('test')->newInstance([]);
  }

  #[Test, Expect(['class' => IllegalArgumentException::class, 'withMessage' => '/Missing member "value"/'])]
  public function missing_value_for_test() {
    Operations::named('test')->newInstance(['path' => '/path']);
  }
}