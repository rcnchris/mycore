<?php
namespace Tests\Rcnchris\Core\Session;

use Rcnchris\Core\Session\ArraySession;
use Tests\Rcnchris\BaseTestCase;

class ArraySessionTest extends BaseTestCase
{

    /**
     * @var ArraySession
     */
    private $session;

    public function setUp()
    {
        $this->session = new ArraySession();
        $this->session->set('ip', '192.168.1.99');
        $this->session->set('nav', 'Opera');
    }

    public function testInstance()
    {
        $this->ekoTitre('Session - ArraySession');
        $this->assertInstanceOf(ArraySession::class, $this->session);
    }

    public function testSet()
    {
        $ip = '192.168.1.100';
        $this->session->set('ip', $ip);
        $this->assertEquals($ip, $this->session->get('ip'));
    }

    public function testGetAll()
    {
        $this->assertArrayHasKey('id', $this->session->get());
        $this->assertArrayHasKey('ip', $this->session->get());
        $this->assertArrayHasKey('nav', $this->session->get());
    }

    public function testGetUniqId()
    {
        $id = $this->session->get('id');
        $this->assertInternalType('string', $id);
        $this->assertEquals($id, $this->session->get('id'));
    }

    public function testGetLikeObject()
    {
        $this->assertEquals('192.168.1.99', $this->session->ip);
    }

    public function testGetDefault()
    {
        $this->assertEquals('Firefox', $this->session->get('browser', 'Firefox'));
    }

    public function testDelete()
    {
        $this->session->delete('nav');
        $this->assertNull($this->session->nav);
    }
}
