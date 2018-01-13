<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\TextExtension;
use Tests\Rcnchris\BaseTestCase;

class TextExtensionTest extends BaseTestCase{

    /**
     * @var TextExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new TextExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Texte');
        $this->assertInstanceOf(TextExtension::class, $this->ext);
        $this->assertNotEmpty($this->ext->getFilters());
        $this->assertEmpty($this->ext->getFunctions());
    }

    /**
     * Obtenir le résumé d'un texte
     */
    public function testExcerpt()
    {
        $texte = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium aliquid aperiam aut dolores, enim error id, impedit laboriosam nisi nobis porro quo repudiandae veritatis. Blanditiis est fuga magnam necessitatibus quisquam.';
        $this->assertEquals(
            'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium aliquid aperiam aut dolores,...',
            $this->ext->resume($texte));
    }

    /**
     * Obtenir le résumé d'un texte qui n'en a pas besoin
     */
    public function testExcerptWithoutChange()
    {
        $texte = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';
        $this->assertEquals($texte, $this->ext->resume($texte));
    }

    /**
     * Obtenir le résumé de rien...
     */
    public function testExcerptWithoutText()
    {
        $this->assertEquals('', $this->ext->resume());
    }

    /**
     * Décoder une chaîne au format json
     */
    public function testJsonDecode()
    {
        $texte = json_encode(['ola', 'ole', 'oli']);
        $this->assertEquals(['ola', 'ole', 'oli'], $this->ext->jsonDecode($texte));
    }

    /**
     * Décoder avec un mauvais paramètre
     */
    public function testJsonDecodeWithWrongParameter()
    {
        $this->assertFalse($this->ext->jsonDecode([]));
    }

    /**
     * Obtenir une taille en bits
     */
    public function testGetBitsSize()
    {
        $this->assertEquals('1024 B', $this->ext->bitsSize(1024));
        $this->assertEquals('2 KB', $this->ext->bitsSize(2048));
        $this->assertEquals('0 B', $this->ext->bitsSize('fake'));
    }
}
