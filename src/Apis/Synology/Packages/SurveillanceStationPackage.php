<?php
/**
 * Fichier SurveillanceStationPackage.php du 20/08/2018
 * Description : Fichier de la classe SurveillanceStationPackage
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis\Synology\Packages
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\SynologyAPI;
use Rcnchris\Core\Apis\Synology\SynologyAPIPackage;

/**
 * Class SurveillanceStationPackage
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis\Synology\Packages
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class SurveillanceStationPackage extends SynologyAPIPackage
{
    /**
     * Constructeur
     *
     * @param \Rcnchris\Core\Apis\Synology\SynologyAPI $syno
     *
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function __construct(SynologyAPI $syno)
    {
        parent::__construct('SurveillanceStation', $syno);
    }

    public function cameras()
    {

    }
}
