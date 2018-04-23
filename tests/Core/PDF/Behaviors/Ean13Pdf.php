<?php
/**
 * Fichier Ean13Pdf.php du 22/04/2018
 * Description : Fichier de la classe Ean13Pdf
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

use Rcnchris\Core\PDF\Behaviors\Ean13PdfTrait;
use Tests\Rcnchris\Core\PDF\DocPdf;

/**
 * Class Ean13Pdf
 *
 * @category PDF
 *
 * @package  Tests\Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class Ean13Pdf extends DocPdf
{
    use Ean13PdfTrait;
}
