<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Common;
use Tests\Rcnchris\BaseTestCase;

class CommonTest extends BaseTestCase
{

    public function testGetInstance()
    {
        $this->ekoTitre('Tools - Common');
        $common = new Common();
        $this->assertInstanceOf(
            Common::class
            , $common
            , $this->getMessage("L'instance retournée n'est pas la bonne")
        );
    }

    public function testObjectToArray()
    {
        $o = new \stdClass();
        $o->name = 'Mathis';
        $o->birthday = date('d-m-Y');
        $a = Common::toArray($o);
        $this->assertEquals(
            ['name' => 'Mathis', 'birthday' => date('d-m-Y')]
            , $a
            , $this->getMessage("Le tableau n'est pas correct")
        );
    }

    public function testArrayToArray()
    {
        $a = ['name' => 'Mathis', 'birthday' => date('d-m-Y')];
        $a2 = Common::toArray($a);
        $this->assertEquals($a, $a2, $this->getMessage("toArray d'un tableau ne retourne pas la même chose"));
    }

    public function testGetMemory()
    {
        $m = Common::getMemoryUse();
        $this->assertInternalType('string', $m, $this->getMessage("Le type de retour n'est pas celui attendu"));

        $m = Common::getMemoryUse(true, true);
        $this->assertInternalType('int', $m, $this->getMessage("Le type de retour n'est pas celui attendu"));
    }

    public function testGetJsonFileContent()
    {
        $composer = Common::getJsonFileContent($this->rootPath() . '/composer.json');
        $this->assertInstanceOf(
            \stdClass::class
            , $composer
            , $this->getMessage("Le retour n'est pas un objet stdClass")
        );
        $this->assertEquals(
            'rcnchris/core'
            , $composer->name
            , $this->getMessage("La clé name existe dans le fichier composer.json et elle n'est pas retournée")
        );

        $composer = Common::getJsonFileContent($this->rootPath() . '/composer.json', true);
        $this->assertInternalType(
            'array'
            , $composer
            , $this->getMessage("Le retour n'est pas un tableau")
        );
        $this->assertArrayHasKey(
            'name'
            , $composer
            , $this->getMessage("La clé name existe dans le fichier composer.json et elle n'est pas retournée")
        );
    }

    public function testWithWrongContent()
    {
        $this->assertFalse(Common::getJsonFileContent('/fake.json'));
    }

    public function testGetPortsOfServices()
    {
        $services = Common::getPortOfServices();
        $this->assertNotEmpty($services);
        $this->assertArrayHasKey('http', $services);
        $this->assertEquals(80, $services['http']);
    }

    public function testGetPortsOfUDPServices()
    {
        $services = Common::getPortOfServices(null, 'udp');
        $this->assertNotEmpty($services);
        $this->assertArrayHasKey('http', $services);
        $this->assertEquals(80, $services['http']);
    }

    public function testGetPortsOfServicesWithWrongProtocol()
    {
        $services = Common::getPortOfServices(null, 'fake');
        $this->assertNotEmpty($services);
        $this->assertArrayHasKey('http', $services);
        $this->assertEquals(80, $services['http']);
    }

    public function testGetPortsOfServicesWithServiceName()
    {
        $this->assertEquals(80, Common::getPortOfServices('http'));
    }

    public function testGetPortsOfServicesWithWrongServiceName()
    {
        $this->assertFalse(Common::getPortOfServices('fake'));
    }

    public function testGetPortsOfServicesWithProtocol()
    {
        $this->assertEquals(80, Common::getPortOfServices('http', 'udp'));
    }

    public function testGetServiceOfPort()
    {
        $this->assertEquals('ftp', Common::getServiceOfPort(21));
    }

    public function testGetServiceOfWrongProtocol()
    {
        $this->assertFalse(Common::getServiceOfPort(21, 'fake'));
    }

    public function testGetUrlParts()
    {
        $url = 'http://www.google.fr/ola/les/gens?p=12&user=3';
        $parts = Common::getUrlParts($url);
        $this->assertArrayHasKey('scheme', $parts);
        $this->assertEquals('http', Common::getUrlParts($url, 'scheme'));
        $this->assertFalse(Common::getUrlParts($url, 'fake'));
    }
}
