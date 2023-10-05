<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{

    public function testExample()
    {
        $response = $this->get('/login/customer');
//        dd($response);

        $response->assertStatus(200);
    }
}
