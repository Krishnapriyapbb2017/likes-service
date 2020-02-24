<?php
/**
 * Created by PhpStorm.
 * User: Arun
 * Date: 14.08.19
 * Time: 16:58
 */

namespace Tests\Unit;


use Tests\TestCase;

class ValidationJsonTest extends TestCase
{
    /** @test */
    public function fail_invalid_json()
    {
        $response = $this->json('POST',
            '/api/likes',
            ['user_id' => '1']
        );
        $response->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function pass_valid_json()
    {
        $response = $this->json('POST',
            '/api/likes',
            ['user_id' => 4]
        );
        $response->assertJsonMissingValidationErrors(['user_id']);
    }
}