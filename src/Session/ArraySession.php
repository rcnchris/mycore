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
 * <ul>
 * <li>Gestion des sessions utilisateurs sous la forme de tableau</li>
 * </ul>
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
     * Constructeur
     *
     * Ajoute l'id de session en tant que clé
     */
    public function __construct()
    {
        $this->set('id', uniqid());
    }

    /**
     * Obtenir la valeur d'une clé de la Session
     *
     * ### Exemple
     * - `$session->get();`
     * - `$session->get('id');`
     * - `$session->get('nav', 'Firefox');`
     *
     * @param string|null $key     Nom de la clé, si null toute les clés/valeurs sont retournées
     * @param mixed       $default Valeur par défaut
     *
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->session;
        } elseif (array_key_exists($key, $this->session)) {
            return $this->session[$key];
        }
        return $default;
    }

    /**
     * Obtenir la valeur d'une clé de la session lors de l'appel sous forme d'objet
     *
     * ### Exemple
     * - `$session->nav;`
     *
     * @param string $key Nom de la clé
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Ajoute une information en Session
     *
     * ### Exemple
     * - `$session->set('ip', '192.168.1.99');`
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
     * ### Exemple
     * - `$session->delete('nav');`
     *
     * @param string $key Nom de la clé
     *
     * @return void
     */
    public function delete($key)
    {
        unset($this->session[$key]);
    }

    /**
     * Vérifie la présence d'une clé en session
     *
     * ### Exemple
     * - `$session->has('ip');`
     *
     * @param string $key Nom de la clé en session
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->session);
    }
}
