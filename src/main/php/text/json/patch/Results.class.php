<?php namespace text\json\patch;

use lang\Value;

/**
 * Holds results from `Changes::apply()`.
 */
abstract class Results implements Value {

  /** @return bool */
  public abstract function successful();

}