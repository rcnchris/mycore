<?php
/**
 * Fichier SynologyException.php du 02/01/2018
 * Description : Fichier de la classe SynologyException
 *
 * PHP version 7
 *
 * @category Synology
 *
 * @package  Rcnchris\Core\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis\Synology;

use Locale;
use Exception;

/**
 * Class SynologyException
 * <ul>
 * <li>Gestion des erreurs de l'API Synology</li>
 * </ul>
 *
 * @category Synology
 *
 * @package  Rcnchris\Core\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class SynologyException extends Exception
{
    /**
     * Constructeur
     *
     * @param string     $message
     * @param int        $code
     * @param Exception $previous
     */
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        if ($message === '' && $code != 0) {
            $errorsCodes = require __DIR__ . '/errors-codes.php';
            $lang = substr(Locale::getDefault(), 0, 2);
            if (array_key_exists($code, $errorsCodes)) {
                if (array_key_exists($lang, $errorsCodes[$code])) {
                    $message = "Erreur de l'API Synology : " . $errorsCodes[$code][$lang] . " ($code)";
                } else {
                    $message = $errorsCodes[$code]['en'];
                }
            }
        }
        parent::__construct($message, $code, null);
    }
}
