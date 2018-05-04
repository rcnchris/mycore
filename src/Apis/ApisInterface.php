<?php
/**
 * Fichier ApisInterface.php du 03/05/2018 
 * Description : Fichier de la classe ApisInterface 
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Rcnchris\Core\Apis
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis;


interface ApisInterface {

    /**
     * @param string $url URL de base de l'API (immuable)
     *
     * @return $this
     */
    public function setBaseUrl($url);
}