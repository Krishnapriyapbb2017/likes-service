<?php
/**
 * Created by PhpStorm.
 * User: Arun
 * Date: 14.08.19
 * Time: 23:06
 */

namespace Tests\Feature;


use App\Repository\EmailService\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class EmailServiceTest extends TestCase
{
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new Service();
    }

    /** @test */
    public function can_send_email()
    {
        $response = $this->guzzleMockResponse(File::get(base_path('/tests/Responses/email-service.json')), 200, 'post');
        $this->assertTrue($this->service->handleResponseCode($response)['status']);
    }

    /** @test */
    public function handle_too_many_request()
    {
        $response = $this->guzzleMockResponse(File::get(base_path('/tests/Responses/email-service.json')), 429, 'post');
        $this->assertFalse($this->service->handleResponseCode($response)['status']);
    }

    /** @test */
    public function handle_5xx_errors()
    {
        $response = $this->guzzleMockResponse(File::get(base_path('/tests/Responses/email-service.json')), 500, 'post');
        $this->assertFalse($this->service->handleResponseCode($response)['status']);
    }


    private function guzzleMockResponse($body, $statusCode, $verb)
    {
        $headers = ['Content-Type' => 'application/json'];
        $response = new Response($statusCode, $headers, $body);

        $mock = new MockHandler([
            $response
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        return $client->$verb('/', ['http_errors' => false]);
    }
}