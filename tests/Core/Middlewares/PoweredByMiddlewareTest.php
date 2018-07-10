<?php
/**
 * Fichier PoweredByMiddlewareTest.php du 10/07/2018 
 * Description : Fichier de la classe PoweredByMiddlewareTest 
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Tests\Rcnchris\Core\Middlewares
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Rcnchris\Core\Middlewares\PoweredByMiddleware;
use Tests\Rcnchris\BaseTestCase;

/**
 * Class PoweredByMiddlewareTest
 *
 * @category New
 *
 * @package Tests\Rcnchris\Core\Middlewares
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @version Release: <1.0.0>
 *
 * @link https://github.com/rcnchris on Github
 */
class PoweredByMiddlewareTest extends BaseTestCase
{
    public function testInvoke()
    {
        $this->ekoTitre('Middlewares - PoweredBy');
        $request = $this->makeRequestPsr7();
        $response = $this->makeResponsePsr7();
        $this->assertInstanceOf(
            ResponseInterface::class,
            (new PoweredByMiddleware())
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
            (new PoweredByMiddleware())
                ->withContainer(
                    $this->makeContainer([
                        'name' => 'Mathis',
                        'debug' => true
                    ])
                )
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
        $this->assertEquals('PoweredBy', (new PoweredByMiddleware())->getName());
    }

    public function testGetContainer()
    {
        $this->assertInstanceOf(
            ContainerInterface::class,
            (new PoweredByMiddleware())
                ->withContainer($this->makeContainer(['name' => 'Mathis']))
                ->getContainer()
        );
    }
}