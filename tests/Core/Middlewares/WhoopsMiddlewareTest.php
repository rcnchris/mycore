<?php
namespace Tests\Rcnchris\Core\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Rcnchris\Core\Middlewares\WhoopsMiddleware;
use Tests\Rcnchris\BaseTestCase;

class WhoopsMiddlewareTest extends BaseTestCase
{
    public function testInvoke()
    {
        $this->ekoTitre('Middlewares - Whoops');
        $request = $this->makeRequestPsr7();
        $response = $this->makeResponsePsr7();
        $this->assertInstanceOf(
            ResponseInterface::class,
            (new WhoopsMiddleware())
                ->__invoke(
                    $request,
                    $response,
                    function ($request, $response) {
                        return $response;
                    }
                )
        );
    }
}
