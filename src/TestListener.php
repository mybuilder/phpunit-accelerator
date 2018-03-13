<?php

namespace MyBuilder\PhpunitAccelerator;

use PHPUnit\Framework\TestListener as BaseTestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;

class TestListener implements BaseTestListener
{
    use TestListenerDefaultImplementation;

    private $ignorePolicy;

    const PHPUNIT_PROPERTY_PREFIX = 'PHPUnit_';

    public function __construct(IgnoreTestPolicy $ignorePolicy = null)
    {
        $this->ignorePolicy = ($ignorePolicy) ?: new NeverIgnoreTestPolicy();
    }

    public function endTest(\PHPUnit\Framework\Test $test, float $time): void
    {
        $testReflection = new \ReflectionObject($test);

        if ($this->ignorePolicy->shouldIgnore($testReflection)) {
            return;
        }

        $this->safelyFreeProperties($test, $testReflection->getProperties());
    }

    private function safelyFreeProperties(\PHPUnit\Framework\Test $test, array $properties)
    {
        foreach ($properties as $property) {
            if ($this->isSafeToFreeProperty($property)) {
                $this->freeProperty($test, $property);
            }
        }
    }

    private function isSafeToFreeProperty(\ReflectionProperty $property)
    {
        return !$property->isStatic() && $this->isNotPhpUnitProperty($property);
    }

    private function isNotPhpUnitProperty(\ReflectionProperty $property)
    {
        return 0 !== strpos($property->getDeclaringClass()->getName(), self::PHPUNIT_PROPERTY_PREFIX);
    }

    private function freeProperty(\PHPUnit\Framework\Test $test, \ReflectionProperty $property)
    {
        $property->setAccessible(true);
        $property->setValue($test, null);
    }
}

class NeverIgnoreTestPolicy implements IgnoreTestPolicy
{
    public function shouldIgnore(\ReflectionObject $testReflection)
    {
        return false;
    }
}
