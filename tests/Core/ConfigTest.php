<?php
namespace Tests\Rcnchris\Core;

use Psr\Container\ContainerInterface;
use Rcnchris\Core\Config;
use Tests\Rcnchris\BaseTestCase;

class ConfigTest extends BaseTestCase {

    /**
     * Configuration de test
     *
     * @var array
     */
    private $config;

    public function setUp()
    {
        parent::setUp();
        $this->config = [
            'appName' => 'Test unitaire'
        ];
    }

    public function makeConfig($config = null)
    {
        if (is_null($config)) {
            $config = $this->config;
        }
        return new Config($config);
    }

    public function testInstance()
    {
        $this->ekoTitre('Application - Configuration');
        $this->assertInstanceOf(
            ContainerInterface::class
            , $this->makeConfig($this->config)
            , $this->getMessage("L'instance attendue est incorrecte")
        );
    }

    public function testInstanceWithObject()
    {
        $o = new \stdClass();
        $o->appName = 'Test unitaire';
        $this->assertInstanceOf(
            ContainerInterface::class
            , $this->makeConfig($o)
            , $this->getMessage("L'instance attendue est incorrecte")
        );
    }

    public function testGet()
    {
        $c = $this->makeConfig();
        $this->assertEquals(
            'Test unitaire'
            , $c->get('appName')
            , $this->getMessage("La clé devrait exister")
        );
        $this->assertEquals(
            'Test unitaire'
            , $c->appName
            , $this->getMessage("La clé devrait exister")
        );
    }

    public function testHas()
    {
        $this->assertTrue(
            $this->makeConfig()->has('appName')
            , $this->getMessage("La clé attendue est introuvable")
        );
    }
}
