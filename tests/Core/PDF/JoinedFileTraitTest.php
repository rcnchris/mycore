<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\JoinPdf;

class JoinedFileTraitTest extends PdfTestCase
{

    /**
     * @var JoinPdf
     */
    protected $pdf;

    public function setUp()
    {
        $this->pdf = $this->makePdf('join');
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Fichier joint');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\JoinedFilePdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testJoinedFile()
    {
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'textFile.txt';
        $this->pdf->AddPage();
        $this->pdf->setAttachPane(true);
        $this->pdf->attach($fileName);
        $this->assertPdfToFile(
            __FUNCTION__,
            "Joindre un fichier au document PDF.",
            '$pdf->attach("/path/to/file")'
        );
    }
}
