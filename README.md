[![PHP version](https://img.shields.io/badge/PHP-%3E%3D8.0-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/juliangut/php-cs-fixer-custom-fixers.svg?style=flat-square)](https://packagist.org/packages/juliangut/php-cs-fixer-custom-fixers)
[![License](https://img.shields.io/github/license/juliangut/php-cs-fixer-custom-fixers.svg?style=flat-square)](https://github.com/juliangut/php-cs-fixer-custom-fixers/blob/master/LICENSE)

[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/php-cs-fixer-custom-fixers.svg?style=flat-square)](https://packagist.org/packages/juliangut/php-cs-fixer-custom-fixers/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/juliangut/php-cs-fixer-custom-fixers.svg?style=flat-square)](https://packagist.org/packages/juliangut/php-cs-fixer-custom-fixers/stats)

# php-cs-fixer-custom-fixers

Custom fixers for [PHP-CS-Fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer/)

## Installation

### Composer

```
composer require --dev juliangut/php-cs-fixer-custom-fixers
```

## Usage

Register custom fixers on PHP-CS-Fixer configuration

```diff
 <?php

 use Jgut\PhpCsFixerCustomFixers\Fixers;
 use Jgut\PhpCsFixerCustomFixers\Fixer\Comment\LeadingUppercaseCommentFixer;
 use Jgut\PhpCsFixerCustomFixers\Fixer\Comment\PhpdocLeadingUppercaseSummaryFixer;
 use Jgut\PhpCsFixerCustomFixers\Fixer\LanguageConstruct\FloatLeadingZeroFixer;
 use PhpCsFixer\Config;

 return (new Config())
+    ->registerCustomFixers(new Fixers())
     ->setRules([
         '@PSR2' => true,
         // ...,
+        LeadingUppercaseCommentFixer::name() => true,
+        PhpdocLeadingUppercaseSummaryFixer::name() => true,
+        FloatLeadingZeroFixer::name() => true,
     ]);
```

## Fixers

### LanguageConstruct

#### FloatLeadingZeroFixer

Float values should or should not have a leading zero

```diff
 <?php
 
 class Foo
 {
-    private float $value = .5;
+    private float $value = 0.5;
 }
```

##### Configuration

__leading_zero__  (string), should `add` or should `remove` a leading zero

### Comment

#### LeadingUppercaseCommentFixer

Comments should start with a leading uppercase letter

```diff
 <?php

-// this is a comment
+// This is a comment

 /*
- * this is a block comment
+ * This is a block comment
  */
```

#### PhpdocLeadingUppercaseSummaryFixer

Docblock summary should start with a leading uppercase letter

```diff
 <?php

 class Foo
 {
     /**
-     * this is a docblock summary.
+     * This is a docblock summary.
      */
     public function bar()
     {
     }
 }
```

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/php-cs-fixer-custom-fixers/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/juliangut/php-cs-fixer-custom-fixers/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/juliangut/php-cs-fixer-custom-fixers/blob/master/LICENSE) included with the source code for a copy of the license terms.
