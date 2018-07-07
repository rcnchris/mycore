<?php
/**
 * Fichier Mail.php du 02/07/2018
 * Description : Fichier de la classe Mail
 *
 * PHP version 5
 *
 * @category Mail
 *
 * @package  Rcnchris\Core\Mail
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Mail;

/**
 * Class Mail
 *
 * @category Mail
 *
 * @package  Rcnchris\Core\Mail
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class Mail
{

    /**
     * Envoie un mail
     *
     * @param string      $to            Le ou les destinataires du mail
     * @param string      $subject       Sujet du mail à envoyer.
     * @param string      $message       Message à envoyer
     * @param mixed|null  $addHeaders    String ou array à insérer à la fin des en-têtes du mail
     * @param string|null $addParameters Options
     *
     * @return bool|void
     * @see http://php.net/manual/fr/function.mail.php
     */
    public static function send($to, $subject, $message, $addHeaders = null, $addParameters = null)
    {
        return mail($to, $subject, $addHeaders, $addParameters);
    }
}
