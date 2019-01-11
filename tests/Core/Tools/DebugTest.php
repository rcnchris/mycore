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
        $this->ekoTitre('Tools - Debug');
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
        $this->assertEquals([], Debug::getProperties($this)->toArray());
        $o = new \stdClass();
        $o->name = 'Clara';
        $this->assertEquals(['name' => 'Clara'], Debug::getProperties($o)->toArray());
        $this->assertEquals(['name' => 'Clara'], $this->debugger->getProperties($o)->toArray());
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
        $this->assertCount(4, Debug::getParents($this));
        $this->assertEquals(get_parent_class(get_class($this)), current(Debug::getParents($this)->toArray()));
        $this->assertEquals(get_parent_class(get_class($this)), current($this->debugger->getParents($this)->toArray()));
    }

    public function testGetParentsReverse()
    {
        $this->assertEquals(array_reverse(Debug::getParents($this)->toArray()), Debug::getParents($this, true)->toArray());
        $this->assertEquals(array_reverse($this->debugger->getParents($this)->toArray()), $this->debugger->getParents($this, true)->toArray());
    }

    public function testGetInterfaces()
    {
        $this->assertNotEmpty(Debug::getInterfaces($this));
        $this->assertNotEmpty($this->debugger->getInterfaces($this));
    }

    public function testGetTraits()
    {
        $this->assertEquals([], Debug::getTraits($this)->toArray());
        $this->assertEquals([], $this->debugger->getTraits($this)->toArray());
    }

    public function testGetNamespace()
    {
        $this->assertEquals('Tests\Rcnchris\Core\Tools', $this->debugger->getNamespace($this));
    }
}
