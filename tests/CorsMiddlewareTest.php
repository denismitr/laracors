<?php

namespace Tests;

use Denismitr\Laracors\Cors;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CorsMiddlewareTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('laracors', [
            'allowed_origins' => [
                "/.+/"
            ],

            'all_allowed_methods' => [
                'HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'
            ],

            'allowed_headers' => [
                'Content-Type', 'Accept', 'Authorization', 'Location', "Origin", 'Requested'
            ]
        ]);
}

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
    public function it_can_change_allowed_headers_through_config()
    {
        $this->app['config']->set('laracors.allowed_headers', [
            'Content-Type', 'Authorization'
        ]);

        $request = $this->mockRequest("OPTIONS", 'http://example.com');
        $response = new Response;
        $cors = new Cors;

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
            'Content-Type, Authorization'
        );
    }

    /** @test */
    public function it_can_change_allowed_origins_through_config()
    {
        $this->app['config']->set('laracors.allowed_origins', [
            "/http(s)?:\/\/(www\.)?localhost(:[0-9]+)?/"
        ]);

        $request = $this->mockRequest("OPTIONS", 'http://localhost:8000');
        $response = new Response;
        $cors = new Cors;

        $response = $cors->handle($request, function() use ($response) {
            return $response;
        });

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), 'http://localhost:8000');

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
    public function it_can_change_allowed_origins_through_config_and_prohibt_headers_for_non_match()
    {
        $this->app['config']->set('laracors.allowed_origins', [
            "/http(s)?:\/\/(www\.)?localhost(:[0-9]+)?/"
        ]);

        $request = $this->mockRequest("OPTIONS", 'http://example.com');
        $response = new Response;
        $cors = new Cors;

        $response = $cors->handle($request, function() use ($response) {
            return $response;
        });

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), null);

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Methods'),
            null
        );

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Headers'),
            null
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
        }, 'get,post,put');

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), 'http://example.com');

        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Methods'),
            'GET, POST, PUT'
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