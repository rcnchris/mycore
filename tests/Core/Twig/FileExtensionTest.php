<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\FileExtension;
use Tests\Rcnchris\BaseTestCase;

/**
 * Class FileExtensionTest
 * <ul>
 * <li>Helper de manipulation des fichiers et dossiers</li>
 * </ul>
 *
 * @category Twig
 *
 * @package  Tests\Rcnchris\Core\Twig
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
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
     * Obtenir uniquement le nom d'un fichier à partir d'un chemin
     */
    public function testGetDirName()
    {
        $this->assertEquals(
            __DIR__
            , $this->ext->dirName(__FILE__)
            , $this->getMessage("Le chemin attendu est incorrect")
        );
    }

    /**
     * Obtenir l'extension d'un fichier
     */
    public function testGetFileExtension()
    {
        $this->assertEquals('php', $this->ext->fileExtension(__FILE__));
    }

}
