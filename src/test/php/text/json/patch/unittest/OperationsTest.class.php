<?php namespace text\json\patch\unittest;

use text\json\patch\Operations;
use text\json\patch\AddOperation;
use text\json\patch\RemoveOperation;
use text\json\patch\ReplaceOperation;
use text\json\patch\MoveOperation;
use text\json\patch\CopyOperation;
use text\json\patch\TestOperation;
use lang\IllegalArgumentException;

class OperationsTest extends \unittest\TestCase {

  #[@test, @expect(IllegalArgumentException::class)]
  public function unknown_op() {
    Operations::named('UNKNOWN');
  }

  #[@test]
  public function add() {
    $this->assertEquals(
      new AddOperation('/path', 'value'),
      Operations::named('add')->newInstance(['path' => '/path', 'value' => 'value'])
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path_for_add() {
    Operations::named('add')->newInstance([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "value"/')]
  public function missing_value_for_add() {
    Operations::named('add')->newInstance(['path' => '/path']);
  }

  #[@test]
  public function remove() {
    $this->assertEquals(
      new RemoveOperation('/path'),
      Operations::named('remove')->newInstance(['path' => '/path'])
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path_for_remove() {
    Operations::named('remove')->newInstance([]);
  }

  #[@test]
  public function replace() {
    $this->assertEquals(
      new ReplaceOperation('/path', 'value'),
      Operations::named('replace')->newInstance(['path' => '/path', 'value' => 'value'])
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path_for_replace() {
    Operations::named('replace')->newInstance([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "value"/')]
  public function missing_value_for_replace() {
    Operations::named('replace')->newInstance(['path' => '/path']);
  }

  #[@test]
  public function move() {
    $this->assertEquals(
      new MoveOperation('/source', '/path'),
      Operations::named('move')->newInstance(['path' => '/path', 'from' => '/source'])
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "from"/')]
  public function missing_path_for_move() {
    Operations::named('move')->newInstance([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "from"/')]
  public function missing_from_for_move() {
    Operations::named('move')->newInstance(['path' => '/path']);
  }

  #[@test]
  public function copy() {
    $this->assertEquals(
      new CopyOperation('/source', '/path'),
      Operations::named('copy')->newInstance(['path' => '/path', 'from' => '/source'])
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "from"/')]
  public function missing_path_for_copy() {
    Operations::named('copy')->newInstance([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "from"/')]
  public function missing_from_for_copy() {
    Operations::named('copy')->newInstance(['path' => '/path']);
  }

  #[@test]
  public function test() {
    $this->assertEquals(
      new TestOperation('/path', 'value'),
      Operations::named('test')->newInstance(['path' => '/path', 'value' => 'value'])
    );
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "path"/')]
  public function missing_path_for_test() {
    Operations::named('test')->newInstance([]);
  }

  #[@test, @expect(class= IllegalArgumentException::class, withMessage= '/Missing member "value"/')]
  public function missing_value_for_test() {
    Operations::named('test')->newInstance(['path' => '/path']);
  }
}