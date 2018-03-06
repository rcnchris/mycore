<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\RoundPdf;

class RoundedRecTraitTest extends PdfTestCase
{

    /**
     * @var RoundPdf
     */
    protected $pdf;

    public function setUp()
    {
        $this->pdf = $this->makePdf('round');
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Rectangles arrondis');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\RoundedRectPdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testRoundedRectWithNoRounded()
    {
        $this->pdf->roundedRect(60, 60, 68, 46, 5, null, "DF");
        $this->assertPdfToFile(
            __FUNCTION__,
            "Dessiner des rectangles aux coins arrondis.",
            '$this->pdf->roundedRect(60, 60, 68, 46, 5, null, "DF");',
            null,
            true
        );
    }

    public function testRoundedRect()
    {
        $this->pdf->roundedRect(60, 60, 68, 46, 5, "13", "F");
        $this->assertPdfToFile(
            __FUNCTION__,
            "Dessiner des rectangles aux coins arrondis avec style F.",
            '$this->pdf->roundedRect(60, 60, 68, 46, 5, "13", "DF");',
            null
        );
    }

    public function testRoundedRectWithCorners()
    {
        $this->pdf->roundedRect(60, 60, 68, 46, 5, "24", "F");
        $this->assertPdfToFile(
            __FUNCTION__,
            "Dessiner des rectangles aux coins arrondis avec style F.",
            '$this->pdf->roundedRect(60, 60, 68, 46, 5, "24", "DF");',
            null,
            true
        );
    }
}
