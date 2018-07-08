<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class Psr7PdfTraitTest extends PdfTestCase
{
    /**
     * @var Psr7Pdf
     */
    protected $pdf;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\Psr7Pdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(Psr7Pdf::class, $withPage);
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