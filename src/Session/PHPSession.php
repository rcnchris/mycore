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
     * Constructeur
     *
     * @param string|null $name Nom de la session
     */
    public function __construct($name = null)
    {
        if (!is_null($name)) {
            $name = strtoupper(str_replace(' ', '', $name));
            session_name($name);
        }
    }

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
     * Obtenir le nom de la session
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParams('name');
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
        $this->ensureStarted();
        unset($_SESSION[$key]);
    }

    /**
     * Retourne la configuration actuelle du délai d'expiration du cache
     *
     * @return int
     */
    public function getCacheExpired()
    {
        return session_cache_expire();
    }

    /**
     * Définit le nombre de minutes de conservation du cache
     *
     * @param int $value
     */
    public function setCacheExpired($value = 180)
    {
        session_cache_expire($value);
    }

    /**
     * Assure que la session est démarrée
     * Ajoute l'id de session en tant que clé de la session
     */
    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            //$this->setCacheExpired();
            session_start();
            $this->set('id', session_id());
        }
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
     * Obtenir la valeur d'une clé de la session lors de l'appel d'une méthode inaccessible
     *
     * @param       $key
     * @param array $params
     *
     * @return mixed
     */
    public function __call($key, array $params = [])
    {
        return $this->get($key);
    }

    /**
     * Obtenir les données de session au format json
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->get());
    }

    /**
     * Obtenir les paramètres de session ou l'un d'entre eux
     *
     * @param string|null $key Nom de la clé recherchée
     *
     * @return array|mixed|bool
     */
    public function getParams($key = null)
    {
        $values = [
            'name' => session_name(),
            'cache_expire' => session_cache_expire(),
            'cache_limiter' => session_cache_limiter(),
            'module_name' => session_module_name(),
            'save_path' => session_save_path(),
        ];
        if (is_null($key)) {
            return $values;
        } elseif (array_key_exists($key, $values)) {
            return $values[$key];
        }
        return false;
    }
}
