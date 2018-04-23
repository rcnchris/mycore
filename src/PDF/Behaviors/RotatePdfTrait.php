<?php
/**
 * Fichier RotatePdfTrait.php du 24/02/2018
 * Description : Fichier de la classe RotatePdfTrait
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

namespace Rcnchris\Core\PDF\Behaviors;

/**
 * Trait RotatePdfTrait
 * <ul>
 * <li>Permet d'effectuer une rotation à du texte ou une image.</li>
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
trait RotatePdfTrait
{
    /**
     * Angle de l'arc
     *
     * @var int
     */
    private $angle = 0;

    /**
     * Effectue une rotation
     *
     * @param int $angle Angle de l'arc
     * @param int $x     Abscisse du centre de rotation
     * @param int $y     Ordonnée du centre de rotation
     */
    private function rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) {
            $x = $this->x;
        }
        if ($y == -1) {
            $y = $this->y;
        }
        if ($this->angle != 0) {
            $this->_out('Q');
        }
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(
                sprintf(
                    'q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',
                    $c,
                    $s,
                    -$s,
                    $c,
                    $cx,
                    $cy,
                    -$cx,
                    -$cy
                )
            );
        }
    }

    /**
     * Rotation du texte autour de son origine
     *
     * ### Exemple
     * - `$pdf->rotatedText(100, 60, 'Ola !', 45);`
     *
     * @param double $x     Abscisse du centre de rotation
     * @param double $y     Ordonnée du centre de rotation
     * @param string $txt   Texte à faire pivoter
     * @param int    $angle Angle de rotation
     *
     * @return $this
     */
    public function rotatedText($x, $y, $txt, $angle)
    {
        $this->rotate($angle, $x, $y);
        parent::Text($x, $y, $txt);
        //$this->rotate(0);  commenté pour le test de l'angle différent de zéro dans _endpage()
        return $this;
    }

    /**
     * Rotation de l'image autour du coin supérieur gauche
     *
     * ### Exemple
     * - `$pdf->rotatedImage('path/to/file/circle.png', 85, 60, 40, 16, 45);`
     *
     * @param string $file  Nom du fichier de l'image
     * @param double $x     Abscisse du centre de rotation
     * @param double $y     Ordonnée du centre de rotation
     * @param double $w     Largeur
     * @param double $h     Hauteur
     * @param int    $angle Angle de rotation
     *
     * @return $this
     */
    public function rotatedImage($file, $x, $y, $w, $h, $angle)
    {
        $this->rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->rotate(0);
        return $this;
    }

    /**
     * Définit l'état du document à 1
     */
    protected function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            parent::_out('Q');
        }
        parent::_endpage();
    }
}
