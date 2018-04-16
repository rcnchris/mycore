<?php
/**
 * Fichier EllipsePdfTrait.php du 24/02/2018
 * Description : Fichier de la classe EllipsePdfTrait
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
 * Trait EllipsePdfTrait
 * <ul>
 * <li>Permet de dessiner des cercles et ellipses.</li>
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
trait EllipsePdfTrait
{
    /**
     * Dessiner un cercle
     *
     * @param double $x     Abscisse du centre
     * @param double $y     Ordonnée du centre
     * @param double $r     Rayon du cercle
     * @param string $style Style de dessin, comme pour Rect (D, F ou FD)
     */
    public function circle($x, $y, $r, $style = 'D')
    {
        $this->ellipse($x, $y, $r, $r, $style);
    }

    /**
     * Dessiner une ellipse
     *
     * @param double $x     Abscisse du centre
     * @param double $y     Ordonnée du centre
     * @param double $rx    Rayon horizontal
     * @param double $ry    Rayon vertical
     * @param string $style Style de dessin, comme pour Rect (D, F ou FD)
     */
    public function ellipse($x, $y, $rx, $ry, $style = 'D')
    {
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' || $style == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;
        $k = $this->k;
        $h = $this->h;
        $this->_out(sprintf(
            '%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x + $rx) * $k,
            ($h - $y) * $k,
            ($x + $rx) * $k,
            ($h - ($y - $ly)) * $k,
            ($x + $lx) * $k,
            ($h - ($y - $ry)) * $k,
            $x * $k,
            ($h - ($y - $ry)) * $k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $lx) * $k,
            ($h - ($y - $ry)) * $k,
            ($x - $rx) * $k,
            ($h - ($y - $ly)) * $k,
            ($x - $rx) * $k,
            ($h - $y) * $k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $rx) * $k,
            ($h - ($y + $ly)) * $k,
            ($x - $lx) * $k,
            ($h - ($y + $ry)) * $k,
            $x * $k,
            ($h - ($y + $ry)) * $k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x + $lx) * $k,
            ($h - ($y + $ry)) * $k,
            ($x + $rx) * $k,
            ($h - ($y + $ly)) * $k,
            ($x + $rx) * $k,
            ($h - $y) * $k,
            $op
        ));
    }

    /**
     * Imprime les informations du trait
     */
    public function infosEllipsePdfTrait()
    {
        $this->AddPage();
        $this->title('Ellipse', 1);
        $this->SetFont(null, 'I', 10, ['color' => 'black', 'fillColor' => 'graylight']);
        $this->alert("Permet de tracer cercles et ellipses sans images.");
        $this->printInfoClass(EllipsePdfTrait::class);

        $this->title('circle', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode('Dessiner une cercle.'));
        $this->codeBloc("\$pdf->circle(100, 25, 7, 'F');");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Exemple :");
        $this->circle($this->lMargin + 7, $this->GetY() + 7, 7, 'F');
        $this->Ln();
        $this->Ln();

        $this->title('ellipse', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode('Dessiner une ellipse.'));
        $this->codeBloc("\$pdf->ellipse(100, 50, 7, 7, 10);");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Exemple :");
        $this->ellipse($this->lMargin + 7, $this->GetY() + 7, 7, 10);
    }
}