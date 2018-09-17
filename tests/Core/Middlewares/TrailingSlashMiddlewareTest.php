<?php
namespace Tests\Rcnchris\Core\Middlewares;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Rcnchris\Core\Middlewares\TrailingSlashMiddleware;
use Tests\Rcnchris\BaseTestCase;

class TrailingSlashMiddlewareTest extends BaseTestCase
{
    public function testInvoke()
    {
        $this->ekoTitre('Middlewares - Trailing Slash');
        $request = $this
            ->makeRequestPsr7()
            ->withUri(new Uri('http://192.168.1.12/ola/'));
        $response = $this->makeResponsePsr7();
        $newResponse = (new TrailingSlashMiddleware())
            ->__invoke(
                $request,
                $response,
                function ($request, $response) {
                    return $response;
                }
            );
        $this->assertInstanceOf(ResponseInterface::class, $newResponse);
    }
}
