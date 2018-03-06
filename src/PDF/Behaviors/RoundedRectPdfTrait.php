<?php
/**
 * Fichier RoundedRect.php du 24/02/2018
 * Description : Fichier de la classe RoundedRect
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 */

namespace Rcnchris\Core\PDF\Behaviors;

/**
 * Trait RoundedRectPdfTrait
 * <ul>
 * <li>Permet de tracer un rectangle avec les bords arrondis</li>
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
trait RoundedRectPdfTrait
{

    /**
     * Permet de tracer un rectangle avec certains bords arrondis (tous ou au choix)
     *
     * ### Exemple
     * - `$pdf->roundedRect(60, 30, 68, 46, 5, '13', 'DF');`
     *
     * @param double $x       Coin supérieur gauche du rectangle
     * @param double $y       Coin supérieur gauche du rectangle
     * @param double $w       Largeur
     * @param double $h       Hauteur
     * @param double $r       Rayon des coins
     * @param string $corners Numéro du ou des angles à arrondir : 1, 2, 3, 4 ou toute combinaison (1=haut gauche,
     *                        2=haut droite, 3=bas droite, 4=bas gauche)
     * @param string $style   Comme celui de Rect() :
     *                        <ul>
     *                        <li>D ou chaîne vide : contour (draw). C'est la valeur par défaut.</li>
     *                        <li>F : remplissage (fill)</li>
     *                        <li>DF ou FD : contour et remplissage</li>
     *                        </ul>
     */
    public function roundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' || $style == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        if (strpos($corners, '2') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $y) * $k));
        } else {
            $this->arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
        }

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        if (strpos($corners, '3') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
        } else {
            $this->arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
        }

        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        if (strpos($corners, '4') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - ($y + $h)) * $k));
        } else {
            $this->arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
        }

        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
        if (strpos($corners, '1') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $y) * $k));
            $this->_out(sprintf('%.2F %.2F l', ($x + $r) * $k, ($hp - $y) * $k));
        } else {
            $this->arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        }
        $this->_out($op);
    }

    /**
     * Adapte l'échelle de conversion entre les points et les unités
     *
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param $x3
     * @param $y3
     */
    private function arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(
            sprintf(
                '%.2F %.2F %.2F %.2F %.2F %.2F c ',
                $x1 * $this->k,
                ($h - $y1) * $this->k,
                $x2 * $this->k,
                ($h - $y2) * $this->k,
                $x3 * $this->k,
                ($h - $y3) * $this->k
            )
        );
    }
}
