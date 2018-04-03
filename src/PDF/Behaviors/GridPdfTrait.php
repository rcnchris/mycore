<?php
/**
 * Fichier GrillePdfTrait.php du 25/02/2018
 * Description : Fichier de la classe GrillePdfTrait
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
 * Trait GrillePdfTrait
 * <ul>
 * <li>Ajoute une grille pour aider à positionner les élements lors du développement.</li>
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
trait GridPdfTrait
{

    /**
     * Grille activée
     *
     * @var bool
     */
    private $grid = false;

    /**
     * Dessine une grille
     */
    public function drawGrid()
    {
        $spacing = $this->grid === true
            ? 5
            : $this->grid;

        $this->SetDrawColor(204, 255, 255);
        $this->SetLineWidth(0.35);
        for ($i = 0; $i < $this->w; $i += $spacing) {
            $this->Line($i, 0, $i, $this->h);
        }
        for ($i = 0; $i < $this->h; $i += $spacing) {
            $this->Line(0, $i, $this->w, $i);
        }
        $this->SetDrawColor(0, 0, 0);

        $x = $this->GetX();
        $y = $this->GetY();
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(204, 204, 204);
        for ($i = 20; $i < $this->h; $i += 20) {
            $this->SetXY(1, $i - 3);
            $this->Write(4, $i);
        }
        for ($i = 20; $i < (($this->w) - ($this->rMargin) - 10); $i += 20) {
            $this->SetXY($i - 1, 1);
            $this->Write(4, $i);
        }
        $this->SetXY($x, $y);
    }

    /**
     * Active ou désactive le mode grille
     *
     * @param bool|int $activated
     */
    public function setGrid($activated = 5)
    {
        $this->grid = $activated;
    }

    /**
     * Imprime les informations du trait
     */
    public function infosGridPdfTrait()
    {
        $this->AddPage();
        $this->setGrid(10);

        $this->title('Grille', 1);
        $this->alert("Ajoute une grille pour aider à positionner les élements lors du développement.");
        $this->printInfoClass(GridPdfTrait::class);

        $this->title('setGrid', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Définir la taille de l'échelle de la grille (5mm par défaut)."));
        $this->codeBloc("\$pdf->setGrid(10);");

        $this->MultiCell(0, 10, utf8_decode("Arrêter l'impression de la grille."));
        $this->codeBloc("\$pdf->setGrid(false);");

        $this->Ln();

        $this->title('drawGrid', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Dessiner la grille."));
        $this->codeBloc("\$pdf->drawGrid();");

        $this->drawGrid();
        $this->setGrid(false);
    }
}
