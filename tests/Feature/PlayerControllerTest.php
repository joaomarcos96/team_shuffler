<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerControllerTest extends TestCase
{
    public function testShouldGetDefaultRoute()
    {
        $response = $this->get('/');
        $response->assertSuccessful();
    }

    public function testShouldGetIndex()
    {
        $response = $this->get('/players');
        $response->assertSuccessful();
    }

    public function testShouldGetCreate()
    {
        $response = $this->get('/players/create');
        $response->assertSuccessful();
    }
}
