<?php namespace text\json\patch;

class NullPointer extends Pointer {
  private $address;

  public function __construct($address) {
    $this->address= $address;
  }

  /**
   * Get a new pointer to an address inside this pointer
   *
   * @param  string $address
   * @return bool
   */
  public function to($address) {
    return new self($this->address.'/'.$address);
  }

  /** @return bool */
  public function resolves() { return false; }

  /** @return var */
  public function value() { return null; }

  /**
   * Modify this pointer
   *
   * @param  var $value
   * @return bool
   */
  public function modify($value) {
    return false;
  }
}