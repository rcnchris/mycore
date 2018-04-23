<?php
/**
 * Fichier DataPdf.php du 22/04/2018 
 * Description : Fichier de la classe DataPdf 
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

use Rcnchris\Core\PDF\Behaviors\DataPdfTrait;
use Tests\Rcnchris\Core\PDF\DocPdf;

/**
 * Class DataPdf
 *
 * @category PDF
 *
 * @package Tests\Rcnchris\Core\PDF\Behaviors
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @version Release: <1.0.0>
 */
class DataPdf extends DocPdf
{
    use DataPdfTrait;
}