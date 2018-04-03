<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\EllipsePdf;

class EllipseTraitTest extends PdfTestCase
{
    /**
     * @var EllipsePdf
     */
    protected $pdf;

    /**
     * Instancie le document PDF
     */
    public function setUp()
    {
        $this->pdf = $this->makePdf('ellipse');
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
            'Rcnchris\Core\PDF\Behaviors\EllipsePdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testCircle()
    {
        $this->pdf->AddPage();
        $this->pdf->circle(110, 50, 7, 'F');
        $this->assertPdfToFile(
            __FUNCTION__,
            "Dessiner un cercle de 7mm de rayon.",
            '$this->pdf->circle(110, 50, 7, "F")'
        );
    }

    public function testEllipse()
    {
        $this->pdf->AddPage();
        $this->pdf->ellipse(110, 80, 30, 20);
        $this->assertPdfToFile(
            __FUNCTION__,
            "Dessiner une ellipse de 30mm de rayon horizontal et 20mm de rayon vertical.",
            '$this->pdf->ellipse(110, 80, 30, 20)'
        );
    }

    public function testEllipseWithStyle()
    {
        $this->pdf->AddPage();
        $this->pdf->ellipse(110, 80, 30, 20, 'FD');
        $this->assertPdfToFile(
            __FUNCTION__,
            "Dessiner une ellipse de 30mm de rayon horizontal et 20mm de rayon vertical, avec le style FD.",
            '$this->pdf->ellipse(110, 80, 30, 20, "FD")'
        );
    }
}
