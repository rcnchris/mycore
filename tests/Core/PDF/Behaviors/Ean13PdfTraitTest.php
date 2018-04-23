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
     * @param bool|null $withPage
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\Ean13Pdf
     */
    public function makePdf($withPage = true)
    {
        $pdf = new Ean13Pdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
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
