<?php
/**
 * Fichier Common.php du 28/10/2017
 * Description : Fichier de la classe Common
 *
 * PHP version 5
 *
 * @category Outils
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
 * Class Common
 * <ul>
 * <li>Classe statique qui fournit des méthodes diverses.</li>
 * </ul>
 *
 * @category Outils
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.0.1>
 */
class Common
{
    /**
     * Aide de cette classe
     *
     * @var array
     */
    private static $help = [
        "Méthodes communes",
        "Classe statique et instanciable",
    ];

    /**
     * Instance
     *
     * @var $this
     */
    private static $instance;

    /**
     * Obtenir une instance (Singleton)
     *
     * @return \Rcnchris\Core\Tools\Debug
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retourne une taille en Bits pour une valeur en octets
     *
     * ### Exemple
     * - `$ext->bitsSize(123456)`
     * - `$ext->bitsSize(123456, 2)`
     * - `123456|bitsSize(2)`
     *
     * @param int      $value Valeur en octets
     * @param int|null $round Arrondi
     *
     * @return string
     */
    public static function bitsSize($value, $round = 0)
    {
        $sizes = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];
        for ($i = 0; $value > 1024 && $i < count($sizes) - 1; $i++) {
            $value /= 1024;
        }
        return round($value, $round) . $sizes[$i];
    }

    /**
     * Obtenir un tableau à partir d'une variable.
     *
     * @param object|mixed $var Objet à transformer
     *
     * @return array
     */
    public static function toArray($var)
    {
        if (is_array($var)) {
            return $var;
        }
        $ret = [];
        if (is_object($var)) {
            foreach ($var as $properties => $value) {
                $ret[$properties] = $value;
            }
        }
        return $ret;
    }

    /**
     * Retourne la quantité de mémoire allouée par PHP
     *
     * <code>$m = Common::getMemoryUse();</code>
     *
     * @param bool|null $peak  Mémoire max
     * @param bool|null $octet Retour en octets
     *
     * @return int|string
     */
    public static function getMemoryUse($peak = true, $octet = false)
    {
        $octets = $peak
            ? memory_get_peak_usage(true)
            : memory_get_usage(true);
        return $octet
            ? $octets
            : self::bitsSize($octets, 2);
    }

    /**
     * Obtenir le contenu d'un fichier json sous la forme d'un objet ou tableau
     *
     * @param string    $path    Chemin du fichier json
     * @param bool|null $toArray Un tableau est retourné plutôt que un `stdClass`
     *
     * @return array|bool|\stdClass
     */
    public static function getJsonFileContent($path, $toArray = false)
    {
        if (file_exists($path)) {
            $content = json_decode(file_get_contents($path), true);
            if (is_array($content)) {
                if ($toArray) {
                    return $content;
                }
                $o = new \stdClass();
                foreach ($content as $key => $value) {
                    $o->$key = $value;
                }
                return $o;
            }
        }
        return false;
    }

    /**
     * Obtenir la liste des ports utilisés par les services ou l'un d'entre eux
     *
     * @param string|null $serviceName Nom du service
     * @param string|null $protocol    Nom du protocole (tcp ou udp)
     *
     * @return array|int
     */
    public static function getPortOfServices($serviceName = null, $protocol = 'tcp')
    {
        $services = ['http', 'ftp', 'ssh', 'telnet', 'imap', 'smtp', 'nicname', 'gopher', 'finger', 'pop3', 'www'];
        $protocoles = ['tcp', 'udp'];
        if (!is_null($protocol) && !in_array($protocol, $protocoles)) {
            $protocol = 'tcp';
        }
        if (is_null($serviceName)) {
            $items = [];
            foreach ($services as $service) {
                $items[$service] = getservbyname($service, $protocol);
            }
            return $items;
        } elseif (in_array($serviceName, $services)) {
            return getservbyname($serviceName, $protocol);
        }
        return false;
    }

    /**
     * Obtenir le service Internet qui correspond au port et protocole
     *
     * ### Exemple
     * - `Common::getServiceOfPort(21);`
     * - `Common::getServiceOfPort(80, 'udp');`
     *
     * @param int         $port     Numéro de port
     * @param string|null $protocol Protocole du service (tcp ou udp)
     *
     * @return bool|string
     */
    public static function getServiceOfPort($port = 0, $protocol = 'tcp')
    {
        if (in_array($protocol, ['tcp', 'udp'])) {
            return getservbyport($port, $protocol);
        }
        return false;
    }

    /**
     * Obtenir les parties d'une URL ou l'une d'entre elles
     *
     * - `Common::getUrlParts($url, 'host');`
     *
     * @param string      $url  URL
     * @param string|null $part Partie de l'URL à retourner
     *
     * @return string|bool
     */
    public static function getUrlParts($url, $part = null)
    {
        $parts = parse_url($url);
        if (is_null($part)) {
            return $parts;
        } elseif (array_key_exists($part, $parts)) {
            return $parts[$part];
        }
        return false;
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
}
