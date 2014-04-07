<?php

namespace MyBuilder\PhpunitAccelerator;

class TestListener implements \PHPUnit_Framework_TestListener
{
    const PHPUNIT_PROPERTY_PREFIX = 'PHPUnit_';

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        $this->safelyFreeProperties($test);
    }

    private function safelyFreeProperties($test)
    {
        foreach ($this->getProperties($test) as $property) {
            if ($this->isSafeToFreeProperty($property)) {
                $this->freeProperty($test, $property);
            }
        }
    }

    private function getProperties($test)
    {
        $reflection = new \ReflectionObject($test);

        return $reflection->getProperties();
    }

    private function isSafeToFreeProperty($property)
    {
        return !$property->isStatic() && $this->isNotPhpUnitProperty($property);
    }

    private function isNotPhpUnitProperty($property)
    {
        return 0 !== strpos($property->getDeclaringClass()->getName(), self::PHPUNIT_PROPERTY_PREFIX);
    }

    private function freeProperty($test, $property)
    {
        $property->setAccessible(true);
        $property->setValue($test, null);
    }

    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite) {}

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time) {}

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time) {}

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time) {}

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time) {}

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite) {}

    public function startTest(\PHPUnit_Framework_Test $test) {}

    public function addRiskyTest(\PHPUnit_Framework_Test $test, \Exception $e, $time) {}
}
