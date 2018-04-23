<?php
/**
 * Fichier ColorsPdf.php du 22/04/2018 
 * Description : Fichier de la classe ColorsPdf 
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package Tests\Rcnchris\Core\PDF\Behaviors
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\Behaviors\ColorsPdfTrait;
use Tests\Rcnchris\Core\PDF\DocPdf;

/**
 * Class ColorsPdf
 *
 * @category PDF
 *
 * @package Tests\Rcnchris\Core\PDF\Behaviors
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @version Release: <1.0.0>
 */
class ColorsPdf extends DocPdf
{
    use ColorsPdfTrait;
}