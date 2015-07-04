<?php namespace text\json\patch;

class Pointer extends \lang\Object {
  protected $reference;

  public function __construct(&$reference) {
    $this->reference= &$reference;
  }

  /**
   * Get a new pointer to an address inside this pointer
   *
   * @param  string $address
   * @return bool
   */
  public function to($address) {
    if (is_numeric($address) && isset($this->reference[$pos= (int)$address])) {
      return new self($this->reference[$pos]);
    } else if (isset($this->reference[$address])) {
      return new self($this->reference[$address]);
    }
    return new NullPointer($address);
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