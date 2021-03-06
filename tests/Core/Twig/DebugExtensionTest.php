<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Apis\OneAPI;
use Rcnchris\Core\Tools\Collection;
use Rcnchris\Core\Tools\Items;
use Rcnchris\Core\Twig\DebugExtension;
use Tests\Rcnchris\BaseTestCase;

class DebugExtensionTest extends BaseTestCase
{

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
        $o = new Items('ola,ole,oli');
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
        $o = new Items('ola,ole,oli');
        $methods = get_class_methods(get_class($o));
        $this->assertEquals($methods, $this->ext->getMethods($o)->toArray());
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
            current($this->ext->getParentClass($this->ext)->toArray())
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
            current($this->ext->getParentMethods($this->ext)->toArray())
        );
    }

    public function testGetTraits()
    {
        $this->assertInternalType('array', $this->ext->getTraits($this)->toArray());
    }

    public function testGetImplements()
    {
        $this->assertEquals(
            current(class_implements($this->ext)),
            current($this->ext->getImplements($this->ext)->toArray())
        );
    }

    public function testGetPhpRef()
    {
        ob_start();
        $r1 = r($this->ext);
        $r2 = $this->ext->phpRef($this->ext);
        $this->assertEquals($r1, $r2);
        $content = ob_get_clean();
    }

    public function testGetPhpRefInText()
    {
        ob_start();
        $r1 = rt($this->ext);
        $r2 = $this->ext->phpRefText($this->ext);
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

    public function testIsArray()
    {
        $var = ['ola', 'ole'];
        $this->assertTrue($this->ext->isArray($var));
        $this->assertFalse($this->ext->isArray(true));
    }

    public function testIsBool()
    {
        $this->assertTrue($this->ext->isBool(true));
        $this->assertFalse($this->ext->isBool(['ola', 'ole']));
    }

    public function testIsObject()
    {
        $var=new \stdClass();
        $this->assertTrue($this->ext->isObject($var));
        $this->assertFalse($this->ext->isObject(['ola', 'ole']));
    }

    public function testGetConstants()
    {
        $this->assertNotEmpty($this->ext->getConstants());
        $this->assertNotEmpty($this->ext->getConstants('user'));
    }

    public function testGetType()
    {
        $this->assertEquals('string', $this->ext->getType('ola'));
    }
}
