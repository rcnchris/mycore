<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\AbstractSynology;
use Tests\Rcnchris\BaseTestCase;

class AbstractSynologyTest extends BaseTestCase {

    /**
     * Instance
     *
     * @var AbstractSynology
     */
    private $abstract;

    /**
     * Configuration de connexion
     *
     * @var array
     */
    private $config;

    public function setUp()
    {
        $this->config = [
            'name' => 'nas',
            'description' => 'Nas du salon',
            'address' => '192.168.1.2',
            'port' => 5551,
            'protocol' => 'http',
            'version' => 1,
            'ssl' => false,
            'user' => 'rcn',
            'pwd' => 'maracla'
        ];
    }

    /**
     * @param array $config
     *
     * @return \Rcnchris\Core\Apis\Synology\AbstractSynology
     */
    public function makeAbstract(array $config)
    {
        return new AbstractSynology($config);
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology');
        $this->assertInstanceOf(AbstractSynology::class, $this->makeAbstract($this->config));
    }

    public function testInstanceWithWrongConfig()
    {
        $config = [
            'fake' => 'zob'
        ];
        $this->expectException(\Exception::class);
        $this->makeAbstract($config);
    }

    public function testGetConfig()
    {
        $api = $this->makeAbstract($this->config);
        $this->assertNotEmpty($api->getConfig());
        $this->assertEquals('nas', $api->getConfig('name'));
        $this->assertEmpty($api->getConfig('fake'));
    }
}
