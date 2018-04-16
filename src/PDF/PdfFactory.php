<?php
/**
 * Fichier PdfFactory.php du 06/03/2018
 * Description : Fichier de la classe PdfFactory
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

use Rcnchris\Core\PDF\Behaviors\BookmarkPdfTrait;
use Rcnchris\Core\PDF\Behaviors\ColorsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\DataPdfTrait;
use Rcnchris\Core\PDF\Behaviors\Ean13PdfTrait;
use Rcnchris\Core\PDF\Behaviors\EllipsePdfTrait;
use Rcnchris\Core\PDF\Behaviors\GridPdfTrait;
use Rcnchris\Core\PDF\Behaviors\IconsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\JoinedFilePdfTrait;
use Rcnchris\Core\PDF\Behaviors\LayoutsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\Psr7PdfTrait;
use Rcnchris\Core\PDF\Behaviors\RotatePdfTrait;
use Rcnchris\Core\PDF\Behaviors\RoundedRectPdfTrait;
use Rcnchris\Core\PDF\Behaviors\RowPdfTrait;

/**
 * Class BookmarkPdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class BookmarkPdf extends AbstractPDF
{
    use BookmarkPdfTrait;
}

/**
 * Class ColorsPdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class ColorsPdf extends AbstractPDF
{
    use ColorsPdfTrait;
}

/**
 * Class DataPdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class DataPdf extends AbstractPDF
{
    use DataPdfTrait;
}

/**
 * Class EanPdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class EanPdf extends AbstractPDF
{
    use Ean13PdfTrait;
}

/**
 * Class EllipsePdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class EllipsePdf extends AbstractPDF
{
    use EllipsePdfTrait;
}

/**
 * Class GridPdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class GridPdf extends AbstractPDF
{
    use GridPdfTrait;
}

/**
 * Class IconPdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class IconPdf extends AbstractPDF
{
    use IconsPdfTrait;
}

/**
 * Class JoinPdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class JoinPdf extends AbstractPDF
{
    use JoinedFilePdfTrait;
}

/**
 * Class Psr7Pdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class Psr7Pdf extends AbstractPDF
{
    use Psr7PdfTrait;
}

/**
 * Class RoundPdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class RoundPdf extends AbstractPDF
{
    use RoundedRectPdfTrait;
}

/**
 * Class RotatePdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class RotatePdf extends AbstractPDF
{
    use RotatePdfTrait;
}

/**
 * Class RowPdf
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class RowPdf extends AbstractPDF
{
    use RowPdfTrait;
}

/**
 * Class PdfFactory
 * <ul>
 * <li>Est chargée d'instancier l'objet qui correspond au type demandé</li>
 * </ul>
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class PdfFactory
{

    /**
     * Instance du document PDF
     *
     * @var object
     */
    protected static $pdf;

    /**
     * Obtenir une instance d'un document PDF qui utilise un trait
     *
     * ### Exemple
     * - `PdfFactory::make();`
     * - `PdfFactory::make('grid');`
     *
     * @param string|null $type    Type de document PDF
     * @param array|null  $options Options du document
     *
     * @return object|\Rcnchris\Core\PDF\AbstractPDF
     * @throws \Exception
     */
    public static function make($type = null, array $options = [])
    {
        if (is_null($type)) {
            self::$pdf = new AbstractPDF();
        } else {
            $className = 'Rcnchris\Core\PDF\\' . ucfirst($type) . 'Pdf';
            if (class_exists($className)) {
                self::$pdf = new $className();
            } else {
                throw new \Exception("La classe $className est introuvable !");
            }
        }
        return self::$pdf;
    }
}
