<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class LoginControllerTest extends TestCase
{

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
      $this->seed(UsersTableSeeder::class);

      $user = ['email' => 'user@email.com', 'password' => 'userpass'];
      $this->json('POST', 'api/login', $user)
          ->assertStatus(200)
          ->assertJsonStructure([
              'token',
              'user' => [
                  'id',
                  'name',
                  'email',
                  'created_at',
                  'updated_at'
              ]
          ]);
  }

  public function testLogoutSuccessfully()
  {
      $user = ['email' => 'user@email.com',
          'password' => 'userpass'
      ];

      Auth::attempt($user);
      $token = Auth::user()->createToken('nfce_client')->accessToken;
      $headers = ['Authorization' => "Bearer $token"];
      $this->json('GET', 'api/logout', [], $headers)
          ->assertStatus(204);
  }
}
