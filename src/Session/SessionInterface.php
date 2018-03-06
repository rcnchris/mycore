<?php
/**
 * Fichier SessionInterface.php du 05/03/2018
 * Description : Fichier de la classe SessionInterface
 *
 * PHP version 5
 *
 * @category New
 *
 * @package  Rcnchris\Core\Session
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Session;

/**
 * Interface SessionInterface
 *
 * @package Rcnchris\Core\Session
 */
interface SessionInterface
{

    /**
     * Obtenir la valeur d'une clé de la Session
     *
     * @param string $key     Nom de la clé
     * @param mixed  $default Valeur par défaut
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Ajoute une information en Session
     *
     * @param string $key   Nom de la clé
     * @param mixed  $value Valeur de la clé
     *
     * @return void
     */
    public function set($key, $value);

    /**
     * Supprime une clé de la Session
     *
     * @param string $key Nom de la clé
     *
     * @return void
     */
    public function delete($key);
}
