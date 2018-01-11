<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\ArrayExtension;
use Tests\Rcnchris\BaseTestCase;

class ArrayExtensionTest extends BaseTestCase {

    /**
     * @var ArrayExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new ArrayExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Tableaux');
        $this->assertInstanceOf(ArrayExtension::class, $this->ext);
    }

    /**
     * Obtenir la liste des filtres de l'extension
     */
    public function testGetFilters()
    {
        $filters = $this->ext->getFilters();
        $this->assertEmpty($filters);
    }

    /**
     * Obtenir la liste des fonctions de l'extension
     */
    public function testGetFunctions()
    {
        $functions = $this->ext->getFunctions();
        $this->assertNotEmpty($functions);
    }

    /**
     * Fusionner plusieurs tableaux en un seul
     */
    public function testArrayMerge()
    {
        $tab1 = ['ola', 'ole', 'oli'];
        $tab2 = ['mcn', 'rcn', 'ccn'];
        $tab = $this->ext->arrayMerge($tab1, $tab2);
        $this->assertEquals([
            'ola', 'ole', 'oli', 'mcn', 'rcn', 'ccn'
        ], $tab);
    }

    public function testInArray()
    {
        $tab = ['ola', 'ole', 'oli'];
        $this->assertTrue($this->ext->inArray('ola', $tab));
        $this->assertFalse($this->ext->inArray('rcn', $tab));
    }
}
