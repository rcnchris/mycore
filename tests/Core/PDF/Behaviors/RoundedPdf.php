<?php
/**
 * Fichier RoundedPdf.php du 23/04/2018
 * Description : Fichier de la classe RoundedPdf
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

use Rcnchris\Core\PDF\Behaviors\RoundedRectPdfTrait;
use Tests\Rcnchris\Core\PDF\DocPdf;

/**
 * Class RoundedPdf
 *
 * @category PDF
 *
 * @package  Tests\Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class RoundedPdf extends DocPdf
{
    use RoundedRectPdfTrait;
}