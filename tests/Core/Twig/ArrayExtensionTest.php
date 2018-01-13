<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\ArrayExtension;
use Tests\Rcnchris\BaseTestCase;

class ArrayExtensionTest extends BaseTestCase {

    /**
     * @var ArrayExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new ArrayExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Tableaux');
        $this->assertInstanceOf(ArrayExtension::class, $this->ext);
        $this->assertNotEmpty($this->ext->getFilters());
        $this->assertNotEmpty($this->ext->getFunctions());
    }

    /**
     * Fusionner plusieurs tableaux en un seul
     */
    public function testArrayMerge()
    {
        $tab1 = ['ola', 'ole', 'oli'];
        $tab2 = ['mcn', 'rcn', 'ccn'];
        $tab = $this->ext->arrayMerge($tab1, $tab2);
        $this->assertEquals([
            'ola', 'ole', 'oli', 'mcn', 'rcn', 'ccn'
        ], $tab);
    }

    public function testInArray()
    {
        $tab = ['ola', 'ole', 'oli'];
        $this->assertTrue($this->ext->inArray('ola', $tab));
        $this->assertFalse($this->ext->inArray('rcn', $tab));
    }

    public function testExtract()
    {
        $tab = [
            ['name' => 'Mathis', 'year' => 2007, 'genre' => 'male']
            , ['name' => 'Raphaël', 'year' => 2007, 'genre' => 'male']
            , ['name' => 'Clara', 'year' => 2009, 'genre' => 'female']
        ];
        $this->assertEquals(
            ['Mathis', 'Raphaël', 'Clara']
            , $this->ext->extract($tab, 'name')
        );
    }

    public function testExtractWithKey()
    {
        $tab = [
            ['name' => 'Mathis', 'year' => 2007, 'genre' => 'male']
            , ['name' => 'Raphaël', 'year' => 2007, 'genre' => 'male']
            , ['name' => 'Clara', 'year' => 2009, 'genre' => 'female']
        ];
        $this->assertEquals(
            [
                'Mathis' => 'male'
                , 'Raphaël' => 'male'
                , 'Clara' => 'female'
            ]
            , $this->ext->extract($tab, 'genre', 'name')
        );
    }

    public function testToHtml()
    {
        $tab = ['ola', 'ole', 'oli'];
        $tab = $this->ext->toHtml($tab);
        $this->assertSimilar(
            '<table>
                <tbody>
                <tr><td>0</td><td>ola</td></tr>
                <tr><td>1</td><td>ole</td></tr>
                <tr><td>2</td><td>oli</td></tr>
                </tbody>
            </table>'
            , $tab
        );
    }

    public function testToHtmlWithSimpleHeader()
    {
        $tab = ['ola', 'ole', 'oli'];
        $tab = $this->ext->toHtml($tab, ['header' => true]);
        $this->assertSimilar(
            '<table>
                <thead>
                <tr><th>#</th><th>Libellé</th></tr>
                </thead>
                <tbody>
                <tr><td>0</td><td>ola</td></tr>
                <tr><td>1</td><td>ole</td></tr>
                <tr><td>2</td><td>oli</td></tr>
                </tbody>
            </table>'
            , $tab
        );
    }

    public function testToHtmlWithSimpleHeaderWithClass()
    {
        $tab = ['ola', 'ole', 'oli'];
        $tab = $this->ext->toHtml($tab, ['header' => true, 'class' => 'table']);
        $this->assertSimilar(
            '<table class="table">
                <thead>
                <tr><th>#</th><th>Libellé</th></tr>
                </thead>
                <tbody>
                <tr><td>0</td><td>ola</td></tr>
                <tr><td>1</td><td>ole</td></tr>
                <tr><td>2</td><td>oli</td></tr>
                </tbody>
            </table>'
            , $tab
        );
    }

    public function testToHtmlWithHeader()
    {
        $tab = [
            ['name' => 'Mathis', 'year' => 2007, 'genre' => 'male']
            , ['name' => 'Raphaël', 'year' => 2007, 'genre' => 'male']
        ];
        $tab = $this->ext->toHtml($tab);
        $this->assertSimilar('
            <table>
            <thead>
            <tr><th>name</th><th>year</th><th>genre</th></tr>
            </thead>
            <tbody>
            <tr><td>Mathis</td><td>2007</td><td>male</td></tr>
            <tr><td>Raphaël</td><td>2007</td><td>male</td></tr>
            </tbody>
            </table>'
            , $tab);
    }
}
