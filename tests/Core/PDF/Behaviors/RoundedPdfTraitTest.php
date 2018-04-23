<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class RoundedPdfTraitTest extends PdfTestCase
{
    /**
     * @var RoundedPdf
     */
    protected $pdf;

    /**
     * @param bool $withPage
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\RoundedPdf
     */
    public function makePdf($withPage = true)
    {
        $pdf = new RoundedPdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
    }

    public function testRoundedRect()
    {
        $pdf = $this->makePdf();

        $this->assertInstanceOf(
            AbstractPDF::class,
            $pdf->roundedRect(10, 20, 30, 10, 40)
        );

        $this->assertInstanceOf(
            AbstractPDF::class,
            $pdf->roundedRect(10, 20, 30, 10, 40, '1234')
        );

        $this->assertInstanceOf(
            AbstractPDF::class,
            $pdf->roundedRect(10, 20, 30, 10, 40, '1')
        );

        $this->assertInstanceOf(
            AbstractPDF::class,
            $pdf->roundedRect(10, 20, 30, 10, 40, '2')
        );

        $this->assertInstanceOf(
            AbstractPDF::class,
            $pdf->roundedRect(10, 20, 30, 10, 40, '1234', 'F')
        );

        $this->assertInstanceOf(
            AbstractPDF::class,
            $pdf->roundedRect(10, 20, 30, 10, 40, '1234', 'FD')
        );

    }
}