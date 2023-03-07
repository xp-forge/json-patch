<?php namespace text\json\patch\unittest;

use text\json\patch\{Address, Pointer};
use test\Assert;
use test\{Test, Values};

class PointerTest {

  /** @return var[][] */
  private function fixtures() {
    return [
      [''], ['/'],
      ['/ '], ['/  '],
      ['/foo'], ['/foo/bar'],
      ['/foo/1'], ['/foo/1/bar'], ['/foo/bar/1'],
      ['/foo/-'],
      ['/foo/~0'], ['/foo/~1'], ['/foo/~01'], ['/foo/~10']
    ];
  }

  #[Test, Values(from: 'fixtures')]
  public function can_create($input) {
    new Pointer($input);
  }

  #[Test, Values(from: 'fixtures')]
  public function string($input) {
    Assert::equals($input, (string)(new Pointer($input)));
  }

  #[Test, Values([['/~01', '~1'], ['/~00', '~0'], ['/~10', '/0'], ['/~11', '/1'], ['/~0~0', '~~'], ['/~1~1', '//']])]
  public function escaping($escaped, $key) {
    $value= [$key => 'OK'];
    Assert::equals('OK', (new Pointer($escaped))->resolve($value)->value());
  }

  #[Test]
  public function resolves_non_existant() {
    $value= [];
    Assert::false((new Pointer('/non-existant'))->resolve($value)->exists());
  }

  #[Test]
  public function resolves_root() {
    $value= [];
    Assert::true((new Pointer(''))->resolve($value)->exists());
  }

  #[Test]
  public function resolves_object_member_in_root() {
    $value= ['member' => 'value'];
    Assert::true((new Pointer('/member'))->resolve($value)->exists());
  }

  #[Test]
  public function resolves_array_index_in_root() {
    $value= [1, 2, 3];
    Assert::true((new Pointer('/1'))->resolve($value)->exists());
  }

  #[Test]
  public function value_of_root() {
    $value= ['member' => 'value'];
    Assert::equals($value, (new Pointer(''))->resolve($value)->value());
  }

  #[Test]
  public function value_of_empty_member_in_root() {
    $value= ['' => 'value'];
    Assert::equals('value', (new Pointer('/'))->resolve($value)->value());
  }

  #[Test]
  public function value_of_object_member_in_root() {
    $value= ['member' => 'value'];
    Assert::equals('value', (new Pointer('/member'))->resolve($value)->value());
  }

  #[Test]
  public function value_of_array_index_in_root() {
    $value= [1, 2, 3];
    Assert::equals(2, (new Pointer('/1'))->resolve($value)->value());
  }

  #[Test]
  public function value_of_array_index_in_object() {
    $value= ['numbers' => [1, 2, 3]];
    Assert::equals(2, (new Pointer('/numbers/1'))->resolve($value)->value());
  }

  #[Test]
  public function value_of_chaining_members_and_arrays() {
    $value= ['products' => [['color' => 'green']]];
    Assert::equals('green', (new Pointer('/products/0/color'))->resolve($value)->value());
  }

  #[Test]
  public function value_of_non_existant() {
    $value= [];
    Assert::null((new Pointer('/non-existant'))->resolve($value)->value());
  }

  #[Test]
  public function modify_root() {
    $value= ['member' => 'value'];
    (new Pointer(''))->resolve($value)->modify(['exchanged' => 'completely']);
    Assert::equals(['exchanged' => 'completely'], $value);
  }

  #[Test]
  public function modify_array_index() {
    $value= [1, 2, 3];
    (new Pointer('/1'))->resolve($value)->modify(4);
    Assert::equals([1, 4, 3], $value);
  }

  #[Test]
  public function modify_object_member() {
    $value= ['text' => 'original'];
    (new Pointer('/text'))->resolve($value)->modify('modified');
    Assert::equals(['text' => 'modified'], $value);
  }

  #[Test]
  public function modify_non_existant_array_index() {
    $value= [];
    Assert::instance('text.json.patch.PathDoesNotExist', (new Pointer('/1'))->resolve($value)->modify('test'));
  }

  #[Test]
  public function modify_non_existant_object_member() {
    $value= [];
    Assert::instance('text.json.patch.PathDoesNotExist', (new Pointer('/text'))->resolve($value)->modify('test'));
  }

  #[Test]
  public function cannot_modify_using_end_of_array() {
    $value= [];
    Assert::instance('text.json.patch.ArrayIndexOutOfBounds', (new Pointer('/-'))->resolve($value)->modify('test'));
  }

  #[Test]
  public function remove_array_index() {
    $value= [1, 2, 3];
    (new Pointer('/1'))->resolve($value)->remove();
    Assert::equals([1, 3], $value);
  }

  #[Test]
  public function remove_object_member() {
    $value= ['text' => 'original'];
    (new Pointer('/text'))->resolve($value)->remove();
    Assert::equals([], $value);
  }

  #[Test]
  public function remove_non_existant_array_index() {
    $value= [];
    Assert::instance('text.json.patch.PathDoesNotExist', (new Pointer('/1'))->resolve($value)->remove());
  }

  #[Test]
  public function remove_non_existant_object_member() {
    $value= [];
    Assert::instance('text.json.patch.PathDoesNotExist', (new Pointer('/text'))->resolve($value)->remove());
  }

  #[Test]
  public function cannot_remove_using_end_of_array() {
    $value= [];
    Assert::instance('text.json.patch.ArrayIndexOutOfBounds', (new Pointer('/-'))->resolve($value)->remove());
  }

  #[Test]
  public function add_array_index() {
    $value= [1, 2, 3];
    (new Pointer('/1'))->resolve($value)->add(4);
    Assert::equals([1, 4, 2, 3], $value);
  }

  #[Test]
  public function add_at_end_of_array() {
    $value= [1, 2, 3];
    (new Pointer('/-'))->resolve($value)->add(4);
    Assert::equals([1, 2, 3, 4], $value);
  }

  #[Test]
  public function add_object_member() {
    $value= ['a' => 'original'];
    (new Pointer('/b'))->resolve($value)->add('added');
    Assert::equals(['a' => 'original', 'b' => 'added'], $value);
  }

  #[Test]
  public function cannot_add_object_member_to_array() {
    $value= [1, 2, 3];
    Assert::instance('text.json.patch.TypeConflict', (new Pointer('/member'))->resolve($value)->add('test'));
  }

  #[Test]
  public function cannot_add_to_nonexistant_array() {
    $value= [];
    Assert::instance('text.json.patch.PathDoesNotExist', (new Pointer('/1/member'))->resolve($value)->add('test'));
  }

  #[Test]
  public function cannot_add_to_nonexistant_object() {
    $value= [];
    Assert::instance('text.json.patch.PathDoesNotExist', (new Pointer('/non-existant/member'))->resolve($value)->add('test'));
  }
}