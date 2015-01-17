<?php

namespace MyBuilder\PhpunitAccelerator;

interface IgnoreTestPolicy
{
    /**
     * @return boolean
     */
    public function shouldIgnore(\ReflectionObject $testReflection);
}
