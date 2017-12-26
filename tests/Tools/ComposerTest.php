<?php
namespace Tests\Core\Tools;

use Rcnchris\Core\Tools\Composer;
use Tests\Rcnchris\BaseTestCase;

class ComposerTest extends BaseTestCase {

    /**
     * @var Composer
     */
    public $composer;

    public function setUp()
    {
        $file = dirname(dirname(__DIR__)) . '/composer.json';
        $this->composer = $this->makeComposer($file);
    }

    /**
     * @param $path
     *
     * @return Composer
     */
    public function makeComposer($path)
    {
        return new Composer($path);
    }

    public function testInstance()
    {
        $this->ekoTitre("Tools - Composer");
        $this->assertInstanceOf(Composer::class, $this->composer);
    }

    public function testGetKey()
    {
        $this->assertInternalType('string', $this->composer->name);
        $this->assertNotEmpty($this->composer->require);
    }

    public function testGetToArray()
    {
        $this->assertNotEmpty($this->composer->toArray());
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->composer->getVersion());
    }

    public function testShow()
    {
        $this->assertNotEmpty($this->composer->show());
    }
}
