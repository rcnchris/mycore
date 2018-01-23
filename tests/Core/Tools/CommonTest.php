<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Common;
use Tests\Rcnchris\BaseTestCase;

class CommonTest extends BaseTestCase
{

    public function testObjectToArray()
    {
        $this->ekoTitre('Tools - Common');
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

    public function testGetInstance()
    {
        $this->assertInstanceOf(
            Common::class
            , Common::getInstance()
            , $this->getMessage("L'instance retournée par getInstance n'est pas la bonne")
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
}
