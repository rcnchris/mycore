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
 * @version  Release: <0.0.1>
 */
class OneAPI
{

    use APITrait;

    /**
     * Options de curl par défaut
     *
     * @var array
     */
    protected $curlOptions = [
        CURLOPT_SSL_VERIFYPEER => false
        , CURLOPT_RETURNTRANSFER => true
        , CURLOPT_TIMEOUT => 10
        , CURLOPT_CONNECTTIMEOUT => 10
        //, CURLOPT_SSLVERSION => 'CURL_SSLVERSION_TLSv1_2'
    ];


    /**
     * Constructeur
     *
     * @param string|null $url URL de base
     */
    public function __construct($url = null)
    {
        $this->initialize($url);
        $this->setCurlOptions($this->curlOptions);
    }
}
