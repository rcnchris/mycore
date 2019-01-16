<?php
/**
 * Fichier Text.php du 13/01/2019
 * Description : Fichier de la classe Text
 *
 * PHP version 5
 *
 * @category Texte
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
 * Class Text
 *
 * @category Texte
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
class Text
{

    /**
     * Aide de cette classe
     *
     * @var array
     */
    private static $help = [
        'Facilite la manipulations des chaînes de caractères',
        'Statique et instanciable via <code>getInstance()</code>',
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
     * Obtenir le texte à gauche d'un caractère.
     *
     * @param string      $string  Caractère séparateur
     * @param string      $text    Texte à découper
     * @param string|null $default Valeur par défaut si le caractère n'est pas trouvé
     *
     * @return null|string
     */
    public static function getBefore($string, $text, $default = null)
    {
        $result = strstr($text, $string, true);
        return $result != '' ? $result : $default;
    }

    /**
     * Obtenir le texte à droite d'un caractère.
     *
     * @param string      $string  Caractère séparateur
     * @param string      $text    Texte à découper
     * @param string|null $default Valeur par défaut si le caractère n'est pas trouvé
     *
     * @return null|string
     */
    public static function getAfter($string, $text, $default = null)
    {
        $result = strstr($text, $string);
        return $result != '' ? substr($result, 1, strlen($result) - 1) : $default;
    }

    /**
     * Sérialise une variable
     *
     * @param mixed       $value  Variable à sérialiser
     * @param string|null $format Format de sérialisation
     *
     * @return string|void
     */
    public static function serialize($value, $format = null)
    {
        switch ($format) {
            case 'json':
                return json_encode($value);
            default:
                return serialize($value);
        }
    }

    /**
     * Obtenir l'aide de cette classe
     *
     * @param bool|null $text Si faux, c'est le tableau qui ets retourné
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
