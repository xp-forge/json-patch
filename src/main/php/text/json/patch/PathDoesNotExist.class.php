<?php namespace text\json\patch;

class PathDoesNotExist extends Error {
  private $path;

  public function __construct($path) {
    $this->path= $path;
  }

  public function message() { return 'Path '.$this->path.' does not exist'; }
}