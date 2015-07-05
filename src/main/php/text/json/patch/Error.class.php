<?php namespace text\json\patch;

abstract class Error extends \lang\Object {

  public abstract function message();

  /** @return string */
  public function toString() {
    return nameof($this).'('.$this->message().')';
  }
}