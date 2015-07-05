<?php namespace text\json\patch;

class ArrayEnd extends Address {

  public function __construct($parent) {
    parent::__construct(self::$null, $parent, false);
  }

  /** @return string */
  public function token() { return '/-'; }

  /**
   * Removes this address
   *
   * @param  var $value
   * @return text.json.patch.Error
   */
  public function modify($value) {
    return new ArrayIndexOutOfBounds('-');
  }

  /**
   * Removes this address
   *
   * @return text.json.patch.Error
   */
  public function remove() {
    return new ArrayIndexOutOfBounds('-');
  }

  /**
   * Add to this address
   *
   * @param  var $value
   * @return text.json.patch.Error
   */
  public function add($value) {
    if ($this->parent->exists) {
      $this->parent->reference[]= $value;
      return null;
    } else {
      return new PathDoesNotExist($this->path());
    }
  }
}