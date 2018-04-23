<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class EllipsePdfTraitTest extends PdfTestCase
{
    /**
     * @var EllipsePdf
     */
    protected $pdf;

    /**
     * @param bool|null $withPage
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\EllipsePdf
     */
    public function makePdf($withPage = true)
    {
        $pdf = new EllipsePdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
    }

    public function testEllipse()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->ellipse(10, 20, 5, 15));
        $this->assertInstanceOf(AbstractPDF::class, $pdf->ellipse(10, 20, 5, 15, 'F'));
        $this->assertInstanceOf(AbstractPDF::class, $pdf->ellipse(10, 20, 5, 15, 'FD'));
    }

    public function testCircle()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->circle(10, 20, 5, 15));
    }
}