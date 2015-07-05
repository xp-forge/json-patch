<?php namespace text\json\patch;

class PathDoesNotExist extends Error {
  private $path;

  /**
   * Creates a new "path does not exist" error
   *
   * @param  string $path
   */
  public function __construct($path) {
    $this->path= $path;
  }

  /** @return string */
  public function message() { return 'Path '.$this->path.' does not exist'; }
}