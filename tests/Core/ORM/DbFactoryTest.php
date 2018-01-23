<?php
namespace Tests\Rcnchris\Core\ORM;

class DbFactoryTest extends OrmTestCase {

    public function testGetDbSQLiteMemory()
    {
        $this->ekoTitre('ORM - DbFactory');
        $db = $this->makeDb('memory', 0, '', '', '', 'sqlite');
        $this->assertInstanceOf(\PDO::class, $db);
    }

    public function testGetDbSQLiteFile()
    {
        $fileName = $this->rootPath() . $this::TESTS_FOLDER . '/Core/ORM/dbTests.sqlite';
        $db = $this->makeDb('dbTests', 0, '', '', '', 'sqlite', $fileName);
        $this->assertInstanceOf(\PDO::class, $db);
        array_push($this->dbFiles, $fileName);
    }

    public function testGetDbWithoutSgbd()
    {
        $db = $this->makeDb('memory', 0, '', '', '');
        $this->assertInstanceOf(\PDO::class, $db);
    }

    public function testGetDbWithWrongSgbd()
    {
        $this->expectException(\Exception::class);
        $this->makeDb('localhost', 1234, 'fake', 'fake', 'fake', 'fake');
    }

    public function testGetDbWithWrongDbName()
    {
        $this->expectException(\Exception::class);
        $this->makeDb('localhost', 1234, 'fake', 'fake', 'fake', 'fake');
    }

    public function testGetDbWithWrongServer()
    {
        $this->assertInternalType('string', $this->makeDb('192.168.1.99', 3306, 'lan', 'vaccoune', 'home', 'mysql'));
    }

//    public function testGetDbWithSQLServer()
//    {
//        $this->assertInstanceOf(\PDO::class, $this->makeDb('192.168.1.7\SQLEXPRESS', 1433, 'php', 'php', 'DEMO', 'sqlsrv'));
//    }
}
