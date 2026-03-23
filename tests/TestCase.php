<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\UsesProjectSchema;

abstract class TestCase extends BaseTestCase
{
    use UsesProjectSchema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpProjectSchema();
    }
}
