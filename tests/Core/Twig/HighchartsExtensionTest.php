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

    public function testLine()
    {
        $expectDiv = '<div id="chartTest" style="height: 400px; min-width: 300px;"></div>';
        $expectScript = "<script>$(function () {var myChart = Highcharts.chart(chartTest, {chart: {type: 'line'},plotOptions: {line: {dataLabels: {enabled: true}}},legend: false,xAxis: { categories: ['Mathis','Raphaël','Clara',]},yAxis: {title: {'text': Minots}},title: {text: Graphique test},credits: {enabled: false},series: [{data: [10,10,8,]}]});});</script>";
        $items = ['Mathis' => 10, 'Raphaël' => 10, 'Clara' => 8];
        $this->assertSimilar(
            $expectDiv . $expectScript,
            $this->ext->line($items, [
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
        $expectDiv = '<div id="chart" style="height: 400px; min-width: 300px;"></div>';
        $expectScript = "<script>$(function () {var myChart = Highcharts.chart(chart, {chart: {type: 'line'},plotOptions: {line: {dataLabels: {enabled: true}}},legend: false,xAxis: { categories: ['Mathis','Raphaël','Clara',]},yAxis: {title: {'text': Minots}},title: {text: Graphique test},credits: {enabled: false},series: [{data: [10,10,8,]}]});});</script>";
        $items = ['Mathis' => 10, 'Raphaël' => 10, 'Clara' => 8];
        $this->assertSimilar(
            $expectDiv . $expectScript,
            $this->ext->line($items, [
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
        $expectDiv = '<div id="chart" style="height: 400px; min-width: 300px;"></div>';
        $expectScript = "<script>$(function () {var myChart = Highcharts.chart(chart, {chart: {type: 'line'},plotOptions: {line: {dataLabels: {enabled: true}}},legend: false,xAxis: { categories: ['Mathis','Raphaël','Clara',]},yAxis: {title: {'text': }},title: {text: },credits: {enabled: false},series: [{data: [10,10,8,]}]});});</script>";
        $items = ['Mathis' => 10, 'Raphaël' => 10, 'Clara' => 8];
        $this->assertSimilar(
            $expectDiv . $expectScript,
            $this->ext->line($items)
        );
    }
}
