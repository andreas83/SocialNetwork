<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiGetContentTest extends TestCase
{
    /**
     * Simple get example
     *
     * @return void
     */
    public function testGetContent()
    {
        $this->get('/api/content');
        
        
        $json=json_decode($this->response->getContent());

        $this->assertEquals(
            50, count($json)
        );
        $this->assertResponseOk();
    }

    /**
     * Test if the show parameter works
     */
    public function testGetWithLimitContent()
    {
        $this->get('/api/content?show=10');
        $json=json_decode($this->response->getContent());

        $this->assertEquals(
            10, count($json)
        );
    }

    /**
     * test if the id and show parameter works in combination
     */
    public function testGetWithIDContent()
    {
        $this->get('/api/content?id=10&show=1');
        $json=json_decode($this->response->getContent());
      
        $this->assertEquals(
            10, $json[0]->id
        );
        
    }
    

}
