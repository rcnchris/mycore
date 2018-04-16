<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Apis\OneAPI;
use Rcnchris\Core\Tools\Collection;
use Rcnchris\Core\Twig\DebugExtension;
use Tests\Rcnchris\BaseTestCase;

class DebugExtensionTest extends BaseTestCase {

    /**
     * @var DebugExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new DebugExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Debug');
        $this->assertInstanceOf(DebugExtension::class, $this->ext);
        $this->assertNotEmpty($this->ext->getFilters());
        $this->assertNotEmpty($this->ext->getFunctions());
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
     * Obtenir la classe d'un objet
     */
    public function testGetClass()
    {
        $o = new Collection('ola,ole,oli');
        $this->assertEquals(get_class($o), $this->ext->getClass($o));
    }

    public function testGestClassWithWrongParameter()
    {
        $this->assertFalse($this->ext->getClass('fake'));
    }

    /**
     * Obtenir les méthodes d'un objet
     */
    public function testGetMethods()
    {
        $o = new Collection('ola,ole,oli');
        $methods = get_class_methods(get_class($o));
        sort($methods);
        $this->assertEquals($methods, $this->ext->getMethods($o));
    }

    public function testGestMethodsWithWrongParameter()
    {
        $this->assertFalse($this->ext->getMethods('fake'));
    }

    public function testGetProperties()
    {
        $o = new \DateTime();
        $this->assertNotEmpty($this->ext->getProperties($o));
        $this->assertArrayHasKey('date', $this->ext->getProperties($o));
    }

    public function testGetParentClass()
    {
        $this->assertEquals(
            get_parent_class($this->ext),
            $this->ext->getParentClass($this->ext)
        );
    }

    public function testGetParentClassRecurs()
    {
        $this->assertContains(
            get_parent_class($this->ext),
            $this->ext->getParentClass($this->ext, true)
        );
    }

    public function testGetParentClassWithNonObjectParameter()
    {
        $this->assertFalse($this->ext->getParentClass([]));
    }

    public function testGetParentMethods()
    {
        $this->assertEquals(
            get_class_methods(get_parent_class($this->ext)),
            $this->ext->getParentMethods($this->ext)
        );
    }

    public function testGetImplements()
    {
        $this->assertEquals(
            class_implements($this->ext),
            $this->ext->getImplements($this->ext)
        );
    }

    public function testGetTraits()
    {
        $o = new OneAPI();
        $this->assertNotEmpty($this->ext->getTraits($o));
    }

    public function testGetPhpRef()
    {
        ob_start();
        $r1 = r($this->ext);
        $r2 = $this->ext->phpRef($this->ext);
        $this->assertEquals($r1, $r2);
        $content = ob_get_clean();
    }

    public function testVardump()
    {
        ob_start();
        $this->ext->vd('ola');
        $this->assertTrue(true);
        $content = ob_get_clean();
    }
}
