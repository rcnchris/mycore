<?php
namespace Rcnchris\Core\ORM;

use Tests\Rcnchris\Core\ORM\OrmTestCase;

class TableTest extends OrmTestCase
{
    public function makeTable($name)
    {
        return new Table($name, $this->getManager()->connect('demo'));
    }

    public function testInstance()
    {
        $this->ekoTitre('ORM - Table');
        $this->assertInstanceOf(Table::class, $this->makeTable('users'));
    }

    public function testGetPdo()
    {
        $this->assertInstanceOf(\PDO::class, $this->makeTable('users')->getPdo());
    }

    public function testGetName()
    {
        $this->assertEquals('users', $this->makeTable('users')->getName());
    }

    public function testQuery()
    {
        $this->assertInstanceOf(Query::class, $this->makeTable('users')->query());
    }
}
