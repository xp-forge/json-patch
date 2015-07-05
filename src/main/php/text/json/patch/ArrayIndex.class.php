<?php namespace text\json\patch;

class ArrayIndex extends Address {
  private $pos;

  public function __construct($pos, $parent) {
    $this->pos= $pos;
    if (is_array($parent->reference) && array_key_exists($this->pos, $parent->reference)) {
      parent::__construct($parent->reference[$this->pos], $parent);
    } else {
      parent::__construct(self::$null, $parent, false);
    }
  }

  /** @return string */
  public function token() { return '/'.$this->pos; }

  /**
   * Modify this address
   *
   * @param  var $value
   * @return text.json.patch.Applied
   */
  public function modify($value) {
    if ($this->exists) {
      $this->reference= $value;
      return Applied::$CLEANLY;
    } else {
      return new PathDoesNotExist($this->path());
    }
  }

  /**
   * Removes this address
   *
   * @return text.json.patch.Applied
   */
  public function remove() {
    if ($this->exists) {
      array_splice($this->parent->reference, $this->pos, 1);
      return Applied::$CLEANLY;
    } else {
      return new PathDoesNotExist($this->path());
    }
  }

  /**
   * Add to this address
   *
   * @param  var $value
   * @return text.json.patch.Applied
   */
  public function add($value) {
    if ($this->parent->exists) {
      if ($this->pos < 0 || $this->pos > sizeof($this->parent->reference)) {
        return new ArrayIndexOutOfBounds($this->pos);
      }

      array_splice($this->parent->reference, $this->pos, 0, [$value]);
      return Applied::$CLEANLY;
    } else {
      return new PathDoesNotExist($this->path());
    }
  }
}