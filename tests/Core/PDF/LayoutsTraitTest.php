<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\LayoutPdf;

class LayoutsTraitTest extends PdfTestCase
{

    /**
     * @var LayoutPdf
     */
    protected $pdf;

    public function setUp()
    {
        $this->pdf = $this->makePdf('layout');
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Layouts');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\LayoutsPdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testLayouts()
    {
        $this->pdf->codeBloc('echo "ola les gens";');
        $this->assertPdfToFile(
            __FUNCTION__,
            "Utiliser des layouts.",
            '$this->pdf->codeBloc(\'echo "ola les gens";\');'
        );
    }
}
