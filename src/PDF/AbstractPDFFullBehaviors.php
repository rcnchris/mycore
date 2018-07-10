<?php
/**
 * Fichier AbstractPDFFullBehaviors.php du 10/07/2018
 * Description : Fichier de la classe AbstractPDFFullBehaviors
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\Behaviors\ColorsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\ComponentsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\DataPdfTrait;
use Rcnchris\Core\PDF\Behaviors\DesignerPdfTrait;
use Rcnchris\Core\PDF\Behaviors\IconsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\Psr7PdfTrait;
use Rcnchris\Core\PDF\Behaviors\RecordSetPdfTrait;
use Rcnchris\Core\PDF\Behaviors\RessourcesPdfTrait;
use Rcnchris\Core\PDF\Behaviors\RotatePdfTrait;
use Rcnchris\Core\PDF\Behaviors\RowPdfTrait;

/**
 * Class AbstractPDFFullBehaviors
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class AbstractPDFFullBehaviors extends AbstractPDF
{
    use ColorsPdfTrait, ComponentsPdfTrait, DataPdfTrait, DesignerPdfTrait, IconsPdfTrait, Psr7PdfTrait, RecordSetPdfTrait, RessourcesPdfTrait, RotatePdfTrait, RowPdfTrait;
}
