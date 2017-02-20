<?php

namespace MyBuilder\PhpunitAccelerator;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;

class TestListener implements \PHPUnit\Framework\TestListener
{
    private $ignorePolicy;

    const PHPUNIT_PROPERTY_PREFIX = 'PHPUnit_';

    public function __construct(IgnoreTestPolicy $ignorePolicy = null)
    {
        $this->ignorePolicy = ($ignorePolicy) ?: new NeverIgnoreTestPolicy();
    }

    public function endTest(Test $test, $time)
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

    public function startTestSuite(TestSuite $suite) {}

    public function addError(Test $test, \Exception $e, $time) {}

    public function addFailure(Test $test, AssertionFailedError $e, $time) {}

    public function addIncompleteTest(Test $test, \Exception $e, $time) {}

    public function addSkippedTest(Test $test, \Exception $e, $time) {}

    public function endTestSuite(TestSuite $suite) {}

    public function startTest(Test $test) {}

    public function addRiskyTest(Test $test, \Exception $e, $time) {}
    
    public function addWarning(Test $test, Warning $e, $time) {}
}

class NeverIgnoreTestPolicy implements IgnoreTestPolicy
{
    public function shouldIgnore(\ReflectionObject $testReflection)
    {
        return false;
    }
}
