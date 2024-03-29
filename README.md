JSON Patch
==========

[![Build status on GitHub](https://github.com/xp-forge/json-patch/workflows/Tests/badge.svg)](https://github.com/xp-forge/json-patch/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/json-patch/version.png)](https://packagist.org/packages/xp-forge/json-patch)

Implements JSON patch documents described in [RFC #6902](http://tools.ietf.org/html/rfc6902) and JSON Pointer from [RFC #6901](http://tools.ietf.org/html/rfc6901). Tested against the spec available at https://github.com/json-patch/json-patch-tests. See also http://jsonpatch.com/. 

Example: JSON Patch
-------------------
The entry point class is `text.json.patch.Changes`:

```php
use text\json\patch\{Changes, TestOperation, AddOperation};

// You can create changes via maps...
$changes= new Changes(
  ['op' => 'test', 'path' => '/best', 'value' => 'Choco Liebniz'],
  ['op' => 'add', 'path' => '/biscuits/1', 'value' => ['name' => 'Ginger Nut']]
);

// ...or by using Operation instances
$changes= new Changes(
  new TestOperation('/best', 'Choco Liebniz'),
  new AddOperation('/biscuits/1', ['name' => 'Ginger Nut'])
);

// If you have a JSON patch document, use the spread operator
$patch= [
  ['op' => 'test', 'path' => '/best', 'value' => 'Choco Liebniz'],
  ['op' => 'add', 'path' => '/biscuits/1', 'value' => ['name' => 'Ginger Nut']]
];
$changes= new Changes(...$patch);
```

Available operations are:

* `AddOperation(string $path, var $value)` - The "add" operation performs one of the following functions, depending upon what the target location references.
* `RemoveOperation(string $path)` - The "remove" operation removes the value at the target location.
* `ReplaceOperation(string $path, var $value)` - The "replace" operation replaces the value at the target location with a new value. 
* `MoveOperation(string $from, string $to)` - The "move" operation removes the value at a specified location and adds it to the target location.
* `CopyOperation(string $from, string $to)` - The "copy" operation copies the value at a specified location to the target location.
* `TestOperation(string $path, var $value)` - The "test" operation tests that a value at the target location is equal to a specified value.

To apply the changes, call the `apply()` method and work with the result:

```php
$document= [
  'best' => 'Choco Liebniz',
  'biscuits' => [
    ['name' => 'Digestive'],
    ['name' => 'Choco Liebniz']
  ]
];

$changed= $changes->apply($document);

// $changed->successful() := true
// $changed->value() := [
//  'best' => 'Choco Liebniz',
//  'biscuits' => [
//    ['name' => 'Digestive'],
//    ['name' => 'Ginger Nut'],
//    ['name' => 'Choco Liebniz']
//  ]
//];
```

Example: JSON Pointer
---------------------
You can also use the "raw" functionality underneath the `Changes` instance.

```php
use text\json\patch\Pointer;

$document= [
  'biscuits' => [
    ['name' => 'Digestive'],
    ['name' => 'Choco Liebniz']
  ]
];

$pointer= new Pointer('/biscuits/1');

// $pointer->exists() := true
// $pointer->value() := ['name' => 'Ginger Nut'];

// This will return an text.json.patch.Applied instance. Use its isError() 
// method to discover whether an error occurred.
$result= $pointer->modify('Ginger Nut');

// You can chain calls using the then() method. Closures passed to it will
// only be invoked if applying the operation succeeds, otherwise an Error
// will be returned.
$result= $pointer->remove()->then(function() use($pointer) {
  return $pointer->add('Choco Liebniz');
});
```