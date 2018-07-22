<?php
/**
 * Fichier FlashExtension.php du 05/03/2018
 * Description : Fichier de la classe FlashExtension
 *
 * PHP version 5
 *
 * @category Twig
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Twig;

use Rcnchris\Core\Session\FlashService;

/**
 * Class FlashExtension
 * <ul>
 * <li>Helper messages Flash</li>
 * </ul>
 *
 * @category Twig
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.1.0>
 */
class FlashExtension extends \Twig_Extension
{

    /**
     * Instance du service
     *
     * @var \Rcnchris\Core\Session\FlashService
     */
    private $flash;

    /**
     * Constructeur
     *
     * @param \Rcnchris\Core\Session\FlashService $flash
     */
    public function __construct(FlashService $flash)
    {
        $this->flash = $flash;
    }

    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('flash', [$this, 'getFlash'])
        ];
    }

    /**
     * Retourne le message correspondant au type
     * - Fonction
     *
     * @param string $type Type de message à retourner
     *
     * @return null|string
     */
    public function getFlash($type)
    {
        return $this->flash->get($type);
    }
}
