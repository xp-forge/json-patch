<?php namespace text\json\patch;

use util\Objects;

/**
 * Holds results from `Changes::apply()`.
 */
abstract class Results extends \lang\Object {

  /** @return bool */
  public abstract function successful();

}