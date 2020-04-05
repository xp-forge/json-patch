JSON patch change log
=====================

## ?.?.? / ????-??-??

## 1.1.2 / 2020-04-05

* Implemented RFC #335: Remove deprecated key/value pair annotation syntax
  (@thekid)

## 1.1.1 / 2020-04-05

* Made compatible with XP 10 - @thekid

## 1.1.0 / 2018-12-30

* Merged PR #1: Make compatible with newer XP / PHP versions - @thekid

## 1.0.0 / 2016-02-22

* Added version compatibility with XP 7 - @thekid

## 0.3.0 / 2015-07-05

* Code quality assurance: Add api documentation where missing
  (@thekid)
* Verify escaping works as described in JSON pointer RFC
  (@thekid)

## 0.2.0 / 2015-07-05

* Made it possible to shorten array by allowing the remove operation in
  conjunction with "-". This is not specified by the RFC, but I've seen
  it implemented [in other places](https://github.com/raphaelstolt/php-jsonpatch/blob/master/tests/integration/Rs/Json/PatchRemoveTest.php).
  (@thekid)
* Made it possible to modify, add and remove "-" keys from objects.
  (@thekid)
* Fixed RFC compliance problem: Numbers must be *numerically* equal, not
  strictly; meaning `1.0` equals `1` (and vice versa).
  (@thekid)

## 0.1.0 / 2015-07-05

* Hello World! First release - @thekid