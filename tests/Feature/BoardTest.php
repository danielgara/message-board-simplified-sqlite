<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BoardTest extends TestCase
{
    /**
     * Test invalid url
     *
     * @return void
     */
    public function test_invalid_url()
    {
        $response = $this->getJson('api/v1/thread');

        $response->assertStatus(404);
    }

    /**
     * Test the first thread in sqlite database
     *
     * @return void
     */
    public function test_find_first_thread_json()
    {
        $response = $this->getJson('api/v1/threads');

        $response
            ->assertJson(fn (AssertableJson $json) => $json->where('threads.current_page', 1)
                    ->where('threads.data.0.id', 1)
                    ->where('threads.data.0.title', 'Story 1')
                    ->etc()
            );
    }
}
