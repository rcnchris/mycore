<?php
/**
 * Fichier DbFactory.php du 15/12/2017
 * Description : Fichier de la classe DbFactory
 *
 * PHP version 5
 *
 * @category Base de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM;

/**
 * Class DbFactory
 * <ul>
 * <li>Fournit l'instance d'une base de données</li>
 * <li>Pour l'instant seul PDO est géré</li>
 * </ul>
 *
 * @category Base de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class DbFactory
{
    /**
     * Liste des SGBD gérés par cette classe
     *
     * @var array
     */
    private static $sgbds = [
        'sqlite' => 'pdo_sqlite',
        'mysql' => 'pdo_mysql',
        'sqlsrv' => 'pdo_dblib'
    ];

    /**
     * Driver de connexion
     *
     * @var string
     */
    private static $driver;

    /**
     * Configuration de connexion
     *
     * @var array
     */
    private static $dsnParts = [];

    /**
     * Chaîne de connexion
     *
     * @var string
     */
    private static $dsn;

    /**
     * Obtenir l'instance d'une connexion à un SGBD
     *
     * @param string      $server   Adresse ou nom du serveur de la base de données
     * @param int         $port     Port de communication du serveur de bases de données
     * @param string      $user     Utilisateur de la connexion
     * @param string      $password Mot de passe de la connexion
     * @param string      $database Base de données par défaut
     * @param string|null $sgbd     Type du SGBD (mysql, sqlsrv)
     * @param string|null $file     Nom du fichier de la base de données
     *
     * @return null|\PDO|string
     * @throws \Exception
     */
    public static function get($server, $port, $user, $password, $database, $sgbd = null, $file = null)
    {
        if (is_null($sgbd)) {
            $sgbd = current(array_keys(self::$sgbds));
        }
        if (!array_key_exists($sgbd, self::$sgbds)) {
            throw new \Exception("Le type de SGBD $sgbd n'est pas géré par cette classe !");
        }
        self::$driver = self::$sgbds[$sgbd];
        self::$dsnParts = [
            'driver' => explode('_', self::$driver)[1],
            'host' => $server,
            'port' => intval($port),
            'dbname' => $database,
            'charset' => 'UTF-8',
            'user' => $user,
            'password' => $password,
            'file' => $file
        ];
        return self::getPdo(self::$dsnParts, $user, $password);
    }

    /**
     * Obtenir une instance de PDO
     *
     * @param array  $dsnParts Tableau d'éléments du DSN
     * @param string $user     Utilisateur de connexion
     * @param string $pwd      Mot de passe de connexion
     * @param array  $options  Options de connexions
     *
     * @return null|\PDO|string
     */
    private static function getPdo(array $dsnParts, $user, $pwd, array $options = [])
    {
        $pdo = null;
        self::setDsn($dsnParts);
        try {
            $pdo = new \PDO(self::$dsn, $user, $pwd, $options);
        } catch (\PDOException $e) {
            return $e->getMessage();
        }

        // Paramètres de la connexion instanciée
        // Connexion permanente
        $pdo->setAttribute(\PDO::ATTR_PERSISTENT, true);
        // Mode debug
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // Mode de fetch
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        return $pdo;
    }

    /**
     * Construction de la chaîne de caractères du DSN
     *
     * @param array       $parts Parties du DSN
     *
     * @param string|null $format
     *
     * @return string
     * @throws \Exception
     */
    private static function setDsn(array $parts, $format = 'pilote')
    {
        $formats = ['pilote', 'uri'];
//        if (!in_array($format, $formats)) {
//            throw new \Exception(
//                "Le format du DSN est incorrect ! Essayez-plutôt un de ceux-ci :" . implode(', ', $formats)
//            );
//        }
        // Construction de la chaîne de caractères du DSN au format pilote
        // (existe aussi le format URI 'uri:file:///usr/local/dbconnect')

        $driver = null;
        $sepDriver = ':';
        $charset = null;
        // Cas particuliers des drivers
        if (self::$driver === 'pdo_dblib') {
            $driver = $parts['driver'] . ':version=7.0';
            $sepDriver = ';';
            $charset = $parts['charset'];
        } elseif (self::$driver === 'pdo_sqlite') {
            $driver = $parts['driver'];
            if ($parts['host'] === 'memory') {
                $sepDriver = '::memory:';
            }
        } else {
            $driver = $parts['driver'];
            $sepDriver = ':';
            $charset = str_replace('-', '', $parts['charset']);
        }
        // Cas particulier de l'utilisation d'un fichier de base de données
        if (isset($parts['file']) && !is_null($parts['file'])) {
            self::$dsn = $driver . $sepDriver . $parts['file'];
        } else {
            self::$dsn = $driver . $sepDriver
                . "host=" . $parts['host']
                . ";dbname=" . $parts['dbname']
                . ";charset=" . $charset;
        }
        return self::$dsn;
    }
}
