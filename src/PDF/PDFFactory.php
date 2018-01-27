<?php
/**
 * Fichier PDFFactory.php du 24/01/2018
 * Description : Fichier de la classe PDFFactory
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

/**
 * Class PDFFactory
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class PDFFactory
{

    /**
     * Obtenir une document PDF
     *
     * @param array|null $options
     *
     * @return \Rcnchris\Core\PDF\MyFPDF
     */
    public static function make(array $options = [])
    {
        return new MyFPDF($options);
    }
}
