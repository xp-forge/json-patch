<?php namespace text\json\patch;

use lang\IllegalArgumentException;

/**
 * A pointer segment in a JSON pointer 
 *
 * @see   http://tools.ietf.org/html/rfc6901
 * @test  xp://text.json.patch.unittest.PointerTest
 */
class Pointer extends \lang\Object {
  protected $path;

  /**
   * Creates a new pointer
   *
   * @param  string $path
   * @throws lang.IllegalArgumentException
   */
  public function __construct($path) {
    if ('' === $path) {
      $this->path= [];
    } else if (1 === strspn($path, '/')) {
      $this->path= [];
      foreach (explode('/', substr($path, 1)) as $token) {
        $this->path[$token]= $this->address($token);
      }
    } else {
      throw new IllegalArgumentException('Malformed path '.$path.', must either be empty or start with "/"');
    }
  }

  /**
   * Returns address resolvers 
   *
   * @param  string $token
   * @return function(text.json.patch.Address): text.json.patch.Address
   */
  private function address($token) {
    static $escape= ['~1' => '/', '~0' => '~'];

    if ('-' === $token) {
      return function($parent) { return new ArrayEnd($parent); };
    } else if ('' === $token) {
      return function($parent) { return new ObjectMember('', $parent); };
    } else {
      $member= strtr($token, $escape);
      return 1 === sscanf($member, '%d%s', $pos, $rest)
        ? function($parent) use($pos) { return new ArrayIndex($pos, $parent); }
        : function($parent) use($member) { return new ObjectMember($member, $parent); }
      ;
    }
  }

  /**
   * Resolves input to an address
   *
   * @param  var $input
   * @return text.json.patch.Address
   */
  public function resolve(&$input) {
    $address= new RootOf($input);
    foreach ($this->path as $resolver) {
      $address= $resolver($address);
    }
    return $address;
  }

  /** @return string */
  public function __toString() {
    if (empty($this->path)) {
      return '';
    } else {
      $path= '';
      foreach ($this->path as $token => $resolver) {
        $path.= '/'.$token;
      }
      return $path;
    }
  }

  /**
   * Returns whether a given value is equal to this results instance
   *
   * @param  var $cmp
   * @return bool
   */
  public function equals($cmp) {
    return $cmp instanceof self && $this->__toString() === $cmp->__toString();
  }
}