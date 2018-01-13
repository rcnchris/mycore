<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\FileExtension;
use Tests\Rcnchris\BaseTestCase;

class FileExtensionTest extends BaseTestCase {

    /**
     * @var FileExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new FileExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Texte');
        $this->assertInstanceOf(FileExtension::class, $this->ext);
        $this->assertNotEmpty($this->ext->getFilters());
        $this->assertEmpty($this->ext->getFunctions());
    }

    /**
     * Obtenir uniquement le nom d'un fichier à partir d'un chemin
     */
    public function testGetBaseName()
    {
        $this->assertEquals('FileExtensionTest.php', $this->ext->baseName(__FILE__));
    }

    /**
     * Obtenir l'extension d'un fichier
     */
    public function testGetFileExtension()
    {
        $this->assertEquals('php', $this->ext->fileExtension(__FILE__));
    }

}
