<?php namespace text\json\patch;

abstract class Error extends \lang\Object {

  public abstract function message();

  /** @return string */
  public function toString() {
    return nameof($this).'('.$this->message().')';
  }

  /**
   * Returns whether a given error is equal to this results instance
   *
   * @param  var $cmp
   * @return bool
   */
  public function equals($cmp) {
    return $cmp instanceof self && $this->message() === $cmp->message();
  }
}