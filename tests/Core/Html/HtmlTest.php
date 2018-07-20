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
        $this->html->setCdns($this->getConfig('cdn'));
    }

    public function testInstance()
    {
        $this->ekoTitre('Html - Helper HTML');
        $this->assertInstanceOf(Html::class, $this->html);
        $this->assertArrayHasKey('jquery', $this->html->getCdns()->toArray());
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

    public function testTableWithSimpleList()
    {
        $list = ['ola','ole','oli'];
        $expect = '
        <table>
            <tbody>
                <tr><th>0</th><td>ola</td></tr>
                <tr><th>1</th><td>ole</td></tr>
                <tr><th>2</th><td>oli</td></tr>
            </tbody>
        </table>
        ';
        $this->assertSimilar($expect, $this->html->table($list));
    }

    public function testTableWithSimpleListWithoutHeader()
    {
        $list = ['ola','ole','oli'];
        $expect = '
        <table>
            <tbody>
                <tr><td>ola</td></tr>
                <tr><td>ole</td></tr>
                <tr><td>oli</td></tr>
            </tbody>
        </table>
        ';
        $this->assertSimilar($expect, $this->html->table($list, [], false));
    }

    public function testTableRecursive()
    {
        $list = [
            'list1' => ['ola','ole','oli'],
            'list2' => ['olo','olu','oly'],
        ];
        $expect = '
        <table>
            <tbody>
                <tr>
                    <th>list1</th>
                    <td>
                        <table>
                            <tbody>
                                <tr><th>0</th><td>ola</td></tr>
                                <tr><th>1</th><td>ole</td></tr>
                                <tr><th>2</th><td>oli</td></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>list2</th>
                    <td>
                        <table>
                            <tbody>
                                <tr><th>0</th><td>olo</td></tr>
                                <tr><th>1</th><td>olu</td></tr>
                                <tr><th>2</th><td>oly</td></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        ';
        $this->assertSimilar($expect, $this->html->table($list));
    }

    public function testTableWithSimpleListWithCaption()
    {
        $list = ['ola','ole','oli'];
        $expect = '
        <table>
            <caption>Avec un titre</caption>
            <tbody>
                <tr><th>0</th><td>ola</td></tr>
                <tr><th>1</th><td>ole</td></tr>
                <tr><th>2</th><td>oli</td></tr>
            </tbody>
        </table>
        ';
        $this->assertSimilar($expect, $this->html->table($list, ['caption' => 'Avec un titre']));
    }

    public function testTableWithSimpleListColMode()
    {
        $list = ['ola','ole','oli'];
        $expect = '
        <table>
            <thead>
                <tr>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ola</td>
                    <td>ole</td>
                    <td>oli</td>
                </tr>
            </tbody>
        </table>
        ';
        $this->assertSimilar($expect, $this->html->table($list, [], true, true));
    }

    public function testTableWithSimpleListColModeRecursive()
    {
        $list = [
            'list1' => ['ola','ole','oli'],
            'list2' => ['olo','olu','oly'],
        ];
        $expect = '
        <table>
            <thead>
                <tr>
                    <th>list1</th>
                    <th>list2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <table>
                            <thead>
                                <tr>
                                    <th>0</th>
                                    <th>1</th>
                                    <th>2</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ola</td>
                                    <td>ole</td>
                                    <td>oli</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <table>
                            <thead>
                                <tr>
                                    <th>0</th>
                                    <th>1</th>
                                    <th>2</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>olo</td>
                                    <td>olu</td>
                                    <td>oly</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        ';
        $this->assertSimilar($expect, $this->html->table($list, [], true, true));
    }

    public function testTableWithWithAttributes()
    {
        $list = ['ola','ole','oli'];
        $expect = '
        <table class="table table-sm">
            <tbody>
                <tr><th>0</th><td>ola</td></tr>
                <tr><th>1</th><td>ole</td></tr>
                <tr><th>2</th><td>oli</td></tr>
            </tbody>
        </table>
        ';
        $this->assertSimilar($expect, $this->html->table($list, ['class' => 'table table-sm']));
    }

    public function testTableWithAssociativeArray()
    {
        $list = [
            'Mathis' => 12,
            'Raphaël' => 14,
            'Clara' => 16,
        ];
        $expect = '
        <table>
            <tbody>
                <tr><th>Mathis</th><td>12</td></tr>
                <tr><th>Raphaël</th><td>14</td></tr>
                <tr><th>Clara</th><td>16</td></tr>
            </tbody>
        </table>
        ';
        $this->assertSimilar($expect, $this->html->table($list));
    }

    public function testGetScript()
    {
        $expect = '<script src="https://code.highcharts.com/highcharts.js" type="text/javascript"></script>';
        $this->assertSimilar($expect, $this->html->script('highcharts'));
    }

    public function testGetScriptMin()
    {
        $expect = '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" type="text/javascript"></script>';
        $this->assertSimilar($expect, $this->html->script('jquery', 'min'));
    }

    public function testGetScriptWithWrongType()
    {
        $this->assertNull($this->html->script('jquery', 'fake'));
    }

    public function testGetScriptWithMissingKey()
    {
        $this->assertNull($this->html->script('fake', 'src'));
    }

    public function testGetCssLink()
    {
        $expect = '<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>';
        $this->assertSimilar($expect, $this->html->css('datatables'));
    }

    public function testGetCssLinkMin()
    {
        $expect = '<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>';
        $this->assertSimilar($expect, $this->html->css('datatables', 'min'));
    }

    public function testGetCssWithWrongKey()
    {
        $this->assertNull($this->html->css('fake'));
    }

    public function testGetCssWithWrongType()
    {
        $this->assertNull($this->html->css('bootstrap', 'fake'));
    }
}