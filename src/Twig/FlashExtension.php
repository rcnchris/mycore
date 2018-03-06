<?php
/**
 * Fichier FlashExtension.php du 05/03/2018
 * Description : Fichier de la classe FlashExtension
 *
 * PHP version 5
 *
 * @category New
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
 *
 * @category New
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class FlashExtension extends \Twig_Extension
{

    /**
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
     * @return array[\Twig_SimpleFunction]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('flash', [$this, 'getFlash'])
        ];
    }

    /**
     * Retourne le message correspondant au type
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
