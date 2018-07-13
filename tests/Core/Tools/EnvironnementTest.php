<?php
namespace Tests\Rcnchris\Core\Tools;

use Locale;
use Rcnchris\Core\Tools\Environnement;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class EnvironnementTest extends BaseTestCase
{
    /**
     * @var Environnement
     */
    private $e;

    public function setUp()
    {
        $this->e = $this->makeEnvironnement($_SERVER);
    }

    /**
     * @param array|null $server
     *
     * @return \Rcnchris\Core\Tools\Environnement
     */
    public function makeEnvironnement($server = null)
    {
        return new Environnement($server);
    }

    public function testInstance()
    {
        $this->ekoTitre('Tools - Environnement');
        $this->assertInstanceOf(Environnement::class, $this->e);
    }

    public function testGet()
    {
        $this->assertInstanceOf(Items::class, $this->e->get());
    }

    public function testMagicGet()
    {
        $this->assertInstanceOf(Items::class, $this->e->argv);
    }

    public function testGetWithKey()
    {
        $this->assertInstanceOf(Items::class, $this->e->get('argv'));
    }

    public function testUname()
    {
        $this->assertSimilar(`uname -a`, $this->e->getUname());
    }

    public function testUnameWithOption()
    {
        $this->assertSimilar(`uname -r`, $this->e->getUname('r'));
    }

    public function testServerName()
    {
        $e = $this->makeEnvironnement(['SERVER_NAME' => 'testserver']);
        $this->assertEquals('testserver', $e->getServerName());
    }

    public function testApacheModules()
    {
        if ($this->getConfig('config.name') != 'local') {
            $this->markTestSkipped("N'existe pas dans les tests");
        }
        $this->assertNotEmpty($this->e->getApacheModules()->toArray());
    }

    public function testIp()
    {
        $this->markTestSkipped('Uniquement en local');
        $this->assertSimilar($_SERVER['SERVER_ADDR'], $this->e->getIp());
        $this->assertSimilar($_SERVER['REMOTE_ADDR'], $this->e->getIp('remote'));
    }

    public function testApacheUser()
    {
        $this->assertSimilar(`whoami`, $this->e->getApacheUser());
    }

    public function testMysqlVersion()
    {
        $this->assertSimilar(`mysql -V`, $this->e->getMysqlVersion());
    }

    public function testPhpVersion()
    {
        $this->assertSimilar(PHP_VERSION, $this->e->getPhpVersion());
        $this->assertInternalType('string', $this->e->getPhpVersion(true));
    }

    public function testIniFile()
    {
        $this->assertInternalType('string', $this->e->getPhpIniFile());
    }

    public function testIniFiles()
    {
        $this->assertInstanceOf(Items::class, $this->e->getPhpIniFiles());
    }

    public function testIniFilesWithParameter()
    {
        $this->assertInstanceOf(Items::class, $this->e->getPhpIniFiles('curl'));
    }

    public function testPhpExtensions()
    {
        $this->assertNotEmpty($this->e->getPhpExtensions()->toArray());
    }

    public function testPhpModules()
    {
        $this->assertNotEmpty($this->e->getPhpModules()->toArray());
    }

    public function testPdoDrivers()
    {
        $this->assertContains('mysql', $this->e->getPdoDrivers()->toArray());
    }

    public function testTimezone()
    {
        $this->assertEquals('Europe/Paris', $this->e->getTimezone());
    }

    public function testTimezones()
    {
        $this->assertContains('Europe/Paris', $this->e->getTimezones()->toArray());
    }

    public function testLocale()
    {
        $this->assertInstanceOf(Locale::class, $this->e->getLocale());
    }

    public function testSetLocale()
    {
        $this->e->setLocale('us_US');
        $this->assertEquals('us_US', $this->e->getLocale()->getDefault());
        $this->e->setLocale('fr_FR');
        $this->assertEquals('fr_FR', $this->e->getLocale()->getDefault());
    }

    public function testLocales()
    {
        $this->assertContains('fr_FR', $this->e->getLocales()->toArray());
    }

    public function testCharset()
    {
        $this->assertSimilar('UTF-8', $this->e->getCharset());
    }

    public function testPhpErrorReporting()
    {
        $this->assertInternalType('integer', $this->e->getPhpErrorReporting());
    }

    public function testSapiName()
    {
        $this->assertEquals('cli', $this->e->getSapi());
    }

    public function testConstants()
    {
        $this->assertNotEmpty($this->e->getConstants()->toArray());
        $this->assertTrue($this->e->getConstants()->has('user'));
    }

    public function testGitVersion()
    {
        $this->assertInternalType('string', $this->e->getGitVersion());
    }

    public function testCurlVersion()
    {
        $this->assertInternalType('string', $this->e->getCurlVersion());
    }

    public function testComposerVersion()
    {
        $this->assertInternalType('string', $this->e->getComposerVersion());
    }

    public function testWkhtmltopdfVersion()
    {
        $this->assertInternalType('string', $this->e->getWkhtmltopdfVersion());
    }
}
