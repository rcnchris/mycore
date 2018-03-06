<?php
namespace Tests\Rcnchris\Core\PDF;

use Psr\Http\Message\ResponseInterface;
use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\Psr7Pdf;
use Slim\Http\Response;

class Psr7TraitTest extends PdfTestCase
{

    /**
     * @var Psr7Pdf
     */
    protected $pdf;

    public function setUp()
    {
        $this->pdf = $this->makePdf('psr7');
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - PSR7');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\Psr7PdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testToView()
    {
        $pdf = $this->makePdf('psr7');
        $this->assertInstanceOf(
            ResponseInterface::class
            , $pdf->toView(new Response())
            , $this->getMessage("La visualisation du PDF retourne une réponse incorrecte")
        );
    }

    public function testToDownload()
    {
        $pdf = $this->makePdf('psr7');
        $this->assertInstanceOf(
            ResponseInterface::class
            , $pdf->toDownload(new Response())
            , $this->getMessage("Le téléchargement du PDF retourne une réponse incorrecte")
        );
    }
}
