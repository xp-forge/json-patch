<?php namespace text\json\patch;

class TypeConflict extends Error {
  private $message;

  public function __construct($message) {
    $this->message= $message;
  }

  public function message() { return $this->message; }
}