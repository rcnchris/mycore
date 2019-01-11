<?php
namespace Tests\Rcnchris\Core\Session;

use Rcnchris\Core\Session\PHPCookies;
use Tests\Rcnchris\BaseTestCase;

class PHPCookiesTest extends BaseTestCase
{
    /**
     * @var \Rcnchris\Core\Session\PHPCookies
     */
    private $cookies;

    /**
     * Obtenir l'instance des cookies
     *
     * @param mixed|null $datas
     * @param array|null $options
     *
     * @return \Rcnchris\Core\Session\PHPCookies
     */
    private function makeCookies($datas = null, array $options = [])
    {
        return new PHPCookies($datas, $options);
    }

    public function setUp()
    {
        $this->cookies = $this->makeCookies();
    }

    public function testInstance()
    {
        $this->ekoTitre('Session - Cookies');
        $this->assertInstanceOf(PHPCookies::class, $this->cookies);
    }

    public function testInstanceWithOptions()
    {
        $cookies = $this->makeCookies(null, ['lifetime' => 240]);
        $this->assertInstanceOf(PHPCookies::class, $cookies);
        $this->assertEquals(240, $cookies->getParams()['lifetime']);
    }

    public function testGetCookies()
    {
        $this->assertEmpty($this->cookies->get());
    }

    public function testGetParams()
    {
        $this->assertEquals(
            ['lifetime', 'path', 'domain', 'secure', 'httponly'],
            array_keys($this->cookies->getParams())
        );
    }

    public function testGetParam()
    {
        $this->assertEquals('/', $this->cookies->getParams('path'));
        $this->assertFalse($this->cookies->getParams('fake'));
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string)$this->cookies);
    }

    public function testHasKey()
    {
        $this->assertFalse($this->cookies->has('ip'));
    }
}
