<?php namespace text\json\patch;

class TypeConflict extends Error {
  private $message;

  /**
   * Creates a new type conflict error
   *
   * @param  string $message
   */
  public function __construct($message) {
    $this->message= $message;
  }

  /** @return string */
  public function message() { return $this->message; }
}