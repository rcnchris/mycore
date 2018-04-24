<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class Ean13PdfTraitTest extends PdfTestCase
{
    /**
     * @var Ean13Pdf
     */
    protected $pdf;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\Ean13Pdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(Ean13Pdf::class, $withPage);
    }

    public function testEan()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->ean13(10, 20, '123456789012'));
    }

    public function testEanWithDifLongerCode()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->ean13(10, 20, '123'));
    }

    public function testEanWithAddParameter()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->ean13(10, 20, '123456789012', 20, .45));
    }

    public function testUpca()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->upca(10, 20, '123456789012'));
    }
}
