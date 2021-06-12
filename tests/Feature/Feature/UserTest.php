<?php

namespace Tests\Feature\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Factories\UserFactory;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserRegistration()
    {
        $factory = new UserFactory();
        $data = $factory->definition();

        $response = $this->post('api/register', $data);   
    
        $response->assertStatus(201);
    }
}
