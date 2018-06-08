<?php

namespace MyBuilder\PhpunitAccelerator;

use PHPUnit\Framework\TestListener as BaseTestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\Test;

class TestListener implements BaseTestListener
{
    use TestListenerDefaultImplementation;

    private $ignorePolicy;

    private const PHPUNIT_PROPERTY_PREFIX = 'PHPUnit_';

    public function __construct(IgnoreTestPolicy $ignorePolicy = null)
    {
        $this->ignorePolicy = $ignorePolicy ?: new NeverIgnoreTestPolicy();
    }

    public function endTest(Test $test, float $time): void
    {
        $testReflection = new \ReflectionObject($test);

        if ($this->ignorePolicy->shouldIgnore($testReflection)) {
            return;
        }

        $this->safelyFreeProperties($test, $testReflection->getProperties());
    }

    private function safelyFreeProperties(Test $test, array $properties)
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

    private function freeProperty(Test $test, \ReflectionProperty $property)
    {
        $property->setAccessible(true);
        $property->setValue($test, null);
    }
}

class NeverIgnoreTestPolicy implements IgnoreTestPolicy
{
    public function shouldIgnore(\ReflectionObject $testReflection): bool
    {
        return false;
    }
}
