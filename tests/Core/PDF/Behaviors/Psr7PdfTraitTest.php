<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class Psr7PdfTraitTest extends PdfTestCase
{
    /**
     * @var Psr7Pdf
     */
    protected $pdf;

    /**
     * @param bool $withPage
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\Psr7Pdf
     */
    public function makePdf($withPage = true)
    {
        $pdf = new Psr7Pdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
    }

    public function testToView()
    {
        $this->assertInstanceOf(
            ResponseInterface::class,
            $this->makePdf()->demo()->toView(new Response()),
            $this->getMessage("La visualisation du PDF ne retourne pas une ResponseInterface")
        );
    }

    public function testToDownload()
    {
        $this->assertInstanceOf(
            ResponseInterface::class,
            $this->makePdf()->demo()->toDownload(new Response()),
            $this->getMessage("Le téléchargement du PDF ne retourne pas une ResponseInterface")
        );
    }
}