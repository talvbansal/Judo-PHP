<?php

namespace Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function setUp(): void
    {
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__.'/../');
        $dotenv->load();
    }
}
