<?php

namespace Tests;

use Denismitr\Laracors\Cors;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CorsMiddlewareTest extends \Orchestra\Testbench\TestCase
{

    /** @test */
    public function it_checks_if_correct_headers_are_set()
    {
        $request = $this->mockRequest("POST", 'http://example.com');

        $cors = new Cors;

        $response = new Response;

        $response = $cors->handle($request, function() use ($response) {
            return $response;
        });

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), 'http://example.com');

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Methods'),
            'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS'
        );

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Headers'),
            'Content-Type, Accept, Authorization, Location, Origin, Requested'
        );
    }

    /** @test */
    public function it_can_handle_options_request()
    {
        $request = $this->mockRequest("OPTIONS", 'http://example.com');

        $cors = new Cors;

        $response = $cors->handle($request, function()  {});

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), 'http://example.com');

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Methods'),
            'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS'
        );

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Headers'),
            'Content-Type, Accept, Authorization, Location, Origin, Requested'
        );
    }

    /** @test */
    public function cors_can_be_told_to_accept_certail_request_methods_only()
    {
        $request = $this->mockRequest("POST", 'http://example.com');

        $cors = new Cors;

        $response = new Response;

        $response = $cors->handle($request, function() use ($response) {
            return $response;
        }, 'post');

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), 'http://example.com');

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Methods'),
            'POST'
        );

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Headers'),
            'Content-Type, Accept, Authorization, Location, Origin, Requested'
        );
    }

    /** @test */
    public function cors_can_be_told_to_accept_certail_request_methods_only_multiple()
    {
        $request = $this->mockRequest("POST", 'http://example.com');

        $cors = new Cors;

        $response = new Response;

        $response = $cors->handle($request, function() use ($response) {
            return $response;
        }, 'post,put');

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), 'http://example.com');

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Methods'),
            'POST, PUT'
        );

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Headers'),
            'Content-Type, Accept, Authorization, Location, Origin, Requested'
        );
    }

    protected function mockRequest(string $method = "GET", string $origin)
    {
        $req = new Request;

        $req->setMethod($method);
        $req->headers->set('Origin', $origin);

        return $req;
    }
}