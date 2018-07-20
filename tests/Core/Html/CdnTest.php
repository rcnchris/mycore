<?php
namespace Tests\Rcnchris\Core\Html;

use Rcnchris\Core\Html\Cdn;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class CdnTest extends BaseTestCase
{
    /**
     * @var Cdn
     */
    private $cdn;

    public function setUp()
    {
        $this->cdn = new Cdn($this->getConfig('cdn'));
    }

    public function testInstance()
    {
        $this->ekoTitre('Html - CDN');
        $this->assertInstanceOf(Cdn::class, $this->cdn);
    }

    public function testGet()
    {
        $this->assertInstanceOf(Items::class, $this->cdn->get('jquery'));
    }

    public function testGetMagic()
    {
        $this->assertInstanceOf(Items::class, $this->cdn->jquery);
    }

    public function testHas()
    {
        $this->assertTrue($this->cdn->has('jquery'));
        $this->assertFalse($this->cdn->has('fake'));
    }

    public function testGetScript()
    {
        $expect = '<script src="https://code.highcharts.com/highcharts.js" type="text/javascript"></script>';
        $this->assertSimilar($expect, $this->cdn->script('highcharts'));
    }

    public function testGetScriptMin()
    {
        $expect = '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" type="text/javascript"></script>';
        $this->assertSimilar($expect, $this->cdn->script('jquery', 'min'));
    }

    public function testGetScriptWithWrongType()
    {
        $this->assertNull($this->cdn->script('jquery', 'fake'));
    }

    public function testGetScriptWithMissingKey()
    {
        $this->assertNull($this->cdn->script('fake', 'src'));
    }

    public function testGetCssLink()
    {
        $expect = '<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>';
        $this->assertSimilar($expect, $this->cdn->css('datatables'));
    }

    public function testGetCssLinkMin()
    {
        $expect = '<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>';
        $this->assertSimilar($expect, $this->cdn->css('datatables', 'min'));
    }

    public function testGetCssWithWrongKey()
    {
        $this->assertNull($this->cdn->css('fake'));
    }

    public function testGetCssWithWrongType()
    {
        $this->assertNull($this->cdn->css('bootstrap', 'fake'));
    }
}
