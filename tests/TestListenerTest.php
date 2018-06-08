<?php

use MyBuilder\PhpunitAccelerator\TestListener;
use MyBuilder\PhpunitAccelerator\IgnoreTestPolicy;

class TestListenerTest extends \PHPUnit\Framework\TestCase
{
    /** @var DummyTest */
    private $dummyTest;

    protected function setUp()
    {
        $this->dummyTest = new DummyTest();
    }

    public function testShouldFreeTestProperty():void
    {
        $this->endTest(new TestListener());

        $this->assertFreesTestProperty();
    }

    private function endTest(TestListener $listener):void
    {
        $listener->endTest($this->dummyTest, 0);
    }

    private function assertFreesTestProperty():void
    {
        $this->assertNull($this->dummyTest->property);
    }

    public function testShouldNotFreePhpUnitProperty():void
    {
        $this->endTest(new TestListener());

        $this->assertDoesNotFreePHPUnitProperty();
    }

    private function assertDoesNotFreePHPUnitProperty():void
    {
        $this->assertNotNull($this->dummyTest->phpUnitProperty);
    }

    public function testShouldNotFreeTestPropertyWithIgnoreAlwaysPolicy(): void
    {
        $this->endTest(new TestListener(new AlwaysIgnoreTestPolicy()));

        $this->assertDoesNotFreeTestProperty();
    }

    private function assertDoesNotFreeTestProperty():void
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
    public function shouldIgnore(\ReflectionObject $testReflection): bool
    {
        return true;
    }
}
