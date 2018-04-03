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
     * Définit l'état du document à 1
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

    /**
     * Imprime les informations du trait
     */
    public function infosRotatePdfTrait()
    {
        $this->AddPage();
        $this->title('Rotation', 1);
        $this->alert("Permet d'appliquer une rotation sur un texte ou une image.");
        $this->printInfoClass(RotatePdfTrait::class);

        $this->title('rotatedText', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Rotation du texte autour de son origine."));
        $this->codeBloc("\$pdf->rotatedText(100, 60, 'Hello !', 45);");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Exemple :");
        $this->rotatedText($this->GetX(), $this->GetY() + 7, 'Hello !', 45);
        $this->Ln();

        $this->title('rotatedImage', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Rotation de l'image autour du coin supérieur gauche."));
        $this->codeBloc("\$pdf->rotatedImage('circle.png', 85, 60, 40, 16, 45);");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Exemple :");
        $this->rotatedImage(dirname(__DIR__) . '/files/circle.png', $this->GetX(), $this->GetY() + 35, 40, 16, 45);
    }
}
