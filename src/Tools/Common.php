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
 * Class Common<br/>
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
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <0.0.1>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class Common
{
    /**
     * Instance
     *
     * @var Common
     */
    private static $instance;

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
     * Retourne une taille en Bits pour une valeur donnée
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
     * Obtenir le contenur d'un fichier json sous la forme d'un objet
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
     * Permet d'instancier une classe statique
     *
     * @return \Rcnchris\Core\Tools\Common
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
