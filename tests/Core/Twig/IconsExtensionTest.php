<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\IconsExtension;
use Tests\Rcnchris\BaseTestCase;

class IconsExtensionTest extends BaseTestCase {

    /**
     * @var IconsExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new IconsExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Icônes');
        $this->assertInstanceOf(IconsExtension::class, $this->ext);
        $this->assertNotEmpty($this->ext->getFilters());
        $this->assertNotEmpty($this->ext->getFunctions());
    }

    /**
     * Obtenir une icône Font-Awesome
     */
    public function testGetCode()
    {
        $this->assertEquals('<i class="fa fa-home"></i>', $this->ext->icon('home'));
    }

    /**
     * Obtenir une icône Font-Awesome de type file à partir d'une extension de fichier
     */
    public function testGetIconFile()
    {
        $this->assertEquals('<i class="fa fa-file-text-o"></i>', $this->ext->iconFile('txt'));
        $this->assertEquals('<i class="fa fa-file-archive-o"></i>', $this->ext->iconFile('zip'));
        $this->assertEquals('<i class="fa fa-file-pdf-o"></i>', $this->ext->iconFile('pdf'));
        $this->assertEquals('<i class="fa fa-file-excel-o"></i>', $this->ext->iconFile('xls'));
        $this->assertEquals('<i class="fa fa-file-word-o"></i>', $this->ext->iconFile('docx'));
        $this->assertEquals('<i class="fa fa-file-powerpoint-o"></i>', $this->ext->iconFile('ppt'));
        $this->assertEquals('<i class="fa fa-file-audio-o"></i>', $this->ext->iconFile('mp3'));
        $this->assertEquals('<i class="fa fa-file-picture-o"></i>', $this->ext->iconFile('jpg'));
    }
}
