<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\HtmlExtension;
use Tests\Rcnchris\BaseTestCase;

class HtmlExtensionTest extends BaseTestCase {

    /**
     * @var HtmlExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new HtmlExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - HTML');
        $this->assertInstanceOf(HtmlExtension::class, $this->ext);
        $this->assertNotEmpty($this->ext->getFilters());
        $this->assertNotEmpty($this->ext->getFunctions());
    }

    /**
     * Obtenir une balise <code>
     */
    public function testGetCode()
    {
        $this->assertEquals('<code>ola</code>', $this->ext->code('ola'));
    }

    /**
     * Obtenir une balise <code>
     */
    public function testGetCodeWithWrongParameter()
    {
        $this->assertNull($this->ext->code(['ola']));
    }

    public function testSurround()
    {
        $this->assertEquals('<code>ola</code>', $this->ext->surround('ola', 'code'));
    }

    public function testSurroundWithWrongParameter()
    {
        $this->assertNull($this->ext->surround(['ola'], 'code'));
        $this->assertNull($this->ext->surround('ola', ['code']));
    }

    public function testGetDetails()
    {
        $this->assertSimilar('<details><summary>ola</summary>oli</details>', $this->ext->details('ola', 'oli'));
    }

    public function testGetList()
    {
        $this->assertSimilar(
            '<ul><li>0 : ola</li><li>1 : ole</li></ul>'
            , $this->ext->getList(['ola', 'ole'])
            , $this->getMessage("La liste est incorrecte")
        );
    }
}
