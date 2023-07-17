<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
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

    public function test_can_create_product()
    {
        
        $data = [
                "name"=>"Product 11",
                "price"=>"19.99",
                "description"=>"new product 11"
            ];

        $resp = [
            "message" => "product inserted successfully",
                "data" => [
                    "name" => "Product 11",
                    "price" => "19.99",
                    "description" => "new product 11",
                ]
        ];
        
        $response =  $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' .  $this->token(),
        ])->json('POST', '/api/v1/products', $data);

        $response->assertStatus(200)
                ->assertJson($resp);
        $this->assertDatabaseHas('products',$data);
    }

    public function test_product_required_name(){

        $data = [
            "name"=>null,
            "price"=>"19.99",
            "description"=>"edited"
        ];

        $resp =[
            "message" => "The given data was invalid.",
            "errors" => [
                "name" => [
                    "The name field is required."
                ]
            ]
        ];
      
        $response =  $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' .  $this->token(),
        ])->json('POST', '/api/v1/products', $data);

        $response->assertStatus(422)
        ->assertJson($resp);
   
    }
    
    public function test_show_without_token_response()
    {
        $response = $this->get('/api/v1/products/2');

        $response->assertStatus(401);
    }

    public function test_can_update_product()
    {
        
        $data =  [
            "name"=> "Product 1 updated",
            "price"=> 19.99,
            "description"=> "updated"
        ];

        $resp = [
            "message" => "successfully updated product",
            "data" => [
                "name"=> "Product 1 updated",
                "price"=> 19.99,
                "description"=> "updated"
            ]
        ];

        $response =  $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' .  $this->token(),
        ])->json('PUT', '/api/v1/products/7', $data);

        $response->assertStatus(200)
        ->assertJson($resp);
        $this->assertDatabaseHas('products',$data);
    }

    public function test_can_delete_product(){
        $resp =  [
        "message" => "product removed successfuly",
        "data" => []
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' .  $this->token(),
        ])->json('DELETE', '/api/v1/products/6');
    
        $response->assertStatus(200)
        ->assertJson($resp);
    }
}
