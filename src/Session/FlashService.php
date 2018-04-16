<?php
/**
 * Fichier FlashService.php du 05/03/2018
 * Description : Fichier de la classe FlashService
 *
 * PHP version 5
 *
 * @category Service
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
 * Class FlashService
 * <ul>
 * <li>Gestion des messages flash</li>
 * </ul>
 *
 * @category Service
 *
 * @package  Rcnchris\Core\Session
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class FlashService
{

    /**
     * Instance de la session
     *
     * @var \Rcnchris\Core\Session\SessionInterface
     */
    private $session;

    /**
     * Nom de la clé des messages flash en session
     *
     * @var string
     */
    private $sessionKey = 'flash';

    /**
     * Tableau des messages stockés
     *
     * @var array[string]
     */
    private $messages = null;

    /**
     * Constructeur
     *
     * @param \Rcnchris\Core\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Définir un message de succès
     *
     * @param string $message Contenu du message
     *
     * @return void
     */
    public function success($message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['success'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * Définir un message d'erreur
     *
     * @param string $message Contenu du message
     *
     * @return void
     */
    public function error($message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['error'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * Définir un message
     *
     * @param string $type    Type de message
     * @param string $message Contenu du message
     */
    public function set($type, $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash[$type] = $message;
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * Obtenir un type de message flash
     *
     * @param string $type Type de message (success, info, warning, danger...)
     *
     * @return string|null
     */
    public function get($type)
    {
        if (is_null($this->messages)) {
            $this->messages = $this->session->get($this->sessionKey, []);
            $this->session->delete($this->sessionKey);
        }
        if (array_key_exists($type, $this->messages)) {
            return $this->messages[$type];
        }
        return null;
    }
}