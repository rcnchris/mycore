<?php
/**
 * Fichier Session.php du 05/03/2018
 * Description : Fichier de la classe PHPSession
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
 * Class PHPSession
 *
 * @category Session
 *
 * @package  Rcnchris\Core\Session
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 *
 * @codeCoverageIgnore
 */
class PHPSession implements SessionInterface
{

    /**
     * Obtenir la valeur d'une clé de la Session
     *
     * ### Exemple
     * - `$session->get();`
     * - `$session->get('id');`
     * - `$session->get('nav', 'Firefox');`
     *
     * @param string $key     Nom de la clé
     * @param mixed  $default Valeur par défaut
     *
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        $this->ensureStarted();
        if (is_null($key)) {
            return $_SESSION;
        } elseif (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

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
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * Supprimme une clé de la Session
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
        $this->ensureStarted();
        unset($_SESSION[$key]);
    }

    /**
     * Assure que la session est démarrée
     * Ajoute l'id de session en tant que clé de la session
     */
    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            $this->set('id', session_id());
        }
    }
}
