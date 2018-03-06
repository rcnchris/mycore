<?php
/**
 * Fichier ArraySession.php du 05/03/2018
 * Description : Fichier de la classe ArraySession
 *
 * PHP version 5
 *
 * @category Session
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
 * Class ArraySession
 *
 * @category Session
 *
 * @package  Rcnchris\Core\Session
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class ArraySession implements SessionInterface
{

    /**
     * Variables de session sous forme de tableau
     *
     * @var array
     */
    private $session = [];

    /**
     * Obtenir la valeur d'une clé de la Session
     *
     * @param string $key     Nom de la clé
     * @param mixed  $default Valeur par défaut
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->session)) {
            return $this->session[$key];
        }
        return $default;
    }

    /**
     * Ajoute une information en Session
     *
     * @param string $key   Nom de la clé
     * @param mixed  $value Valeur de la clé
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->session[$key] = $value;
    }

    /**
     * Supprime une clé de la Session
     *
     * @param string $key Nom de la clé
     *
     * @return void
     */
    public function delete($key)
    {
        unset($this->session[$key]);
    }
}
