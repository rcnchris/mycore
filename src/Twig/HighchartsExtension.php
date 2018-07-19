<?php
/**
 * Fichier HighchartsExtension.php du 18/07/2018
 * Description : Fichier de la classe HighchartsExtension
 *
 * PHP version 5
 *
 * @category Graphique
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Twig;

/**
 * Class HighchartsExtension
 *
 * @category Graphique
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class HighchartsExtension extends HtmlExtension
{

    /**
     * URL du CDN de Highcharts
     *
     * @var string
     */
    private $cdnUrl = 'https://code.highcharts.com/highcharts.js';

    /**
     * Options par défaut des graphiques
     *
     * @var array
     */
    private $defaultOptions = [
        'line' => [
            'id' => 'chart',
            'height' => 400,
            'width' => 300,
            'title' => null,
            'yTitle' => null,
            'credits' => 'false',
            'legend' => 'false'
        ]
    ];

    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('line', [$this, 'line'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Obtenir la balise `script` du CDN de Highcharts
     *
     * @param string|null $url URL du CDN
     *
     * @return string
     */
    public function cdn($url = null)
    {
        if (is_null($url)) {
            $url = $this->cdnUrl;
        }
        return '<script src="' . $url . '"></script>';
    }

    /**
     * Obtenir un graphique en ligne à partir d'un tableau de données
     *
     * @param array      $items   Données du graphique
     * @param array|null $options Options du graphique
     *
     * @return string
     */
    public function chartLine($items, array $options = [])
    {
        $options = array_merge($this->defaultOptions['line'], $options);

        $html = $this->surround(
            '',
            'div',
            [
                'id' => $options['id'],
                'style' => 'height: ' . $options['height'] . 'px; ' . 'min-width: ' . $options['width'] . 'px;',
            ]
        );

        $keys = '';
        $values = '';
        foreach ($items as $k => $v) {
            $keys .= "'$k',";
            $values .= "$v,";
        }
        $keys = substr($keys, 0, strlen($keys) - 1);
        $values = substr($values, 0, strlen($values) - 1);

        $js = "$(function () {";
        $js .= "var myChart = Highcharts.chart(" . $options['id'] . ", {";
        $js .= "chart: {type: 'line'},";
        $js .= "plotOptions: {line: {dataLabels: {enabled: true}}},";
        $js .= "xAxis: {categories: [$keys]},";
        $js .= "yAxis: {title: {text: '" . $options['yTitle'] . "'}},";
        $js .= "title: {text: '" . $options['title'] . "'},";
        $js .= "legend: " . $options['legend'] . ",";
        $js .= "credits: {enabled: " . $options['credits'] . "},";
        $js .= "series: [{data: [$values]}]";
        $js .= "});});";

        return $html . $this->surround($js, 'script');
    }
}
