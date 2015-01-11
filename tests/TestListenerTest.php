<?php

use MyBuilder\PhpunitAccelerator\TestListener;

class TestListenerTest extends \PHPUnit_Framework_TestCase
{
    private $listener;
    private $dummyTest;

    private $listenerFiltersShutdownFunction;
    private $dummyTestRegistersShutdownFunction;

    protected function setUp()
    {
        $this->listener = new TestListener();
        $this->dummyTest = new DummyTest();
        $this->listenerFiltersShutdownFunction = new TestListener(true);
        $this->dummyTestRegistersShutdownFunction = new DummyTestRegistersShutdownFunction();
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

    /**
     * @test
     */
    public function shouldNotFreePhpUnitPropertyIfRegistersShutdownFunction()
    {
        $this->listenerFiltersShutdownFunction->endTest(
            $this->dummyTestRegistersShutdownFunction,
            0
        );

        $this->assertNotNull($this->dummyTestRegistersShutdownFunction->property);
    }

    private function endTest()
    {
        $this->listener->endTest($this->dummyTest, 0);
    }
}

class PHPUnit_Fake extends \PHPUnit_Framework_TestCase
{
    public $phpUnitProperty = 1;
}

class DummyTest extends \PHPUnit_Fake
{
    public $property = 1;
}

class DummyTestRegistersShutdownFunction extends \PHPUnit_Fake
{
    public $property = 1;

    function foo()
    {
        register_shutdown_function(function() {return;});
    }
}
