<?php namespace text\json\patch;

class ObjectMember extends Address {
  private $name;

  public function __construct($name, parent $parent) {
    $this->name= $name;
    if (is_array($parent->reference) && array_key_exists($this->name, $parent->reference)) {
      parent::__construct($parent->reference[$this->name], $parent);
    } else {
      parent::__construct(self::$null, $parent, false);
    }
  }

  /** @return string */
  public function token() { return '/'.$this->name; }

  /**
   * Modify this address
   *
   * @param  var $value
   * @return text.json.patch.Error
   */
  public function modify($value) {
    if ($this->exists) {
      $this->reference= $value;
      return null;
    } else {
      return new PathDoesNotExist($this->path());
    }
  }

  /**
   * Remove this address
   *
   * @return text.json.patch.Error
   */
  public function remove() {
    if ($this->exists) {
      unset($this->parent->reference[$this->name]);
      return null;
    } else {
      return new PathDoesNotExist($this->path());
    }
  }

  /**
   * Add to this address
   *
   * @param  var $value
   * @return text.json.patch.Error
   */
  public function add($value) {
    if ($this->parent->exists) {
      if (0 === key($this->parent->reference)) {
        return new TypeConflict('Object operation on array target');
      }

      $this->parent->reference[$this->name]= $value;
      return null;
    } else {
      return new PathDoesNotExist($this->path());
    }
  }
}