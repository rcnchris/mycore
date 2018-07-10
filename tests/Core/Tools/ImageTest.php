<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Image;
use Tests\Rcnchris\BaseTestCase;

class ImageTest extends BaseTestCase
{

    /**
     * Chemin des images de tests
     *
     * @var string
     */
    private $dirPath;

    /**
     * Instance du dossier des images de tests
     *
     * @var \Directory
     */
    private $imgDir;

    /**
     * Liste des images du dossier img
     *
     * @var array
     */
    private $files;

    /**
     * @var Image
     */
    private $img;

    public function setUp()
    {
        parent::setUp();
        $this->dirPath = __DIR__ . '/img';
        $this->imgDir = dir($this->dirPath);
        $this->files = [];
        while (false !== ($item = $this->imgDir->read())) {
            $fileName = $this->dirPath . DIRECTORY_SEPARATOR . $item;
            if (is_file($fileName)) {
                $this->files[] = $fileName;
            }
        }
        $file = $this->files[array_rand($this->files)];
        $this->img = $this->makeImage($file);
    }

    /**
     * @param mixed|null $source
     *
     * @return \Rcnchris\Core\Tools\Image
     */
    public function makeImage($source = null)
    {
        return new Image($source);
    }

    public function testInstanceWithPathFile()
    {
        $file = __DIR__ . '/img/bob_marley_santa-barbara79.jpg';
        $img = $this->makeImage($file);
        $this->assertInstanceOf(Image::class, $img);
        $this->assertEquals(
            $file
            , $img->getPath()
            , $this->getMessage("Le chemin n'est pas celui d'origine")
        );
    }

    public function testInstanceWithObject()
    {
        $file = __DIR__ . '/img/bob_marley_santa-barbara79.jpg';
        $img = $this->makeImage($file);
        $this->assertInstanceOf(Image::class, $this->makeImage($img));
        $this->assertEquals(
            $file, $img->getPath(), $this->getMessage("Le chemin n'est pas celui d'origine")
        );
    }

    public function testSetSourceWithEmptyParameter()
    {
        $this->expectException(\Exception::class);
        $this->makeImage();
    }

    public function testGet()
    {
        $this->assertInstanceOf(
            \Intervention\Image\Image::class, $this->img->get(),
            $this->getMessage("La fonction get doit retourner l'instance d'Intervention")
        );
    }

    public function testGetDirname()
    {
        $this->assertEquals(
            __DIR__ . '/img', $this->img->getDirname(),
            $this->getMessage("Le chemin de l'image n'est pas celui attendu")
        );
    }

    public function testGetBasename()
    {
        $fileName = $this->img->getBasename();
        $this->assertContains(
            $this->img->getDirname() . DIRECTORY_SEPARATOR . $fileName, $this->files,
            $this->getMessage("Le nom du fichier est introuvable dans la liste des fichiers")
        );
    }

    public function testGetExtension()
    {
        $this->assertInternalType(
            'string',
            $this->img->getExtension(),
            $this->getMessage("L'extension n'est pas celle attendue")
        );
    }

    public function testGetWidth()
    {
        $this->assertInternalType(
            'integer',
            $this->img->getWidth(),
            $this->getMessage("La largeur n'est pas celle attendue")
        );
    }

    public function testGetHeight()
    {
        $this->assertInternalType(
            'integer',
            $this->img->getHeight(),
            $this->getMessage("La hauteur n'est pas celle attendue")
        );
    }

    public function testGetSize()
    {
        $this->assertInternalType(
            'integer',
            $this->img->getSize(),
            $this->getMessage("La taille du fichier n'a pas le type attendu")
        );
    }

    public function testGetMime()
    {
        $this->assertInternalType(
            'string',
            $this->img->getMime(),
            $this->getMessage("Le type mime est incorrect")
        );
    }

    public function testGetExifs()
    {
        $img = $this->makeImage($this->files[2]);
        $this->assertNotEmpty(
            $img->getExifs(),
            $this->getMessage("Cette image est censée avoir des données exif")
        );
        $this->assertInternalType(
            'integer', $img->getExifs('FileDateTime'),
            $this->getMessage("Le type attendu d'une donnée exif est incorrect")
        );
        $this->assertInstanceOf(
            \stdClass::class,
            $img->getExifs(null, true),
            $this->getMessage("L'objet attendu est incorrect")
        );
    }

    public function testGetEncode()
    {
        $this->assertInstanceOf(
            Image::class,
            $this->img->getEncode(),
            $this->getMessage("L'objet attendu est incorrect")
        );
    }

    public function testToString()
    {
        $this->assertInternalType(
            'string',
            (string)$this->img,
            $this->getMessage("Le type attendu est incorrect")
        );
    }

    public function testMakeThumb()
    {
        $thumb = $this->img->makeThumb();
        $this->assertInstanceOf(
            Image::class,
            $thumb,
            $this->getMessage("L'objet attendu est incorrect")
        );
        $this->assertEquals(
            150,
            $thumb->getWidth(),
            $this->getMessage("La largeur attendue est incorrecte")
        );
    }

    public function testSave()
    {
        $newFile = __DIR__ . '/img/save_' . $this->img->getBasename();
        $newImg = $this->img->save($newFile);
        $this->assertInstanceOf(
            Image::class,
            $newImg,
            $this->getMessage("L'objet attendu est incorrect")
        );
        $this->assertTrue(file_exists($newFile));
        $this->addUsedFile($newFile);
    }
}
