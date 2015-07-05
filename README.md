JSON Patch
==========

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/json-patch.svg)](http://travis-ci.org/xp-forge/json-patch)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.6+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_6plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Supports HHVM 3.5+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_5plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/json-patch/version.png)](https://packagist.org/packages/xp-forge/json-patch)

Implements JSON patch documents described in [RFC #6902](http://tools.ietf.org/html/rfc6902) and JSON Pointer from [RFC #6901](http://tools.ietf.org/html/rfc6901). Tested against the spec available at https://github.com/json-patch/json-patch-tests. See also http://jsonpatch.com/. 

Example: JSON Patch
-------------------
The entry point class is `text.json.patch.Changes`:

```php
use text\json\patch\Changes;
use text\json\patch\AddOperation;

// You can create changes via maps...
$changes= new Changes(
  ['op' => 'add', 'path' => '/biscuits/1', 'value' => ['name' => 'Ginger Nut']]
);

// ...or by using Operation instances
$changes= new Changes(
  new AddOperation('/biscuits/1', ['name' => 'Ginger Nut'])
);

// If you have a JSON patch document, use the spread operator
$patch= [
  ['op' => 'add', 'path' => '/biscuits/1', 'value' => ['name' => 'Ginger Nut']]
];
$changes= new Changes(...$patch);
```

To apply the changes, call the `apply()` method and work with the result:

```php
$document= [
  'biscuits' => [
    ['name' => 'Digestive'],
    ['name' => 'Choco Liebniz']
  ]
];

$changed= $changes->apply($document);

// $changed->successful() := true
// $changed->value() := [
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
// only be invoked if applying the operation succeeds, otheriwse an Error
// will be returned.
$result= $pointer->remove()->then(function() use($pointer) {
  return $pointer->add('Choco Liebniz');
});
```