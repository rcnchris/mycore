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
     * @param string $key     Nom de la clé
     * @param mixed  $default Valeur par défaut
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $this->ensureStarted();
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
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
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * Supprimme une clé de la Session
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
     * Obtenir l'identifiant de la session
     *
     * @return string
     */
    public function getId()
    {
        $this->ensureStarted();
        return session_id();
    }

    /**
     * Assure que la session est démarrée
     */
    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
