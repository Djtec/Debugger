Djtec/Debugger
============

PHP Debugger 1.0.0

![debugger](http://djtec.github.com/Debugger/images/demo.png)

Requirement
-----------
PHP 5.3.0

Installation
-----------
Via composer
```
require: {
    "djtec/debugger": "dev-master"
}
```

Usage
-----------
```php
use Djtec/Debugger;
echo Debugger::run($var);
```