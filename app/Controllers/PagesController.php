<?php
/**
 * Fichier PagesController.php du 05/01/2018 
 * Description : Fichier de la classe PagesController 
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Rcnchris\App\Controllers
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Rcnchris\App\Controllers;
use Michelf\MarkdownExtra;
use Rcnchris\Core\Apis\OneAPI;
use Rcnchris\Core\Apis\Synology\SynologyAbstract;
use Rcnchris\Core\Tools\Cmd;
use Rcnchris\Core\Tools\Collection;
use Rcnchris\Core\Tools\Composer;
use Rcnchris\Core\Tools\Folder;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class PagesController
 *
 * @category New
 *
 * @package Rcnchris\App\Controllers
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris/fmk-php GPL
 *
 * @version Release: <1.0.0>
 *
 * @link https://github.com/rcnchris/fmk-php on Github
 */
class PagesController extends Controller
{
    /**
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     */
    public function home(Request $request, Response $response)
    {
        $config = $this->getContainer();
        $readmeFile = $this->rootPath() . '/README.md';
        if (file_exists($readmeFile)) {
            $content = file_get_contents($readmeFile);
            $readme = MarkdownExtra::defaultTransform($content);
        }
        $this->render($response, 'home', compact('config', 'readme'));
    }

    public function common(Request $request, Response $response)
    {
        $this->render($response, 'Tools/common');
    }

    public function text(Request $request, Response $response)
    {
        $this->render($response, 'Tools/text');
    }

    public function folder(Request $request, Response $response)
    {
        $folder = new Folder($this->rootPath());
        $this->render($response, 'Tools/folder', compact('folder'));
    }

    public function cmd(Request $request, Response $response)
    {
        $cmd = Cmd::getInstance();
        $this->render($response, 'Tools/cmd', compact('cmd'));
    }

    public function composer(Request $request, Response $response)
    {
        $composer = new Composer($this->rootPath() . '/composer.json');
        $this->render($response, 'Tools/composer', compact('composer'));
    }

    public function collection(Request $request, Response $response)
    {
        $c1 = new Collection('ola,ole,oli', 'Liste au format texte avec séparateur');
        $c2 = new Collection(['ola', 'ole', 'oli'], 'Liste au format texte avec séparateur');
        $cdn = new Collection([
            'jquery' => [
                'name' => 'jQuery',
                'path' => '/components/jquery',
                'require' => true,
                'package' => '/package.json',
                'composer' => '/composer.json',
                'readme' => '/README.md',
                'link' => 'https://jquery.com',
                'core' => [
                    'js' => [
                        'min' => '/jquery.min.js',
                        'src' => '/jquery.js',
                    ],
                ],
            ],

            'foundation' => [
                'name' => 'Foundation',
                'path' => '/zurb/foundation',
                'require' => true,
                'favicon' => '/docs/assets/img/logos/foundation-sites-nuget-icon-128x128.jpg',
                'package' => '/package.json',
                'composer' => '/composer.json',
                'readme' => '/README.md',
                'link' => 'http://foundation.zurb.com',
                'exemples' => [],
                'core' => [
                    'css' => [
                        'min' => '/dist/css/foundation.min.css',
                        'src' => '/dist/css/foundation.css'
                    ],
                    'js' => [
                        'min' => '/dist/js/foundation.min.js',
                        'src' => '/dist/js/foundation.js',
                    ],
                ],
            ],
        ]);

        $c3 = new Collection([
            ['name' => 'Mathis', 'year' => 2007, 'genre' => 'male']
            , ['name' => 'Raphaël', 'year' => 2007, 'genre' => 'male']
            , ['name' => 'Clara', 'year' => 2009, 'genre' => 'female']
        ]);

        $c4 = new Collection('[{"name":"Mathis","year":2007,"genre":"male"},{"name":"Rapha\u00ebl","year":2007,"genre":"male"},{"name":"Clara","year":2009,"genre":"female"}]');

        $this->render($response, 'Tools/collection', compact('c1', 'c2', 'c3', 'c4', 'cdn'));
    }

    public function oneapi(Request $request, Response $response)
    {
        $api = (new OneAPI('https://randomuser.me/api'));
        $this->render($response, 'Apis/oneapi', compact('api'));
    }

    public function synology(Request $request, Response $response)
    {
        $config = $this->getContainer()->get('synology')['nas'];
        $nas = new SynologyAbstract($config);
        $video = $nas->getPackage('VideoStation');
        $this->render($response, 'Apis/synology', compact('nas', 'video'));
    }

}