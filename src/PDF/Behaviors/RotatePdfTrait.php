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
 * Class RotatePdfTrait
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
    private $angle = 0;

    /**
     * @param     $angle
     * @param int $x
     * @param int $y
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
                sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx,
                    -$cy)
            );
        }
    }

    /**
     *
     */
    protected function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    /**
     * Rotation du texte autour de son origine
     *
     * ### Exemple
     * - `$pdf->rotatedText(100, 60, 'Ola !', 45);`
     *
     * @param $x
     * @param $y
     * @param $txt
     * @param $angle
     */
    public function rotatedText($x, $y, $txt, $angle)
    {
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    /**
     * Rotation de l'image autour du coin supérieur gauche
     *
     * ### Exemple
     * - `$pdf->rotatedImage(circle.png', 85, 60, 40, 16, 45);`
     *
     * @param $file
     * @param $x
     * @param $y
     * @param $w
     * @param $h
     * @param $angle
     */
    public function rotatedImage($file, $x, $y, $w, $h, $angle)
    {
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }
}
