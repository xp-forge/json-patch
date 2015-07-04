<?php namespace text\json\patch;

class Pointer extends \lang\Object {
  protected $reference;

  public function __construct(&$reference) {
    $this->reference= &$reference;
  }

  public function address($address) {
    if ('-' === $address) {
      return true;
    } else if ('' === $address) {
      return '';
    } else {
      $number= sscanf($address, '%d%s', $pos, $rest);
      if (0 === $number) {
        return $address;
      } else if (1 === $number) {
        return $pos;
      } else {
        return null;
      }
    }
  }

  /**
   * Get a new pointer to an address inside this pointer
   *
   * @param  string $address
   * @return bool
   */
  public function to($address) {
    $key= $this->address($address);
    if (array_key_exists($key, $this->reference)) {
      return new self($this->reference[$key]);
    } else {
      return new NullPointer($address);
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
   * @return bool
   */
  public function modify($value) {
    $this->reference= $value;
    return true;
  }
}