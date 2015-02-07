Djtec/Debugger
============

[![Latest Stable Version](https://poser.pugx.org/djtec/debugger/v/stable.svg)](https://packagist.org/packages/djtec/debugger)
[![Total Downloads](https://poser.pugx.org/djtec/debugger/downloads.svg)](https://packagist.org/packages/djtec/debugger)

PHP Debugger 1.0.0

![debugger](http://djtec.github.com/Debugger/images/demo.png)

Requirement
-----------
PHP 5.3.0

Installation
-----------
Via composer
```
"require": {
    "djtec/debugger": "1.0.0"
}
```

Usage
-----------
```php
use Djtec/Debugger;
echo Debugger::run($var);
```