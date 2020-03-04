<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OpenGraphEndpointTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test if the og parser is reachable for unauthenticated users.
     *
     * @return void
     */
    public function testOGParserAuthentication()
    {
        $response = $this->json(
            'POST',
            '/api/content/ogparser',
            ['url' => 'https://www.codejungle.org'],
            ['HTTP_Authorization' => 'Bearer xxx']
        );

        $response->assertStatus(401);
    }

    /**
     * Test if the og parser is reachable for unauthenticated users.
     *
     * @return void
     */
    public function testOGParserResponse()
    {
        $user = factory(\App\User::class, 1)->create();

        $response = $this->json(
            'POST',
            '/api/content/ogparser',
            ['url' => 'https://www.codejungle.org'],
            ['HTTP_Authorization' => 'Bearer '.$user[0]->api_token]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(
            ['ogtags' => ['title', 'image', 'description']]
        );
    }
}
