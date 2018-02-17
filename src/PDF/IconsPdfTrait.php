<?php
/**
 * Fichier IconsPdfTrait.php du 15/02/2018
 * Description : Fichier de la classe IconsPdfTrait
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

trait IconsPdfTrait
{
    /**
     * Tableau des icônes
     *
     * @var array
     */
    private $icons;

    /**
     * Obtenir un icône par son nom
     *
     * @param string      $name  Nom de l'icône
     * @param array|null  $pos
     * @param int|null    $width Taille de la police
     * @param string|null $style Style de la police
     *
     * @return string
     */
    public function printIcon($name, $pos = null, $width = null, $style = null)
    {
        $initFont = $this->getFont(null, true);
        $this->icons = [
            'phone' => '',
            'envelop' => '41',
        ];

        $initPos = $this->getCursor();
        if (isset($pos['x'])) {
            $this->SetX($pos['x']);
        }
        if (isset($pos['y'])) {
            $this->SetY($pos['y']);
        }

        $width = is_null($width) ? $this->FontSizePt : intval($width);
        $style = is_null($style) ? $this->FontStyle : strtoupper($style);
        $this->SetFont('zapfdingbats', $style, $width);
        $icon = chr($this->icons[$name]);

        $this->Write(10, $icon);
        //parent::Cell(parent::GetStringWidth($icon), 10, $icon);
        //parent::MultiCell(parent::GetStringWidth($icon), 10, $icon);

        $this->SetXY($initPos['x'], $initPos['y']);
        $this->SetFont($initFont['family'], $initFont['style'], $initFont['size']);
    }
}
