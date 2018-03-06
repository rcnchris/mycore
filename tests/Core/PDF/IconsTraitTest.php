<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\IconPdf;

class IconsTraitTest extends PdfTestCase
{

    /**
     * @var IconPdf
     */
    protected $pdf;

    public function setUp()
    {
        $this->pdf = $this->makePdf('icon');
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Icons');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\IconsPdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testIcon()
    {
        $this->pdf->printIcon('envelop');
        $this->pdf->printIcon('envelop', 15, 25);
        $this->pdf->printIcon('envelop', 25, 15, 'B');

        $this->assertPdfToFile(
            __FUNCTION__,
            "Imprimer une enveloppe.",
            '$this->pdf->printIcon("envelop", 25, 15, "B");'
        );
    }
}
