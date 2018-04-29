<?php
/**
 * Fichier RessourcesPdf.php du 26/04/2018
 * Description : Fichier de la classe RessourcesPdf
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Tests\Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\Behaviors\RessourcesPdfTrait;
use Tests\Rcnchris\Core\PDF\DocPdf;

/**
 * Class RessourcesPdf
 *
 * @category PDF
 *
 * @package  Tests\Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class RessourcesPdf extends DocPdf
{
    use RessourcesPdfTrait;
}
