<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Image;
use Tests\Rcnchris\BaseTestCase;

class ImageTest extends BaseTestCase {

    /**
     * @var Image
     */
    private $bob;

    public function setUp()
    {
        parent::setUp();
        $this->bob = $this->makeImage(__DIR__ . '/img/bob_marley_santa-barbara79.jpg');
    }
    /**
     * @param null $source
     *
     * @return \Rcnchris\Core\Tools\Image
     */
    public function makeImage($source = null)
    {
        return new Image($source);
    }

    public function testInstanceWithoutParam()
    {
        $this->ekoTitre('Tools - Image');
        $this->assertInstanceOf(Image::class, $this->makeImage());
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
            $file
            , $img->getPath()
            , $this->getMessage("Le chemin n'est pas celui d'origine")
        );
    }

    public function testGet()
    {
        $this->assertInstanceOf(
            \Intervention\Image\Image::class
            , $this->bob->get()
            , $this->getMessage("La fonction get doit retourner l'instance d'Intervention")
        );
    }

    public function testGetDirname()
    {
        $this->assertEquals(
            __DIR__ . '/img'
            , $this->bob->getDirname()
            , $this->getMessage("Le chemin de l'image n'est pas celui attendu")
        );
    }

    public function testGetBasename()
    {
        $this->assertEquals(
            'bob_marley_santa-barbara79.jpg'
            , $this->bob->getBasename()
            , $this->getMessage("Le nom du fichier n'est pas celui d'origine")
        );
    }

    public function testGetExtension()
    {
        $this->assertEquals(
            'jpg'
            , $this->bob->getExtension()
            , $this->getMessage("L'extension n'est pas celle attendue")
        );
    }

    public function testGetWidth()
    {
        $this->assertEquals(
            1024
            , $this->bob->getWidth()
            , $this->getMessage("La largeur n'est pas celle attendue")
        );
    }

    public function testGetHeight()
    {
        $this->assertEquals(
            576
            , $this->bob->getHeight()
            , $this->getMessage("La haiuteur n'est pas celle attendue")
        );
    }

    public function testGetSize()
    {
        $this->assertInternalType(
            'integer'
            , $this->bob->getSize()
            , $this->getMessage("La taille du fichier n'a pas le type attendu"));
    }

    public function testGetMime()
    {
        $this->assertEquals(
            'image/jpeg'
            , $this->bob->getMime()
            , $this->getMessage("Le type mime n'est pas celui attendu"));
    }

    public function testGetExifs()
    {
        $this->assertNotEmpty(
            $this->bob->getExifs()
            , $this->getMessage("Cette image est censée avoir des données exif")
        );
        $this->assertInternalType(
            'integer'
            , $this->bob->getExifs('FileDateTime')
            , $this->getMessage("Le type attendu d'une donnée exif est incorrect")
        );
        $this->assertInstanceOf(
            \stdClass::class
            , $this->bob->getExifs(null, true)
            , $this->getMessage("L'objet attendu est incorrect")
        );
    }

    public function testGetEncode()
    {
        $this->assertInstanceOf(
            Image::class
            , $this->bob->getEncode()
            , $this->getMessage("L'objet attendu est incorrect")
        );
    }

    public function testToString()
    {
        $this->assertInternalType(
            'string'
            , (string)$this->bob
            , $this->getMessage("Le type attendu est incorrect")
        );
    }

    public function testMakeThumb()
    {
        $thumb = $this->bob->makeThumb();
        $this->assertInstanceOf(
            Image::class
            , $thumb
            , $this->getMessage("L'objet attendu est incorrect")
        );
        $this->assertEquals(
            150
            , $thumb->getWidth()
            , $this->getMessage("La largeur attendue est incorrecte")
        );
        $this->assertEquals(
            1024
            , $this->bob->getWidth()
            , $this->getMessage("La largeur attendue est incorrecte")
        );
    }

    public function testSave()
    {
        $newFile = __DIR__ . '/img/save_' . $this->bob->getBasename();
        $newImg = $this->bob->save($newFile);
        $this->assertInstanceOf(
            Image::class
            , $newImg
            , $this->getMessage("L'objet attendu est incorrect")
        );
        $this->assertTrue(file_exists($newFile));
        unlink($newFile);
    }
}
