<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class GridPdfTraitTest extends PdfTestCase
{
    /**
     * @var GridPdf
     */
    protected $pdf;

    /**
     * @param bool|null $withPage
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\GridPdf
     */
    public function makePdf($withPage = true)
    {
        $pdf = new GridPdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
    }

    public function testSetGrid()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->setGrid());
        $this->assertInstanceOf(AbstractPDF::class, $pdf->setGrid(true));
        $this->assertInstanceOf(AbstractPDF::class, $pdf->setGrid(10));
    }

    public function testDrawGridWithWithoutParameterActivated()
    {
        $pdf = $this->makePdf()->setGrid();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->drawGrid());
    }

    public function testDrawGridWithWithBooleanActivated()
    {
        $pdf = $this->makePdf()->setGrid(true);
        $this->assertInstanceOf(AbstractPDF::class, $pdf->drawGrid());
    }

    public function testDrawGridWithWithValueActivated()
    {
        $pdf = $this->makePdf()->setGrid(10);
        $this->assertInstanceOf(AbstractPDF::class, $pdf->drawGrid());
    }
}
