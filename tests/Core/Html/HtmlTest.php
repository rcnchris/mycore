<?php
namespace Tests\Rcnchris\Core\Html;

use Rcnchris\Core\Html\Html;
use Tests\Rcnchris\BaseTestCase;

class HtmlTest extends BaseTestCase
{
    /**
     * @var Html
     */
    private $html;

    public function setUp()
    {
        $this->html = Html::getInstance();
    }

    public function testInstance()
    {
        $this->ekoTitre('Html - Helper HTML');
        $this->assertInstanceOf(Html::class, $this->html);
    }

    public function testLink()
    {
        $expect = '<a href="http://google.fr">Google</a>';
        $this->assertSimilar($expect, $this->html->link('Google', 'http://google.fr'));
    }

    public function testLinkWithAttribute()
    {
        $expect = '<a class="btn btn-primary" href="http://google.fr">Google</a>';
        $this->assertSimilar($expect, $this->html->link('Google', 'http://google.fr', ['class' => 'btn btn-primary']));
    }

    public function testImage()
    {
        $expect = '<img alt="Test unitaire" src="path/to/file.png">';
        $this->assertSimilar($expect, $this->html->img('path/to/file.png', ['alt' => 'Test unitaire']));
    }

    public function testMakeList()
    {
        $expect = '<ul><li>0 : ola</li><li>1 : ole</li></ul>';
        $this->assertSimilar($expect, $this->html->liste(['ola', 'ole']));
    }

    public function testMakeListOl()
    {
        $expect = '<ol><li>0 : ola</li><li>1 : ole</li></ol>';
        $this->assertSimilar($expect, $this->html->liste(['ola', 'ole'], ['type' => 'ol']));
    }

    public function testMakeListDl()
    {
        $expect = '
            <dl>
                <dt>0</dt>
                <dd>ola</dd>
                <dt>1</dt>
                <dd>ole</dd>
            </dl>
        ';
        $this->assertSimilar($expect, $this->html->liste(['ola', 'ole'], ['type' => 'dl']));
    }

    public function testDetails()
    {
        $expect = '
            <details>
                <summary>ola</summary>
                ole
            </details>
        ';
        $this->assertSimilar($expect, $this->html->details('ola', 'ole'));
    }

    public function testSource()
    {
        $expect = '<pre><p>Ola les gens</p></pre>';
        $this->assertSimilar($expect, $this->html->source('<p>Ola les gens</p>'));
    }

    public function testSourceWithFile()
    {
        $file = $this->rootPath() . '/.htaccess';
        $expect = '<pre>' . file_get_contents($file) . '</pre>';
        $this->assertSimilar($expect, $this->html->source($file));
    }

    public function testSourceWithFileWithHeader()
    {
        $file = $this->rootPath() . '/.htaccess';
        $expect = '<code>' . $file . '</code><pre>' . file_get_contents($file) . '</pre>';
        $this->assertSimilar($expect, $this->html->source($file, [], true));
    }

    public function testSourceWithPhpFile()
    {
        $this->assertInternalType(
            'string',
            $this->html->source($this->rootPath() . '/tests/config.php')
        );
    }
}