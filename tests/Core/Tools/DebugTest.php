<?php
/**
 * Fichier DebugTest.php du 02/07/2018 
 * Description : Fichier de la classe DebugTest 
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Tools
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Debug;
use Tests\Rcnchris\BaseTestCase;

class DebugTest extends BaseTestCase
{
    /**
     * @var \Rcnchris\Core\Tools\Debug
     */
    private $debugger;

    /**
     * Crée un debugger dans l'instance
     */
    public function setUp()
    {
        $this->debugger = Debug::getInstance();
    }

    public function testGetInstance()
    {
        $this->ekoTitre('Tools - Items');
        $this->assertInstanceOf(Debug::class, $this->debugger);
    }

    public function testIsObjectException()
    {
        $this->expectException(\Exception::class);
        Debug::getClass('fake');
    }

    public function testIsObjectExceptionPublic()
    {
        $this->expectException(\Exception::class);
        $this->debugger->getClass('fake');
    }

    public function testGetClass()
    {
        $this->assertEquals('Tests\Rcnchris\Core\Tools\DebugTest', Debug::getClass($this));
        $this->assertEquals('Tests\Rcnchris\Core\Tools\DebugTest', $this->debugger->getClass($this));
    }

    public function testGetClassShortName()
    {
        $this->assertEquals('DebugTest', Debug::getClassShortName($this));
        $this->assertEquals('DebugTest', $this->debugger->getClassShortName($this));
    }

    public function testGetProperties()
    {
        $this->assertEquals([], Debug::getProperties($this));
        $o = new \stdClass();
        $o->name = 'Clara';
        $this->assertEquals(['name' => 'Clara'], Debug::getProperties($o));
        $this->assertEquals(['name' => 'Clara'], $this->debugger->getProperties($o));
    }

    public function testGetMethods()
    {
        $this->assertNotEmpty(Debug::getMethods($this));
        $this->assertNotEmpty($this->debugger->getMethods($this));
    }

    public function testGetParentsMethods()
    {
        $this->assertNotEmpty(Debug::getParentsMethods($this));
        $this->assertNotEmpty($this->debugger->getParentsMethods($this));
    }

    public function testGetParents()
    {
        $this->assertNotEmpty(Debug::getParents($this));
        $this->assertCount(3, Debug::getParents($this));
        $this->assertEquals(get_parent_class(get_class($this)), current(Debug::getParents($this)));
        $this->assertEquals(get_parent_class(get_class($this)), current($this->debugger->getParents($this)));
    }

    public function testGetParentsReverse()
    {
        $this->assertEquals(array_reverse(Debug::getParents($this)), Debug::getParents($this, true));
        $this->assertEquals(array_reverse($this->debugger->getParents($this)), $this->debugger->getParents($this, true));
    }

    public function testGetInterfaces()
    {
        $this->assertNotEmpty(Debug::getInterfaces($this));
        $this->assertNotEmpty($this->debugger->getInterfaces($this));
    }

    public function testGetTraits()
    {
        $this->assertEquals([], Debug::getTraits($this));
        $this->assertEquals([], $this->debugger->getTraits($this));
    }

    public function testGetConstants()
    {
        $this->assertNotEmpty(Debug::getConstants());
        $this->assertNotEmpty($this->debugger->getConstants());

        $this->assertNotEmpty(Debug::getConstants('user'));
        $this->assertNotEmpty($this->debugger->getConstants('user'));
    }
}
