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
     * Tableau `$_SERVER` dans une instance de Items
     *
     * @var \Rcnchris\Core\Tools\Items
     */
    private $server;

    /**
     * Instance
     *
     * @var \Locale
     */
    private $locale;

    public function __construct($server = null)
    {
        $this->server = is_null($server) ? new Items($_SERVER) : new Items($server);
        $this->locale = new \Locale();
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
     * Obtenir le nom du serveur
     *
     * @return string
     * @codeCoverageIgnore
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
    public function getPhpVersion($short = false)
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
        return $this->makeItems(Cmd::exec('php -m'));
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
     * Obtenir la localisation courante
     *
     * @return \Locale
     * @see http://php.net/manual/fr/class.locale.php
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Définit la locale par défaut
     *
     * @param string $locale Locale à définir par défaut
     */
    public function setLocale($locale)
    {
        $this->locale->setDefault($locale);
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
     * Obtenir la liste des contantes
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getConstants()
    {
        return $this->makeItems(get_defined_constants(true));
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
    private function makeItems($items)
    {
        return new Items($items);
    }
}
