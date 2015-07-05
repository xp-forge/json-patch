<?php namespace text\json\patch;

class ArrayEnd extends Address {

  /**
   * Creates a new "end of array" address
   *
   * @param  parent $parent
   */
  public function __construct(parent $parent) {
    parent::__construct(self::$null, $parent, false);
  }

  /** @return string */
  public function token() { return '/-'; }

  /**
   * Removes this address
   *
   * @param  var $value
   * @return text.json.patch.Applied
   */
  public function modify($value) {
    if ($this->parent->exists) {
      if (array_key_exists('-', $this->parent->reference)) {
        $this->parent->reference['-']= $value;
        return Applied::$CLEANLY;
      }
    }
    return new ArrayIndexOutOfBounds('-');
  }

  /**
   * Removes this address
   *
   * @return text.json.patch.Applied
   */
  public function remove() {
    if ($this->parent->exists) {
      if (array_key_exists('-', $this->parent->reference)) {
        unset($this->parent->reference['-']);
        return Applied::$CLEANLY;
      } else if (0 === key($this->parent->reference)) {
        array_pop($this->parent->reference);
        return Applied::$CLEANLY;
      }
    }
    return new ArrayIndexOutOfBounds('-');
  }

  /**
   * Add to this address
   *
   * @param  var $value
   * @return text.json.patch.Applied
   */
  public function add($value) {
    if ($this->parent->exists) {
      if (empty($this->parent->reference) || 0 === key($this->parent->reference)) {
        $this->parent->reference[]= $value;
        return Applied::$CLEANLY;
      } else {
        $this->parent->reference['-']= $value;
        return Applied::$CLEANLY;
      }
    } else {
      return new PathDoesNotExist($this->path());
    }
  }
}