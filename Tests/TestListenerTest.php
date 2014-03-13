<?php

namespace PhpunitAccelerator\Tests;

use MyBuilder\PhpunitAccelerator\TestListener;

class DummyTest extends \PHPUnit_Fake
{
    public $property = 1;
}

class TestListenerTest extends \PHPUnit_Framework_TestCase
{
    private $listener;
    private $dummyTest;

    public function setUp()
    {
        $this->listener = new TestListener;
        $this->dummyTest = new DummyTest;
    }

    /**
     * @test
     */
    public function shouldFreeProperty()
    {
        $this->endTest();

        $this->assertNull($this->dummyTest->property);
    }

    /**
     * @test
     */
    public function shouldNotFreePhpUnitProperty()
    {
        $this->endTest();

        $this->assertNotNull($this->dummyTest->phpUnitProperty);
    }

    private function endTest()
    {
        $this->listener->endTest($this->dummyTest, 0);
    }
}
