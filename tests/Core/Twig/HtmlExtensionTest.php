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
    }

    /**
     * Obtenir la liste des filtres de l'extension
     */
    public function testGetFilters()
    {
        $filters = $this->ext->getFilters();
        $this->assertNotEmpty($filters);
    }

    /**
     * Obtenir la liste des fonctions de l'extension
     */
    public function testGetFunctions()
    {
        $functions = $this->ext->getFunctions();
        $this->assertEmpty($functions);
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
}
