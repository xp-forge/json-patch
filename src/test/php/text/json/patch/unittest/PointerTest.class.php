<?php namespace text\json\patch\unittest;

use text\json\patch\Pointer;
use text\json\patch\Address;

class PointerTest extends \unittest\TestCase {

  #[@test, @values(['', '/', '/ ', '/foo', '/foo/bar', '/foo/1', '/foo/1/bar', '/foo/bar/1', '/foo/-'])]
  public function can_create($input) {
    new Pointer($input);
  }

  #[@test]
  public function resolves_non_existant() {
    $value= [];
    $this->assertFalse((new Pointer('/non-existant'))->resolve($value)->exists());
  }

  #[@test]
  public function resolves_root() {
    $value= [];
    $this->assertTrue((new Pointer(''))->resolve($value)->exists());
  }

  #[@test]
  public function resolves_object_member_in_root() {
    $value= ['member' => 'value'];
    $this->assertTrue((new Pointer('/member'))->resolve($value)->exists());
  }

  #[@test]
  public function resolves_array_index_in_root() {
    $value= [1, 2, 3];
    $this->assertTrue((new Pointer('/1'))->resolve($value)->exists());
  }

  #[@test]
  public function value_of_root() {
    $value= ['member' => 'value'];
    $this->assertEquals($value, (new Pointer(''))->resolve($value)->value());
  }

  #[@test]
  public function value_of_empty_member_in_root() {
    $value= ['' => 'value'];
    $this->assertEquals('value', (new Pointer('/'))->resolve($value)->value());
  }

  #[@test]
  public function value_of_object_member_in_root() {
    $value= ['member' => 'value'];
    $this->assertEquals('value', (new Pointer('/member'))->resolve($value)->value());
  }

  #[@test]
  public function value_of_array_index_in_root() {
    $value= [1, 2, 3];
    $this->assertEquals(2, (new Pointer('/1'))->resolve($value)->value());
  }

  #[@test]
  public function value_of_array_index_in_object() {
    $value= ['numbers' => [1, 2, 3]];
    $this->assertEquals(2, (new Pointer('/numbers/1'))->resolve($value)->value());
  }

  #[@test]
  public function value_of_chaining_members_and_arrays() {
    $value= ['products' => [['color' => 'green']]];
    $this->assertEquals('green', (new Pointer('/products/0/color'))->resolve($value)->value());
  }

  #[@test]
  public function value_of_non_existant() {
    $value= [];
    $this->assertNull((new Pointer('/non-existant'))->resolve($value)->value());
  }

  #[@test]
  public function modify_root() {
    $value= ['member' => 'value'];
    (new Pointer(''))->resolve($value)->modify(['exchanged' => 'completely']);
    $this->assertEquals(['exchanged' => 'completely'], $value);
  }

  #[@test]
  public function modify_array_index() {
    $value= [1, 2, 3];
    (new Pointer('/1'))->resolve($value)->modify(4);
    $this->assertEquals([1, 4, 3], $value);
  }

  #[@test]
  public function modify_object_member() {
    $value= ['text' => 'original'];
    (new Pointer('/text'))->resolve($value)->modify('modified');
    $this->assertEquals(['text' => 'modified'], $value);
  }

  #[@test]
  public function modify_non_existant_array_index() {
    $value= [];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', (new Pointer('/1'))->resolve($value)->modify('test'));
  }

  #[@test]
  public function modify_non_existant_object_member() {
    $value= [];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', (new Pointer('/text'))->resolve($value)->modify('test'));
  }

  #[@test]
  public function cannot_modify_using_end_of_array() {
    $value= [];
    $this->assertInstanceOf('text.json.patch.ArrayIndexOutOfBounds', (new Pointer('/-'))->resolve($value)->modify('test'));
  }

  #[@test]
  public function remove_array_index() {
    $value= [1, 2, 3];
    (new Pointer('/1'))->resolve($value)->remove();
    $this->assertEquals([1, 3], $value);
  }

  #[@test]
  public function remove_object_member() {
    $value= ['text' => 'original'];
    (new Pointer('/text'))->resolve($value)->remove();
    $this->assertEquals([], $value);
  }

  #[@test]
  public function remove_non_existant_array_index() {
    $value= [];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', (new Pointer('/1'))->resolve($value)->remove());
  }

  #[@test]
  public function remove_non_existant_object_member() {
    $value= [];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', (new Pointer('/text'))->resolve($value)->remove());
  }

  #[@test]
  public function cannot_remove_using_end_of_array() {
    $value= [];
    $this->assertInstanceOf('text.json.patch.ArrayIndexOutOfBounds', (new Pointer('/-'))->resolve($value)->remove());
  }

  #[@test]
  public function add_array_index() {
    $value= [1, 2, 3];
    (new Pointer('/1'))->resolve($value)->add(4);
    $this->assertEquals([1, 4, 2, 3], $value);
  }

  #[@test]
  public function add_at_end_of_array() {
    $value= [1, 2, 3];
    (new Pointer('/-'))->resolve($value)->add(4);
    $this->assertEquals([1, 2, 3, 4], $value);
  }

  #[@test]
  public function add_object_member() {
    $value= ['a' => 'original'];
    (new Pointer('/b'))->resolve($value)->add('added');
    $this->assertEquals(['a' => 'original', 'b' => 'added'], $value);
  }

  #[@test]
  public function cannot_add_object_member_to_array() {
    $value= [1, 2, 3];
    $this->assertInstanceOf('text.json.patch.TypeConflict', (new Pointer('/member'))->resolve($value)->add('test'));
  }

  #[@test]
  public function cannot_add_to_nonexistant_array() {
    $value= [];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', (new Pointer('/1/member'))->resolve($value)->add('test'));
  }

  #[@test]
  public function cannot_add_to_nonexistant_object() {
    $value= [];
    $this->assertInstanceOf('text.json.patch.PathDoesNotExist', (new Pointer('/non-existant/member'))->resolve($value)->add('test'));
  }
}