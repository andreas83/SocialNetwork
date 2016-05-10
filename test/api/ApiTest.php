<?php
use SocialNetwork\app\lib\Config;


class Api_Test extends PHPUnit_Framework_TestCase
{
    protected $client;

    
    /**
     * @todo use url from config 
     * @todo mookup test db
     */
    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://dmdn'
        ]);
    }
    

    /*
     * we create a new content element
     */
    public function testPost_ContentObject()
    {
        $response = $this->client->post('/api/content/', [
            'form_params' => [
                'content' => "cat",
                'api_key' => "empty"
            ]
        ]);

        self::assertEquals(200, $response->getStatusCode());
        
        
        $data = json_decode($response->getBody());

        self::assertObjectHasAttribute('id', $data);
        self::assertObjectHasAttribute('status', $data);
        self::assertAttributeGreaterThan(0, "id", $data);
    }

    public function testGet_ValidInput_ContentObject()
    {
        $response = $this->client->get('/api/content/', [
            'query' => [
                'hash' => 'cat'
            ]
        ]);

        self::assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        self::assertArrayHasKey('stream', $data[0]);
        self::assertArrayHasKey('author', $data[0]);

    }

    
    public function testDelete_Error()
    {
        $response = $this->client->delete('/api/content/1', [
            'http_errors' => false
        ]);

        self::assertEquals(403, $response->getStatusCode());
    }
    
    
    public function testEndpointnotFound_Error()
    {
        $response = $this->client->get('/api/content/404', [
            'http_errors' => false
        ]);

        self::assertEquals(404, $response->getStatusCode());
    }
}