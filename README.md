PHPUnit Accelerator
===================
[![Build Status](https://secure.travis-ci.org/mybuilder/phpunit-accelerator.svg?branch=master)](http://travis-ci.org/mybuilder/phpunit-accelerator)

Inspired by [Kris Wallsmith faster PHPUnit article](http://kriswallsmith.net/post/18029585104/faster-phpunit), we've created a [PHPUnit](http://phpunit.de) test listener that speeds up PHPUnit tests about 20% by freeing memory.

Setup and Configuration
-----------------------
Add the following to your `composer.json` file
```json
{
    "require-dev": {
        "mybuilder/phpunit-accelerator": "~1.0"
    }
}
```

Update the vendor libraries

    curl -s http://getcomposer.org/installer | php
    php composer.phar install

Usage
-----
Just add to your `phpunit.xml` configuration
```xml
<phpunit>
    <listeners>
        <listener class="\MyBuilder\PhpunitAccelerator\TestListener"/>
    </listeners>
</phpunit>
```
