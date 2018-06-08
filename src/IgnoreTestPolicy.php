<?php

namespace MyBuilder\PhpunitAccelerator;

interface IgnoreTestPolicy
{
    public function shouldIgnore(\ReflectionObject $testReflection):bool;
}
