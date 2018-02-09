<?php

use MyBuilder\PhpunitAccelerator\TestListener;
use MyBuilder\PhpunitAccelerator\IgnoreTestPolicy;

class TestListenerTest extends \PHPUnit\Framework\TestCase
{
    private $dummyTest;

    protected function setUp()
    {
        $this->dummyTest = new DummyTest();
    }

    protected function tearDown()
    {
        unset($this->dummyTest);
    }

    public function testShouldFreeTestProperty()
    {
        $this->endTest(new TestListener());

        $this->assertFreesTestProperty();
    }

    private function endTest(TestListener $listener)
    {
        $listener->endTest($this->dummyTest, 0);
    }

    private function assertFreesTestProperty()
    {
        $this->assertNull($this->dummyTest->property);
    }

    public function testShouldNotFreePhpUnitProperty()
    {
        $this->endTest(new TestListener());

        $this->assertDoesNotFreePHPUnitProperty();
    }

    private function assertDoesNotFreePHPUnitProperty()
    {
        $this->assertNotNull($this->dummyTest->phpUnitProperty);
    }

    public function testShouldNotFreeTestPropertyWithIgnoreAlwaysPolicy()
    {
        $this->endTest(new TestListener(new AlwaysIgnoreTestPolicy()));

        $this->assertDoesNotFreeTestProperty();
    }

    private function assertDoesNotFreeTestProperty()
    {
        $this->assertNotNull($this->dummyTest->property);
    }
}

class PHPUnit_Fake extends \PHPUnit\Framework\TestCase
{
    public $phpUnitProperty = 1;
}

class DummyTest extends \PHPUnit_Fake
{
    public $property = 1;
}

class AlwaysIgnoreTestPolicy implements IgnoreTestPolicy
{
    public function shouldIgnore(\ReflectionObject $testReflection)
    {
        return true;
    }
}
