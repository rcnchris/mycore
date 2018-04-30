<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\DbFactory;

class DbFactoryTest extends OrmTestCase
{
    public function testInstance()
    {
        parent::testInstance();
        $this->assertTrue(class_exists(DbFactory::class));
    }

    public function testGet()
    {
        $fileName = $this->rootPath() . '/public/dbApp.sqlite';
        $pdo = DbFactory::get('dbApp', 0, '', '', '', 'sqlite', $fileName);
        $this->assertInstanceOf(
            \PDO::class,
            $pdo,
            $this->getMessage("Une connexion PDO est attendue")
        );
    }

    public function testGetWithoutFileExist()
    {
        $fileName = $this->rootPath() . '/tests/Core/ORM/files/dbTests.sqlite';
        DbFactory::get('dbTests', 0, '', '', '', 'sqlite', $fileName);
        $this->assertTrue(file_exists($fileName));
        $this->addUsedFile($fileName);
    }

    public function testGetWithoutFileExistInNoPermissionDir()
    {
        $fileName = $this->rootPath() . '/tests/ORM/dbTests.sqlite';
        $pdo = DbFactory::get('dbTests', 0, '', '', '', 'sqlite', $fileName);
        $this->assertContains('[HY000]', $pdo);
        $this->assertFalse(file_exists($fileName));
    }

    public function testGetWithoutSgbdName()
    {
        $fileName = $this->rootPath() . '/public/dbApp.sqlite';
        $pdo = DbFactory::get('dbApp', 0, '', '', '', null, $fileName);
        $this->assertInstanceOf(
            \PDO::class,
            $pdo,
            $this->getMessage("Une connexion PDO est attendue")
        );
    }

    public function testGetWithWithWrongSgbdName()
    {
        $this->expectException(\Exception::class);
        DbFactory::get('dbApp', 0, '', '', '', 'fake', '');
    }

    public function testOrmTestCaseGetDb()
    {
        $this->assertInstanceOf(\PDO::class, $this->getDb());
    }
}