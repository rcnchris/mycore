<?php
/**
 * Fichier HighchartsExtensionTest.php du 18/07/2018
 * Description : Fichier de la classe HighchartsExtensionTest
 *
 * PHP version 5
 *
 * @category New
 *
 * @package  Tests\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\HighchartsExtension;
use Tests\Rcnchris\BaseTestCase;

/**
 * Class HighchartsExtensionTest
 *
 * @category New
 *
 * @package  Tests\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class HighchartsExtensionTest extends BaseTestCase
{
    /**
     * @var HighchartsExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new HighchartsExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Highcharts');
        $this->assertInstanceOf(HighchartsExtension::class, $this->ext);
        $this->assertNotEmpty($this->ext->getFilters());
        $this->assertNotEmpty($this->ext->getFunctions());
    }

    public function testCdn()
    {
        $expect = '<script src="https://code.highcharts.com/highcharts.js"></script>';
        $this->assertSimilar($expect, $this->ext->cdn());
    }

    public function testCdnWithOldies()
    {
        $expect = '<script src="https://code.highcharts.com/highcharts.js"></script>';
        $this->assertSimilar($expect, $this->ext->cdn());
    }

    public function testLine()
    {
        $items = ['Mathis' => 10, 'Raphaël' => 10, 'Clara' => 8];
        $expectDiv = '<div id="chartTest" style="height: 400px; min-width: 300px;"></div>';
        $expectScript = "<script>
            $(function () {
                var myChart = Highcharts.chart(chartTest, {
                    chart: {type: 'line'},
                    plotOptions: {line: {dataLabels: {enabled: true}}},
                    xAxis: {
                        categories: ['Mathis','Raphaël','Clara']
                    },
                    yAxis: {title: {text: 'Minots'}},
                    title: {text: 'Graphique test'},
                    legend: false,
                    credits: {enabled: false},
                    series: [{
                        data: [10,10,8]
                    }]
                });
            });
        </script>";

        $this->assertSimilar(
            $expectDiv . $expectScript,
            $this->ext->chartLine($items, [
                    'id' => 'chartTest',
                    'height' => 400,
                    'width' => 300,
                    'yTitle' => 'Minots',
                    'title' => 'Graphique test'
                ]
            )
        );
    }

    public function testLineWithoutId()
    {
        $items = ['Mathis' => 10, 'Raphaël' => 10, 'Clara' => 8];
        $expectDiv = '<div id="chart" style="height: 400px; min-width: 300px;"></div>';
        $expectScript = "<script>
            $(function () {
                var myChart = Highcharts.chart(chart, {
                    chart: {type: 'line'},
                    plotOptions: {line: {dataLabels: {enabled: true}}},
                    xAxis: {
                        categories: ['Mathis','Raphaël','Clara']
                    },
                    yAxis: {title: {text: 'Minots'}},
                    title: {text: 'Graphique test'},
                    legend: false,
                    credits: {enabled: false},
                    series: [{
                        data: [10,10,8]
                    }]
                });
            });
        </script>";

        $this->assertSimilar(
            $expectDiv . $expectScript,
            $this->ext->chartLine($items, [
                    'height' => 400,
                    'width' => 300,
                    'yTitle' => 'Minots',
                    'title' => 'Graphique test'
                ]
            )
        );
    }

    public function testLineWithMinParameters()
    {
        $items = ['Mathis' => 10, 'Raphaël' => 10, 'Clara' => 8];
        $expectDiv = '<div id="chart" style="height: 400px; min-width: 300px;"></div>';
        $expectScript = "<script>
            $(function () {
                var myChart = Highcharts.chart(chart, {
                    chart: {type: 'line'},
                    plotOptions: {line: {dataLabels: {enabled: true}}},
                    xAxis: {
                        categories: ['Mathis','Raphaël','Clara']
                    },
                    yAxis: {title: {text: ''}},
                    title: {text: ''},
                    legend: false,
                    credits: {enabled: false},
                    series: [{
                        data: [10,10,8]
                    }]
                });
            });
        </script>";

        $this->assertSimilar($expectDiv . $expectScript, $this->ext->chartLine($items));
    }
}
