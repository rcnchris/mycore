<?php
/**
 * Fichier Wkhtmltopdf.php du 08/01/2019
 * Description : Fichier de la classe Wkhtmltopdf
 *
 * PHP version 5
 *
 * @category New
 *
 * @package  PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF;

use mikehaertl\wkhtmlto\Pdf;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

/**
 * Class Wkhtmltopdf
 *
 * @category Wkhtmltopdf
 *
 * @package  PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Wkhtmltopdf extends Pdf
{
    /**
     * Options par défaut d'un document PDF généré par Wkhtmltopdf
     *
     * @var array
     */
    private $defaultOptions = [
        'page-size' => 'A4',
        'orientation' => 'Portrait',
        'encoding' => 'utf-8',
        'disable-smart-shrinking',
        'disable-javascript',
        // Options
        'header-line',
        'header-spacing' => 3,
        'header-center' => "[doctitle]",
        'footer-line',
        'footer-spacing' => 3,
        'footer-font-name' => 'Courier',
        'footer-font-size' => 7,
        'footer-left' => "[date], [time]",
        'footer-right' => "Page [page] sur [toPage]",
        //'user-style-sheet' => null,
    ];

    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        if (is_null($options)) {
            $options = [];
        }
        parent::__construct(array_merge($this->defaultOptions, $options));
    }

    /**
     * Vérifier la présence de Wkhtmltopdf sur le système
     *
     * @return bool
     */
    public function wkhtmltopdfEnabled()
    {
        return !is_null($this->getVersion());
    }

    /**
     * @return string|null
     */
    public function getVersion()
    {
        return `wkhtmltopdf --version`;
    }

    /**
     * Obtenir les options ou l'une d'entre elle
     *
     * @param null $key
     *
     * @return array|null
     */
    public function getOptions($key = null)
    {
        if (is_null($key)) {
            return $this->_options;
        } elseif (array_key_exists($key, $this->_options)) {
            return $this->_options[$key];
        } else {
            $id = array_search($key, $this->_options);
            if ($id) {
                return $this->_options[$id];
            }
        }
        return null;
    }

    /**
     * Vérifier la présence d'une option
     *
     * @param string $name Nom de l'option
     *
     * @return bool
     */
    public function hasOption($name)
    {
        if (array_key_exists($name, $this->_options)) {
            return true;
        } elseif (array_search($name, $this->_options)) {
            return true;
        }
        return false;
    }

    /**
     * Rendre le document PDF dans une réponse PSR7
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array                               $options
     *
     * @return static
     */
    public function render(ResponseInterface $response = null, array $options = [])
    {
        if (is_null($response)) {
            $response = new Response();
        }
        $body = $response->getBody();
        $body->write($this->send());
        if ($this->getError()) {
            $body->write($this->getError());
            return $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->withBody($body);
        }
        return $response
            ->withStatus(200)
            ->withHeader('Content-type', 'application/pdf')
            ->withAddedHeader('Content-Disposition', 'inline; filename="' . $this->getOptions('title') . '"')
            ->withAddedHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->withAddedHeader('Pragma', 'public')
            ->withBody($body);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array                               $options
     * @param null                                $dest
     *
     * @return static
     */
    public function download(ResponseInterface $response = null, array $options = [], $dest = null)
    {
        if (is_null($response)) {
            $response = new Response();
        }
        $body = $response->getBody();
        $body->write($this->send($dest));
        if ($this->getError()) {
            $body->write($this->getError());
            return $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->withBody($body);
        }
        return $response
            ->withStatus(200)
            ->withHeader('Content-type', 'application/x-download')
            ->withAddedHeader('Content-Disposition', 'attachment; filename="' . $dest . '"')
            ->withAddedHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->withAddedHeader('Pragma', 'public')
            ->withBody($body);
    }

    /**
     * Défnir le fichier CSS à utiliser pour rendre les pages du document PDF
     *
     * @param string $path Chemin vers le fichier CSS
     *
     * @return $this
     */
    public function withCss($path)
    {
        if (is_file($path)) {
            $this->setOption('user-style-sheet', $path);
        }
        return $this;
    }

    /**
     * Définir une option
     *
     * @param string     $key   Clé ou valeur unique
     * @param mixed|null $value Valeur de la clé
     *
     * @return $this
     */
    public function setOption($key, $value = null)
    {
        if (is_null($value)) {
            $this->setOptions(array_merge($this->_options, [$key]));
        }
        $this->setOptions(array_merge($this->_options, [$key => $value]));
        return $this;
    }

    /**
     * @param string      $input   URL, a HTML string or a filename
     * @param array|null  $options Options de la page
     * @param string|null $type    a type hint if the input is a string of known type. This can either be `TYPE_HTML`
     *                             or `TYPE_XML`. If `null` (default)
     *
     * @return static
     */
    public function addPage($input, $options = [], $type = null)
    {
        if (mb_substr($input, 0, 1) === '/') {
            $input = 'http://localhost' . $input;
        }
        return parent::addPage($input, $options, $type);
    }
}
