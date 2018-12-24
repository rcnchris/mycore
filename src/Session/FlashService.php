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

use Rcnchris\Core\Tools\Items;

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
     * @var array
     */
    private $messages = [];

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
     * @param string $type
     * @param string $msg
     *
     * @return $this
     */
    public function add($type, $msg)
    {
        $this->messages[$type][] = $msg;
        $this->session->set($this->sessionKey, $this->getMessages());
        return $this;
    }

    /**
     * Vérifie la présence d'un type de message
     *
     * @param string $type Nom du type de message (error, success, warning...)
     *
     * @return bool
     */
    public function has($type)
    {
        return $this->getMessages()->has($type);
    }

    /**
     * Obtenir la liste des messages pour un type de message
     *
     * @param string $type Type de message (success, info, warning, danger...)
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Items|null
     */
    public function get($type)
    {
        $messages = $this->getMessages()->get($type);
        if (!$messages->isEmpty()) {
            $this->getSession()->get($this->sessionKey)->offsetUnset($type);
            if ($messages->count() === 1) {
                return $messages->first();
            }
            return $messages;
        }
        return null;
    }

    /**
     * Obtenir tous les messages
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getMessages()
    {
        return new Items($this->messages);
    }

    /**
     * Obtenir la session
     *
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->session;
    }
}
