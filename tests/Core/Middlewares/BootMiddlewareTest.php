<?php
namespace Tests\Rcnchris\Core\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Rcnchris\Core\Middlewares\BootMiddleware;
use Tests\Rcnchris\BaseTestCase;

class BootMiddlewareTest extends BaseTestCase
{
    public function testInvoke()
    {
        $this->ekoTitre('Middlewares - Boot');
        $request = $this->makeRequestPsr7();
        $response = $this->makeResponsePsr7();
        $this->assertInstanceOf(
            ResponseInterface::class,
            (new BootMiddleware())
                ->__invoke(
                    $request,
                    $response,
                    function ($request, $response) {
                        return $response;
                    }
                )
        );
    }

    public function testInvokeWithContainer()
    {
        $request = $this->makeRequestPsr7();
        $response = $this->makeResponsePsr7();
        $this->assertInstanceOf(
            ResponseInterface::class,
            (new BootMiddleware())
                ->withContainer($this->makeContainer([
                    'name' => 'Mathis',
                    'debug' => true,
                    'timezone' => new \DateTimeZone('Europe/Paris'),
                    'charset' => 'utf-8',
                    'locale' => 'fr_FR',
                ]))
                ->__invoke(
                    $request,
                    $response,
                    function ($request, $response) {
                        return $response;
                    }
                )
        );
    }

    public function testGetName()
    {
        $this->assertEquals('Boot', (new BootMiddleware())->getName());
    }

    public function testGetContainer()
    {
        $this->assertInstanceOf(
            ContainerInterface::class,
            (new BootMiddleware())
                ->withContainer($this->makeContainer(['name' => 'Mathis']))
                ->getContainer()
        );
    }
}
