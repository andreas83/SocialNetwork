<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginControllerTest extends TestCase
{
  use DatabaseTransactions;

  public function testRequireEmailAndLogin()
  {
      $this->json('POST', 'api/login')
          ->assertStatus(422)
          ->assertJson([
              'message' => 'The given data was invalid.',
              'errors' => [
                  'email' => ['The email field is required.'],
                  'password' => ['The password field is required.']
              ]
          ]);

  }

  public function testUserLoginSuccessfully()
  {


      $user = ['email' => 'user@email.com', 'password' => 'userpass'];
      $this->json('POST', 'api/login', $user)
          ->assertStatus(200)
          ->assertJsonStructure([

              'user' => [
                  'id',
                  'name',
                  'email',
                  'created_at',
                  'updated_at',
                  'api_token'
              ]
          ]);
  }


}
