# PHPUnit Accelerator

[![Build Status](https://secure.travis-ci.org/mybuilder/phpunit-accelerator.svg?branch=master)](http://travis-ci.org/mybuilder/phpunit-accelerator)

Inspired by [Kris Wallsmith faster PHPUnit article](http://kriswallsmith.net/post/18029585104/faster-phpunit), we've created a [PHPUnit](http://phpunit.de) test listener that speeds up PHPUnit tests about 20% by freeing memory.

## Installation

To install this library, run the command below and you will get the latest version

``` bash
composer require mybuilder/phpunit-accelerator --dev
```

## Usage

Just add to your `phpunit.xml` configuration

```xml
<phpunit>
    <listeners>
        <listener class="\MyBuilder\PhpunitAccelerator\TestListener"/>
    </listeners>
</phpunit>
```

### Ignoring Tests

Sometimes it is necessary to ignore specific tests, where freeing their properties is undesired. For this use case, you have the ability to *extend the behaviour* of the listener by implementing the `IgnoreTestPolicy` interface.

As an example, if we hypothetically wanted to ignore all tests which include "Legacy" in their test filename, we could create a custom ignore policy as follows

```php
<?php

use MyBuilder\PhpunitAccelerator\IgnoreTestPolicy;

class IgnoreLegacyTestPolicy implements IgnoreTestPolicy {
    public function shouldIgnore(\ReflectionObject $testReflection) {
        return strpos($testReflection->getFilename(), 'Legacy') !== false;
    }
}
```

And pass it to the constructor of our test listener in `phpunit.xml` configuration

```xml
<phpunit>
    <listeners>
        <listener class="\MyBuilder\PhpunitAccelerator\TestListener">
            <arguments>
                <object class="\IgnoreLegacyTestPolicy"/>
            </arguments>
        </listener>
    </listeners>
</phpunit>
```

---

Created by [MyBuilder](http://www.mybuilder.com/) - Check out our [blog](http://tech.mybuilder.com/) for more insight into this and other open-source projects we release.
