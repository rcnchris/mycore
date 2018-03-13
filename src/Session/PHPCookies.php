<?php
/**
 * Fichier PHPCookies.php du 12/03/2018
 * Description : Fichier de la classe PHPCookies
 *
 * PHP version 5
 *
 * @category Cookies
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
 * Class PHPCookies
 * <ul>
 * <li>manipulation des cookies.</li>
 * <li>Implémentation de l'interface <code>CookiesInterface</code>.</li>
 * </ul>
 *
 * @category Cookies
 *
 * @package  Rcnchris\Core\Session
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class PHPCookies implements CookiesInterface
{

    /**
     * Options par défaut des cookies
     *
     * @var array
     */
    private $defaultOptions = [
        'lifetime' => 0,
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httponly' => true
    ];

    /**
     * Constructeur
     *
     * @param mixed|null $datas   Données à écrire dans les cookies
     * @param array      $options Options des cookies (lifetime, path, domain, secure, httponly)
     */
    public function __construct($datas = null, array $options = [])
    {
        $options = array_merge($this->defaultOptions, $options);
        $this->setParams(
            $options['lifetime'],
            $options['path'],
            $options['domain'],
            $options['secure'],
            $options['httponly']
        );
    }

    /**
     * Obtenir la valeur d'un cookie
     *
     * ### Exemple
     * - `$cookie->get();`
     * - `$cookie->get('id');`
     * - `$cookie->get('nav', 'Firefox');`
     *
     * @param string|null $key     Nom de la clé, si null toute les clés/valeurs sont retournées
     * @param mixed       $default Valeur par défaut
     *
     * @return mixed
     * @codeCoverageIgnore
     */
    public function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $_COOKIE;
        } elseif (array_key_exists($key, $_COOKIE)) {
            return $_COOKIE[$key];
        }
        return false;
    }

    /**
     * Obtenir la valeur d'un cookie lorsque que la clé est appelé comme un objet
     *
     * ### Exemple
     * - `$cookie->ip;`
     *
     * @param string $key Nom de la clé
     *
     * @return mixed
     * @codeCoverageIgnore
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Obtenir les cookies sérialisés
     *
     * ### Exemple
     * - `echo (string)$cookies;`
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->get());
    }

    /**
     * Définir la valeur d'un cookie
     *
     * ### Exemple
     * - `$cookies->set('nav', 'Firefox');`
     *
     * @param string      $name     Nom de la clé du cookie
     * @param mixed|null  $value    Valeur du cookie
     * @param int|null    $expire   Le temps après lequel le cookie expire. C'est un timestamp Unix, donc, ce sera un
     *                              nombre de secondes depuis l'époque Unix (1 Janvier 1970).
     * @param string|null $path     Le chemin sur le serveur sur lequel le cookie sera disponible. Si la valeur est
     *                              '/',
     *                              le cookie sera disponible sur l'ensemble du domaine domain.
     * @param string|null $domain   Le domaine pour lequel le cookie est disponible. Le fait de définir le domaine à
     *                              'www.example.com' rendra le cookie disponible pour le sous-domaine www mais aussi
     *                              pour
     *                              les sous-domaines supérieurs (ex: 'sub.www.example.com').
     * @param bool|null   $secure   Indique si le cookie doit uniquement être transmis à travers une connexion
     *                              sécurisée
     *                              HTTPS depuis le client.
     * @param bool|null   $httponly Lorsque ce paramètre vaut TRUE, le cookie ne sera accessible que par le protocole
     *                              HTTP. Cela signifie que le cookie ne sera pas accessible via des langages de
     *                              scripts, comme Javascript.
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function set(
        $name,
        $value = null,
        $expire = 0,
        $path = null,
        $domain = null,
        $secure = false,
        $httponly = false
    ) {
        //return setrawcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * Supprime un cookie par son nom
     *
     * ### Exemple
     * - `$cookies->delete('nav');`
     * - `$cookies->delete('nav', false);`
     *
     * @param string    $name    Nom de la clé du cookie
     * @param bool|null $expired Uniquement si expiré
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function delete($name, $expired = true)
    {
        if ($expired) {
            setcookie($name, null, time() - 1);
        } else {
            unset($_COOKIE[$name]);
        }
        return in_array($name, $_COOKIE) === false;
    }

    /**
     * Obtenir les paramètres de cookies
     *
     * ### Exemple
     * - `$cookies->getParams();`
     * - `$cookies->getParams('path');`
     *
     * @param string|null $key Nom de la clé à retourner
     *
     * @return array|mixed
     * @codeCoverageIgnore
     */
    public function getParams($key = null)
    {
        $params = session_get_cookie_params();
        if (is_null($key)) {
            return $params;
        } elseif (array_key_exists($key, $params)) {
            return $params[$key];
        }
        return false;
    }

    /**
     * Modifie les paramètres du cookie de session
     *
     * ### Exemple
     * - `$cookies->setParams(time() + 3600 * 24, '/', 'localhost', false, true);`
     *
     * @param int       $lifetime Durée de vie du cookie en secondes
     * @param string    $path     Le chemin dans le domaine où le cookie sera accessible. Utilisez un simple slash
     *                            ('/') pour tous les chemins du domaine.
     * @param string    $domain   Le domaine du cookie, par exemple 'www.php.net'. Pour rendre les cookies visibles sur
     *                            tous les sous-domaines, le domaine doit être préfixé avec un point, tel que
     *                            '.php.net'.
     * @param bool|null $secure   Si TRUE, le cookie ne sera envoyé que sur une connexion sécurisée.
     * @param bool|null $httponly Si TRUE, PHP va tenter d'envoyer l'option httponly lors de la configuration du cookie.
     *
     * @return void
     */
    public function setParams($lifetime, $path, $domain, $secure = false, $httponly = false)
    {
        session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
    }
}
