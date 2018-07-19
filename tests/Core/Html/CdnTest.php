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
        $expect = '<script src="https://code.highcharts.com/highcharts.js"></script>';
        $this->assertSimilar($expect, $this->cdn->script('highcharts'));
    }

    public function testGetScriptMin()
    {
        $expect = '<script src="/components/jquery/jquery.min.js"></script>';
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
        $expect = '<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.css"/>';
        $this->assertSimilar($expect, $this->cdn->css('datatables'));
    }

    public function testGetCssLinkMin()
    {
        $expect = '<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>';
        $this->assertSimilar($expect, $this->cdn->css('datatables', 'min'));
    }

    public function testGetCssWithWrongKey()
    {
        $this->assertNull($this->cdn->css('fake'));
    }

    public function testGetCssWithWrongType()
    {
        $this->assertNull($this->cdn->css('bootstrap4', 'fake'));
    }
}
