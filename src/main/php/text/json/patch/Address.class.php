<?php namespace text\json\patch;

abstract class Address extends \lang\Object {
  protected static $null= null;
  protected $exists, $parent;
  public $reference;

  public function __construct(&$reference, $parent= null, $exists= true) {
    $this->reference= &$reference;
    $this->parent= $parent;
    $this->exists= $exists;
  }

  /** @return bool */
  public function exists() { return $this->exists; }

  /** @return var */
  public function value() { return $this->reference; }

  /** @return string */
  public function path() {
    $base= $this;
    $path= '';
    do {
      $path= $base->token().$path;
    } while ($base= $base->parent);
    return $path;
  }

  /**
   * Modify this address
   *
   * @param  var $value
   * @return text.json.patch.Applied
   */
  public abstract function modify($value);

  /**
   * Remove this address
   *
   * @return text.json.patch.Applied
   */
  public abstract function remove();

  /**
   * Add to this address
   *
   * @param  var $value
   * @return text.json.patch.Applied
   */
  public abstract function add($value);
}