<?php namespace text\json\patch\unittest;

use io\collections\iterate\{FilteredIOCollectionIterator, NameMatchesFilter};
use io\collections\{FileCollection, FileElement};
use lang\{IllegalArgumentException, IllegalStateException};
use test\{Args, Assert, IgnoredBecause, Test, Values};
use text\json\StreamInput;
use text\json\patch\Changes;

/**
 * Tests against specification
 *
 * ```sh
 * $ wget 'https://github.com/json-patch/json-patch-tests/archive/master.zip' -O master.zip
 * $ unzip master.zip && rm master.zip
 * $ xp test src/it/php --folder=json-patch-tests-master/
 * ```
 */
#[Args('folder')]
class SpecTest {
  private $target;

  /**
   * Constructor
   *
   * @param string $target The directory in which the spec files exist
   */
  public function __construct($target) {
    $this->target= $target;
  }

  /** @return var[][] */
  public function specifications() {
    if (is_file($this->target)) {
      $files= [new FileElement($this->target)];
    } else {
      $files= new FilteredIOCollectionIterator(
        new FileCollection($this->target),
        new NameMatchesFilter('/tests\.json$/')
      );
    }

    // Return an array of argument lists to be passed to specification
    foreach ($files as $file) {
      $input= new StreamInput($file->getInputStream());
      foreach ($input->elements() as $i => $test) {
        yield [$test['comment'] ?? $file->getName().'#'.$i, $test];
      }
    }
  }

  #[Test, Values(from: 'specifications')]
  public function specification_met($name, $test) {
    if (isset($test['disabled'])) {
      // TODO: Report reason why disabled: Assert::skipped()?
    } else if (isset($test['error'])) {
      try {
        $changes= new Changes(...$test['patch']);
      } catch (IllegalArgumentException $expected) {
        return;  // OK
      }
      Assert::false($changes->apply($test['doc'])->successful());
    } else if (isset($test['expected'])) {
      $changes= new Changes(...$test['patch']);
      $result= $changes->apply($test['doc']);
      if (!$result->successful()) {
        throw new IllegalStateException('Changes did not apply successfully: '.$result->error()->message());
      }
      Assert::equals($test['expected'], $result->value());
    } else if (isset($test['patch'])) {
      $changes= new Changes(...$test['patch']);
      Assert::true($changes->apply($test['doc'])->successful());
    }
  }
}