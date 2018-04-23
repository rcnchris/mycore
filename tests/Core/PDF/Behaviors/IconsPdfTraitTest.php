<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class IconsPdfTraitTest extends PdfTestCase
{
    /**
     * @var IconsPdf
     */
    protected $pdf;

    /**
     * @param bool $withPage
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\IconsPdf
     */
    public function makePdf($withPage = true)
    {
        $pdf = new IconsPdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
    }

    public function testPrintIcon()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->printIcon('envelope'));
        $this->assertInstanceOf(AbstractPDF::class, $pdf->printIcon('envelope', 10, 20));
    }
}
