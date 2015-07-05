<?php namespace text\json\patch;

/**
 * A pointer segment in a JSON pointer 
 *
 * @see   http://tools.ietf.org/html/rfc6901
 * @test  xp://text.json.patch.unittest.PointerTest
 */
class Pointer extends \lang\Object {
  protected $reference;

  public function __construct(&$reference) {
    $this->reference= &$reference;
  }

  /**
   * Gets an address
   *
   * @param  string $address
   * @return var TRUE if the special "-" token is given, ints for numeric tokens, strings otherwise
   */
  public function address($token) {
    static $escape= ['~0' => '~', '~1' => '/'];

    if ('-' === $token) {
      return true;
    } else if ('' === $token) {
      return '';
    } else {
      $address= strtr($token, $escape);
      $number= sscanf($address, '%d%s', $pos, $rest);
      return 1 === $number ? $pos : $address;
    }
  }

  /**
   * Get a new pointer to an address inside this pointer
   *
   * @param  string $token
   * @return bool
   */
  public function to($token) {
    $key= $this->address($token);
    if (array_key_exists($key, $this->reference)) {
      return new self($this->reference[$key]);
    } else {
      return new NullPointer($token);
    }
  }

  /** @return bool */
  public function resolves() { return true; }

  /** @return var */
  public function value() { return $this->reference; }

  /**
   * Modify this pointer
   *
   * @param  var $value
   * @return text.json.patch.Error
   */
  public function modify($value) {
    $this->reference= $value;
    return null;
  }
}