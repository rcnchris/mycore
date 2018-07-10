<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\HtmlExtension;
use Tests\Rcnchris\BaseTestCase;

class HtmlExtensionTest extends BaseTestCase
{

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
        $this->assertSimilar(
            '<details><summary>ola</summary>oli</details>',
            $this->ext->details('ola', 'oli')
        );
    }

    public function testGetList()
    {
        $this->assertSimilar(
            '<ul><li>0 : ola</li><li>1 : ole</li></ul>',
            $this->ext->getList(['ola', 'ole']),
            $this->getMessage("La liste est incorrecte")
        );
    }

    public function testGetListWithNumericValues()
    {
        $values = [12, 45];
        $this->assertSimilar(
            '<ul><li>0 : 12</li><li>1 : 45</li></ul>',
            $this->ext->getList($values),
            $this->getMessage("La liste est incorrecte")
        );
    }

    public function testGetListWithObjectValues()
    {
        $values = [(new \stdClass()), (new \DateTime())];
        $this->assertSimilar(
            '<ul><li>0 : stdClass</li><li>1 : DateTime</li></ul>',
            $this->ext->getList($values),
            $this->getMessage("La liste est incorrecte")
        );
    }

    public function testGetListWithArrayValues()
    {
        $values = ['name' => ['first_name' => 'Mathis', 'last_name' => 'CHRISMANN'], 'year' => 2007];
        $this->assertSimilar(
            '<ul><li>name : first_name, last_name</li><li>year : 2007</li></ul>',
            $this->ext->getList($values),
            $this->getMessage("La liste est incorrecte")
        );
    }

    public function testGetListWithRessourcesValues()
    {
        $img = imagecreate(150, 150);
        $dir = opendir(__DIR__);
        $values = [$img, $dir];
        $this->assertSimilar(
            '<ul><li>0 : gd</li><li>1 : stream</li></ul>',
            $this->ext->getList($values),
            $this->getMessage("La liste est incorrecte")
        );
    }
}
