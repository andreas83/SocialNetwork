<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();
        Schema::dropAllTables();

        $this->runDatabaseMigrations();


    }
}
