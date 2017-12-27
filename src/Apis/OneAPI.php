<?php
/**
 * Fichier OneAPI.php du 26/12/2017
 * Description : Fichier de la classe OneAPI
 *
 * PHP version 7
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis;

/**
 * Class OneAPI<br/>
 * <ul>
 * <li>Représente n'importe quelle API à partir de son URL</li>
 * </ul>
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <0.0.1>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class OneAPI
{

    use APITrait;

    /**
     * Constructeur
     *
     * @param string|null $url URL de base
     */
    public function __construct($url = null)
    {
        $this->initialize($url);
    }

    /**
     * Obtenir la réponse sous la forme d'un tableau
     *
     * @return bool|array
     */
    public function toArray()
    {
        if (is_null($this->response)) {
            $this->request();
        }
        if ($this->getCurlInfos('http_code') === 200) {
            if (is_string($this->response)) {
                return json_decode($this->response, true);
            }
        }
        return curl_error($this->curl);
    }

    /**
     * Obtenir le résultat au format JSON
     *
     * @return bool|int|string
     */
    public function toJson()
    {
        if (is_null($this->response)) {
            $this->request();
        }
        if ($this->getCurlInfos('http_code') === 200) {
            return json_encode($this->toArray());
        }
        return curl_error($this->curl);
    }
}
