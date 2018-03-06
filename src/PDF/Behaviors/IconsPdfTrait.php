<?php
/**
 * Fichier IconsPdfTrait.php du 15/02/2018
 * Description : Fichier de la classe IconsPdfTrait
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF\Behaviors;

/**
 * Trait IconsPdfTrait
 * <ul>
 * <li>Imprimer des icônes dans un document PDF</li>
 * </ul>
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF\Behaviors
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
trait IconsPdfTrait
{
    /**
     * Tableau des icônes
     *
     * @var array
     */
    private $icons = [
        'phone' => '',
        'envelop' => '41',
    ];

    /**
     * Obtenir un icône par son nom
     *
     * @param string      $name  Nom de l'icône
     * @param double|null $x     Position de X
     * @param double|null $y     Position de Y
     * @param int|null    $width Taille de la police
     * @param string|null $style Style de la police
     *
     * @return string
     */
    public function printIcon($name, $x = null, $y = null, $width = null, $style = null)
    {
        $initFont = $this->getFontProperty();
        $initPos = $this->getCursor();
        if (!is_null($x)) {
            $this->SetX($x);
        }
        if (!is_null($y)) {
            $this->SetY($y);
        }

        $width = is_null($width) ? $this->getFontProperty('size') : intval($width);
        $style = is_null($style) ? $this->getFontProperty('style') : strtoupper($style);
        $this->SetFont('zapfdingbats', $style, $width);
        $icon = chr($this->icons[$name]);
        $this->Write(10, $icon);

        $this->SetXY($initPos['x'], $initPos['y']);
        $this->SetFont($initFont['family'], $initFont['style'], $initFont['size']);
    }
}
