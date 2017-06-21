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

    /**
     * @test
     */
    public function shouldFreeTestProperty()
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

    /**
     * @test
     */
    public function shouldNotFreePhpUnitProperty()
    {
        $this->endTest(new TestListener());

        $this->assertDoesNotFreePHPUnitProperty();
    }

    private function assertDoesNotFreePHPUnitProperty()
    {
        $this->assertNotNull($this->dummyTest->phpUnitProperty);
    }

    /**
     * @test
     */
    public function shouldNotFreeTestPropertyWithIgnoreAlwaysPolicy()
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
