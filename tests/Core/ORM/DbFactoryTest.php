<?php
namespace Core\ORM;

use Rcnchris\Core\ORM\DbFactory;
use Tests\Rcnchris\BaseTestCase;

class DbFactoryTest extends BaseTestCase
{
    /**
     * @param $server
     * @param $port
     * @param $user
     * @param $pwd
     * @param $dbname
     * @param $sgbd
     * @param $file
     *
     * @return null|\PDO|string
     * @throws \Exception
     */
    public function makeDbFactory($server, $port, $user, $pwd, $dbname, $sgbd = null, $file = null)
    {
        return DbFactory::get($server, $port, $user, $pwd, $dbname, $sgbd, $file);
    }

    public function testInstacne()
    {
        $this->ekoTitre('ORM - DbFactory', true);
    }

    public function testGet()
    {
        $this->assertInstanceOf(
            \PDO::class,
            $this->makeDbFactory(
                'localhost',
                3306,
                'demo',
                'demo',
                'demo'
            )
        );
    }

    public function testGetWithWrongSgbd()
    {
        $this->expectException(\Exception::class);
        $this->makeDbFactory(
            'localhost',
            3306,
            'demo',
            'demo',
            'demo',
            'fakeSgbd'
        );
    }

    public function testGetWithWrongDb()
    {
        $this->assertInstanceOf(\PDO::class, $this->makeDbFactory(
            'localhost',
            3306,
            'fake',
            'demo',
            'demo'
        ));
    }

    public function testGetWithWrongHost()
    {
        $this->assertInstanceOf(\PDO::class, $this->makeDbFactory(
            'fake',
            3306,
            'demo',
            'demo',
            'demo'
        ));
    }
}
