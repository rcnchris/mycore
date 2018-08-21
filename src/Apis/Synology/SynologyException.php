<?php
/**
 * Fichier SynologyException.php du 02/01/2018
 * Description : Fichier de la classe SynologyException
 *
 * PHP version 7
 *
 * @category API
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
use Rcnchris\Core\Tools\Items;

/**
 * Class SynologyException
 * <ul>
 * <li>Gestion des erreurs de l'API Synology</li>
 * </ul>
 *
 * @property \Rcnchris\Core\Tools\Items errorsCodes
 *
 * @category API
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
     * @param string|object|null $message  Message d'erreur ou object selon besoin
     * @param int|null           $code     Code de l'erreur
     * @param Exception|null     $previous Exception précédente
     */
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        $this->errorsCodes = new Items(require __DIR__ . '/errors-codes.php');
        $packageName = null;

        if (is_object($message)) {
            if ($message instanceof SynologyAPIPackage) {
                $package = $message;
                $errors = $package->getApi()->errors;
                if ($errors) {
                    // print_r(debug_backtrace());
                    // r($errors->toArray());
                }
                $message = $package->getName() . ' : ' . $this->getSynologyMessage($code, $package);
            }
        } elseif (is_string($message) && $message != '') {
            $initMessage = $message;
            $message = $initMessage . "\n" . $message;
        } elseif ($code !== 0) {
            $message = $this->getSynologyMessage($code);
        }
        parent::__construct($message, $code, null);
    }

    /**
     * Obtenir le message Synology s'il existe
     *
     * @param int                                             $code
     * @param \Rcnchris\Core\Apis\Synology\SynologyAPIPackage $package
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    private function getSynologyMessage($code = 0, SynologyAPIPackage $package = null)
    {
        $lang = substr(Locale::getDefault(), 0, 2);

        if (!is_null($package)) {
            $packageName = $package->getName();
            if ($this->errorsCodes->has($packageName) && $this->errorsCodes->get($packageName)->has($code)) {

                return $this->errorsCodes->get($packageName)->get($code)->has($lang)
                    ? '[' . $code . '] ' . $this->errorsCodes->get($packageName)->get($code)->get($lang)
                    : '[' . $code . '] ' . $this->errorsCodes->get($packageName)->get($code)->get('en');
            } else {
                if ($this->errorsCodes->has($code)) {
                    return $this->errorsCodes->get($code)->has($lang)
                        ? '[' . $code . '] ' . $this->errorsCodes->get($code)->get($lang)
                        : '[' . $code . '] ' . $this->errorsCodes->get($code)->get('en');
                }
            }
        } else {

            if ($this->errorsCodes->has($code)) {
                return $this->errorsCodes->get($code)->has($lang)
                    ? '[' . $code . '] ' . $this->errorsCodes->get($code)->get($lang)
                    : '[' . $code . '] ' . $this->errorsCodes->get($code)->get('en');
            }
        }
        return null;
    }
}
