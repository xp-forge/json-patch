<?php namespace text\json\patch\unittest;

use text\json\patch\Changes;
use io\collections\iterate\ExtensionEqualsFilter;
use io\collections\iterate\FilteredIOCollectionIterator;
use io\collections\FileCollection;
use io\collections\FileElement;
use text\json\StreamInput;
use lang\IllegalArgumentException;

class SpecTest extends \unittest\TestCase {
  protected $target= null;

  /**
   * Constructor
   * @param string $name
   * @param string $target The directory in which the spec files exist
   */
  public function __construct($name, $target= null) {
    parent::__construct($name);
    $this->target= $target;
  }

  /** @return var[][] */
  public function specifications() {
    if (null === $this->target) {
      return [];
    } else if (is_file($this->target)) {
      $files= [new FileElement($this->target)];
    } else {
      $files= new FilteredIOCollectionIterator(new FileCollection($this->target), new ExtensionEqualsFilter('json'));
    }

    // Return an array of argument lists to be passed to specification
    $r= [];
    foreach ($files as $file) {
      $spec= (new StreamInput($file->getInputStream()))->read();
      foreach ($spec as $i => $test) {
        $r[]= [isset($test['comment']) ? $test['comment'] : $file->getName().'#'.$i, $test];
      }
    }
    return $r;
  }

  #[@test, @values('specifications')]
  public function specification_met($name, $test) {
    if (isset($test['disabled'])) {
      $this->skip('Disabed');
    } else if (isset($test['error'])) {
      try {
        $changes= new Changes(...$test['patch']);
      } catch (IllegalArgumentException $expected) {
        return;  // OK
      }
      $value= $test['doc'];
      $changes= $changes->apply($value);
      $this->assertFalse($changes[key($changes)]);
    } else if (isset($test['expected'])) {
      $value= $test['doc'];
      $changes= new Changes(...$test['patch']);
      $changes->apply($value);
      $this->assertEquals($test['expected'], $value);
    } else {
      $value= $test['doc'];
      $changes= new Changes(...$test['patch']);
      $changes->apply($value);
    }
  }
}