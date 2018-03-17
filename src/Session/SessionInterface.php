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
 * <ul>
 * <li>Gestion des sessions utilisateurs</li>
 * </ul>
 *
 * @package Rcnchris\Core\Session
 */
interface SessionInterface
{

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
    public function get($key = null, $default = null);

    /**
     * Obtenir la valeur d'une clé de la session lors de l'appel sous forme d'objet
     *
     * ### Exemple
     * - `$session->ip;`
     *
     * @param string $key Nom de la clé
     *
     * @return mixed
     */
    public function __get($key);

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
    public function has($key);

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
    public function set($key, $value);

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
    public function delete($key);
}
