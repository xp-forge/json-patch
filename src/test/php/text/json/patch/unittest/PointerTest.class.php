<?php namespace text\json\patch\unittest;

use text\json\patch\Pointer;

class PointerTest extends \unittest\TestCase {
  private $value= ['value' => 6100];

  #[@test]
  public function can_create() {
    new Pointer($this->value);
  }

  #[@test]
  public function resolves() {
    $this->assertTrue((new Pointer($this->value))->resolves());
  }

  #[@test]
  public function value() {
    $this->assertEquals($this->value, (new Pointer($this->value))->value());
  }

  #[@test]
  public function add_to_array() {
    $this->assertEquals(true, (new Pointer($this->value))->address('-'));
  }

  #[@test, @values(['0', '1', '-1'])]
  public function array_index($index) {
    $this->assertEquals((int)$index, (new Pointer($this->value))->address($index));
  }

  #[@test, @values(['member', '', '1e0'])]
  public function object_member($member) {
    $this->assertEquals($member, (new Pointer($this->value))->address($member));
  }

  #[@test, @values([['~', '~0'], ['test~', 'test~0'], ['~test', '~0test']])]
  public function escaped_tilde($expected, $input) {
    $this->assertEquals($expected, (new Pointer($this->value))->address($input));
  }

  #[@test, @values([['/', '~1'], ['test/', 'test~1'], ['/test', '~1test']])]
  public function escaped_slash($expected, $input) {
    $this->assertEquals($expected, (new Pointer($this->value))->address($input));
  }
}