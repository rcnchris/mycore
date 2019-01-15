<?php
/**
 * Fichier Environnement.php du 12/07/2018
 * Description : Fichier de la classe Environnement
 *
 * PHP version 5
 *
 * @category Environnement
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Tools;

/**
 * Class Environnement
 * <ul>
 * <li>Permet d'accéder aux variables d'environnement</li>
 * </ul>
 *
 * @category Environnement
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Environnement
{
    /**
     * Aide de cette classe
     *
     * @var array
     */
    private static $help = [
        "Permet d'accéder aux variables d'environnement",
        "Statique et instanciable via <code>getInstance</code>",
    ];

    /**
     * Tableau `$_SERVER` dans une instance de Items
     *
     * @var \Rcnchris\Core\Tools\Items
     */
    private $server;

    /**
     * Instance
     *
     * @var $this
     */
    private static $instance;

    /**
     * Constructeur
     *
     * @param array|null $server Contenu de la variable `$_SERVER`
     */
    private function __construct($server = null)
    {
        $this->server = is_null($server) ? new Items($_SERVER) : new Items($server);
    }


    /**
     * Obtenir une instance (Singleton)
     *
     * @param null $server
     *
     * @return \Rcnchris\Core\Tools\Debug
     */
    public static function getInstance($server = null)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($server);
        }
        return self::$instance;
    }


    /**
     * Est appelée pour lire des données depuis des propriétés inaccessibles.
     *
     * @param string $key Propriété à retourner
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Items
     */
    public function __get($key)
    {
        return self::get($key);
    }

    /**
     * Obtenir tous les paramètres ou l'un d'entre eux
     *
     * @param string|null $key Nom de la clé à retourner
     *
     * @return \Rcnchris\Core\Tools\Items|mixed|null
     */
    public function get($key = null)
    {
        if (is_null($key)) {
            return $this->server;
        }
        return $this->server->get($key);
    }

    /**
     * Obtenir le nom du serveur Web
     *
     * @return string
     */
    public function getServerName()
    {
        return $this->get('SERVER_NAME');
    }

    /**
     * Obtenir la version du serveur Linux
     *
     * @param string $opt Voir help uname
     *
     * @return string
     */
    public function getUname($opt = 'a')
    {
        return Cmd::exec('uname -' . $opt);
    }

    /**
     * Obtenir l'adresse IP du serveur ou celle du client
     *
     * @param string|null $who server ou remote
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getIp($who = 'server')
    {
        if ($who === 'server') {
            return $this->get('SERVER_ADDR');
        } elseif ($who === 'remote') {
            return $this->get('REMOTE_ADDR');
        }
        return null;
    }

    /**
     * Obtenir la version du serveur Apache
     *
     * @return string
     * @see http://php.net/manual/fr/function.apache-get-version.php
     * @codeCoverageIgnore
     */
    public function getApacheVersion()
    {
        return apache_get_version();
    }

    /**
     * Obtenir les modules d'Apache
     *
     * @return \Rcnchris\Core\Tools\Items
     * @see http://php.net/manual/fr/function.apache-get-modules.php
     * @codeCoverageIgnore
     */
    public function getApacheModules()
    {
        return $this->makeItems(apache_get_modules());
    }

    /**
     * Obtenir l'utilisateur Apache
     *
     * @return string
     */
    public function getApacheUser()
    {
        return Cmd::exec('whoami');
    }

    /**
     * Obtenir la version de MySQL
     *
     * @return string
     */
    public function getMysqlVersion()
    {
        return Cmd::exec('mysql -V');
    }

    /**
     * Obtenir la version de PHP
     *
     * @param bool|true $short Si vrai, seul le numéro de version est retourné
     *
     * @return string
     */
    public function getPhpVersion($short = true)
    {
        $version = PHP_VERSION;
        if ($short) {
            $parts = explode('+', $version);
            return current($parts);
        } else {
            return $version;
        }
    }

    /**
     * Obtenir l'emplacement du fichier INI chargé
     *
     * @param bool|null $content Si vrai, c'est le contenu du fichier INI chargé qui est retourné
     *
     * @return null|string
     */
    public function getPhpIniFile($content = false)
    {
        $path = php_ini_loaded_file();
        return $path ? ($content ? file_get_contents($path) : $path) : null;
    }

    /**
     * Obtenir la liste des fichiers .ini analysés dans les dossiers de configuration supplémentaires
     *
     * @param string|null $file Fichier à chercher
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getPhpIniFiles($file = null)
    {
        $files = str_replace("\n", '', php_ini_scanned_files());
        $files = explode(',', $files);
        if (!is_null($file)) {
            $files = array_filter($files, function ($item) use ($file) {
                return strstr($item, $file) ? $item : null;
            });
        }
        return $this->makeItems($files);
    }

    /**
     * Obtenir les extensions PHP
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getPhpExtensions()
    {
        return $this->makeItems(get_loaded_extensions());
    }

    /**
     * Obtenir les modules PHP chargés
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getPhpModules()
    {
        $modules = Cmd::exec('php -m');
        array_shift($modules);
        $modules = array_filter($modules);
        return $this->makeItems($modules);
    }

    /**
     * Obtenir la liste des drivers PDO
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getPdoDrivers()
    {
        return $this->makeItems(\PDO::getAvailableDrivers());
    }

    /**
     * Obtenir le timezone courant
     *
     * @return string
     */
    public function getTimezone()
    {
        return date_default_timezone_get();
    }

    /**
     * Obtenir la liste des timezones
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getTimezones()
    {
        return $this->makeItems(timezone_identifiers_list());
    }

    /**
     * Obtenir la liste de toutes les locales
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getLocales()
    {
        return $this->makeItems(intlcal_get_available_locales());
    }

    /**
     * Obtenir le charset courant
     *
     * @return bool|mixed|string
     */
    public function getCharset()
    {
        return mb_internal_encoding();
    }

    /**
     * Obtenir le code erreur courant
     *
     * @return int
     */
    public function getPhpErrorReporting()
    {
        return error_reporting();
    }

    /**
     * Obtenir le type d'interface utilisée entre le serveur web et PHP
     *
     * @return string
     */
    public function getSapi()
    {
        return php_sapi_name();
    }

    /**
     * Obtenir la version de Git
     *
     * @param bool|null $short Si vrai, seul le numéro de version est retourné
     *
     * @return string
     */
    public function getGitVersion($short = false)
    {
        $version = Cmd::exec('git --version');
        return $short ? trim(str_replace('git version', '', $version)) : $version;
    }

    /**
     * Obtenir la version de Curl
     *
     * @param bool|null $short Si vrai, seul le numéro de version est retourné
     *
     * @return \Rcnchris\Core\Tools\Items|mixed|null
     */
    public function getCurlVersion($short = true)
    {
        $curl = $this->makeItems(curl_version());
        return $short ? $curl->get('version') : $curl;
    }

    /**
     * Obrenir la version de Composer
     *
     * @return string
     */
    public function getComposerVersion()
    {
        return Cmd::exec('composer -V');
    }

    /**
     * Obtenir la version de Wkhtmltopdf
     *
     * @return string
     * @see https://wkhtmltopdf.org
     */
    public function getWkhtmltopdfVersion()
    {
        return Cmd::exec('wkhtmltopdf -V');
    }

    /**
     * Obtenir les données dans une instance de Items
     *
     * @param mixed $items Données à instancier
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    private static function makeItems($items = [])
    {
        return new Items($items);
    }

    /**
     * Obtenir le nom court du navigateur
     *
     * @param string|null $ua   Retour de $_SERVER['HTTP_USER_AGENT'] pour Apache
     * @param bool|null   $full Obtenir le nom complet
     *
     * @return string
     */
    public function getUserAgent($ua = null, $full = false)
    {
        if (is_null($ua)) {
            $ua = $this->get('HTTP_USER_AGENT');
        }
        if ($full) {
            return $ua;
        }
        $browsers = [
            'Firefox' => 'Firefox',
            'OPR' => 'Opera',
            'Edge' => 'Edge',
            'MSIE' => 'Internet Explorer',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Mozilla' => 'Firefox'
        ];
        foreach ($browsers as $term => $browser) {
            if (preg_match("/$term/", $ua, $matches, PREG_OFFSET_CAPTURE)) {
                $ua = $browser;
            }
        }
        return $ua;
    }

    /**
     * Obtenir l'aide de cette classe
     *
     * @param bool|null $text Si faux, c'est le tableau qui est retourné
     *
     * @return array|string
     */
    public static function help($text = true)
    {
        if ($text) {
            return join('. ', self::$help);
        }
        return self::$help;
    }

    /**
     * Obtenir la liste des contantes définies
     *
     * @param string|null $key Nom d'une clé des constantes
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Items
     */
    public static function getConstants($key = null)
    {
        $constants = get_defined_constants(true);
        if (!is_null($key) && array_key_exists($key, $constants)) {
            $constants = $constants[$key];
        }
        return self::makeItems($constants);
    }
}
