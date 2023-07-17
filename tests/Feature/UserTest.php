<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use SebastianBergmann\Type\RuntimeException;
use Tests\TestCase;

class UserTest extends TestCase
{
    //use RefreshDatabase;
     
    protected function token(): string
    {
        $data = [
            "email" => "josephine.west@example.com",
            "password"=>"password"
        ];

        $response = $this->json('POST','/api/v1/login',$data);
        $content        = json_decode($response->getContent());
    
        if (!isset($content->data->token)) {
            throw new RuntimeException('Token missing in response');
        }

        return $content->data->token;
    }

    public function test_can_create_user()
    {
        $data = [
                "name" => "Roberto",
                "email" => "roberto11111@gmail.com.br",
                "password"=>"password"
            ];

        $resp = [
            "message" => "user inserted successfully",
            "data" => [
                "name" => "Roberto",
                "email" => "roberto11111@gmail.com.br"
            ]
        ];

        $response = $this->json('POST','/api/v1/users',$data);
        $response->assertStatus(200)
                 ->assertJson($resp);
        $this->assertDatabaseHas('users',$data);
    }

    public function test_can_update_user()
    {
        $data =  [
                "name" => "selma",
                "email" => "selma@gmail.com.br",
                "password" => "password"
        ];

        $resp = [
            "message" => "successfully updated user",
            "data" => [
                "name" => "selma",
                "email" => "selma@gmail.com.br"
            ]
        ];

        $response =  $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' .  $this->token(),
        ])->json('PUT', '/api/v1/users/32', $data);

        $response->assertStatus(200)
        ->assertJson($resp);
        $this->assertDatabaseHas('users',$data);
    }

    public function test_can_delete_user(){
        $resp =  [
        "message" => "user removed successfuly",
        "data" => []
        ];

        $response =  $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' .  $this->token(),
        ])->json('DELETE', '/api/v1/users/15');
    
        $response->assertStatus(200)
        ->assertJson($resp);
    }

}
