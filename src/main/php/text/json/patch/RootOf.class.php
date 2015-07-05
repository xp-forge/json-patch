<?php namespace text\json\patch;

class RootOf extends Address {

  /** @return string */
  public function token() { return ''; }

  /**
   * Modify this address
   *
   * @param  var $value
   * @return text.json.patch.Applied
   */
  public function modify($value) {
    $this->reference= $value;
    return Applied::$CLEANLY;
  }

  /**
   * Remove this address
   *
   * @return text.json.patch.Applied
   */
  public function remove() {
    return new TypeConflict('Cannot remove root');
  }

  /**
   * Add to this address
   *
   * @param  var $value
   * @return text.json.patch.Applied
   */
  public function add($value) {
    $this->reference= $value;
    return Applied::$CLEANLY;
  }
}