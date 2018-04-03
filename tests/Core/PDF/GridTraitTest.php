<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\GridPdf;

class GridTraitTest extends PdfTestCase
{
    /**
     * @var GridPdf
     */
    protected $pdf;

    /**
     * Instancie le document PDF
     */
    public function setUp()
    {
        $this->pdf = $this->makePdf('grid');
    }

    public function testInstance()
    {
        $this->ekoTitre("PDF - {$this->fileName}");
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\GridPdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testGrid()
    {
        $this->pdf->setGrid();
        $this->pdf->AddPage();
        $this->pdf->drawGrid();
        $code = [
            '$this->pdf->setGrid()',
            '$this->pdf->drawGrid()',
        ];
        $this->assertPdfToFile(
            __FUNCTION__,
            "Ajouter une grille graduée à une page.",
            $code
        );
    }

    public function testGrid10()
    {
        $this->pdf->setGrid(10);
        $this->pdf->AddPage();
        $this->pdf->drawGrid();
        $code = [
            '$this->pdf->setGrid()',
            '$this->pdf->drawGrid()',
        ];
        $this->assertPdfToFile(
            __FUNCTION__,
            "Ajouter une grille graduée de 10mm à une page.",
            $code,
            null,
            true
        );
    }

    public function testGridTrue()
    {
        $this->pdf->setGrid(true);
        $this->pdf->AddPage();
        $this->pdf->drawGrid();
        $code = [
            '$this->pdf->setGrid()',
            '$this->pdf->drawGrid()',
        ];
        $this->assertPdfToFile(
            __FUNCTION__,
            "Ajouter une grille graduée de 10mm à une page.",
            $code,
            null,
            true
        );
    }
}
