<?php
namespace Tests\Rcnchris\Core\Html;

use Rcnchris\Core\Config\ConfigContainer;
use Tests\Rcnchris\BaseTestCase;

class ConfigContainerTest extends BaseTestCase
{
    /**
     * @var ConfigContainer
     */
    private $config;

    public function setUp()
    {
        $this->config = new ConfigContainer();
    }

    public function testInstance()
    {
        $this->ekoTitre('Config - Container');
        $this->assertInstanceOf(ConfigContainer::class, $this->config);
    }

    public function testHelp()
    {
        $this->assertHasHelp($this->config);
    }

    public function testSet()
    {
        $this->config->set('test', 'ola');
        $this->assertTrue($this->config->has('test'));
        $this->assertEquals('ola', $this->config->get('test'));
    }

    public function testDel()
    {
        $this->config->set('test', 'ola');
        $this->config->del('test');
        $this->assertFalse($this->config->has('test'));
    }

    public function testMagicGet()
    {
        $this->config->set('test', 'ola');
        $this->assertEquals('ola', $this->config->test);
    }

    public function testAll()
    {
        $this->config->set('test', 'ola');
        $this->config->set('other', 'oli');
        $all = $this->config->all();
        $this->assertInternalType('array', $all);
        $this->assertNotEmpty($all);
        $this->assertCount(2, $all);
    }

    public function testImplementInterfaces()
    {
        $this->assertObjectImplementInterfaces($this->config, ['ArrayAccess', 'Psr\Container\ContainerInterface']);
    }

    public function testImplementArrayAccess()
    {
        $this->config->set('test', 'ola');
        $this->assertArrayAccess($this->config, 'test', 'ola');
    }
}
