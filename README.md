JSON Patch
==========

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/json-patch.svg)](http://travis-ci.org/xp-forge/json-patch)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.6+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_6plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Supports HHVM 3.5+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_5plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/json-patch/version.png)](https://packagist.org/packages/xp-forge/json-patch)

Implements JSON patch documents described in [RFC #6902](http://tools.ietf.org/html/rfc6902).

Examples
--------
*See http://jsonpatch.com/:*

```php
use text\json\patch\Changes;

$document= [
  'biscuits' => [
    ['name' => 'Digestive'],
    ['name' => 'Choco Liebniz']
  ]
];

$changes= new Changes(
  ['op' => 'add', 'path' => '/biscuits/1', 'value' => ['name' => 'Ginger Nut']]
);
$changed= $changes->apply($document);

// $changed->value() := [
//  'biscuits' => [
//    ['name' => 'Digestive'],
//    ['name' => 'Ginger Nut'],
//    ['name' => 'Choco Liebniz']
//  ]
//];
```