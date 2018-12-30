<?php namespace text\json\patch;

use lang\Value;

abstract class Error extends Applied implements Value {

  static function __static() { }

  /** @return bool */
  public function isError() { return true; }

  /** @return string */
  public abstract function message();

  /** @return string */
  public function hashCode() {
    return 'E'.md5($this->message());
  }

  /** @return string */
  public function toString() {
    return nameof($this).'('.$this->message().')';
  }

  /**
   * Returns whether a given error is equal to this results instance
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self ? strcmp($this->message(), $value->message()) : 1;
  }
}