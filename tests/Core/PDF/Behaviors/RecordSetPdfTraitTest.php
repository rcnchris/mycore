<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class RecordSetPdfTraitTest extends PdfTestCase
{
    /**
     * @var RecordSetPdf
     */
    protected $pdf;

    /**
     * Options par défaut pour les tests
     *
     * @var array
     */
    private $fullOptions = [
        //'w' => [],
        'wInPourc' => [30, 20, 50],
        'h' => 5,
        'headerNames' => [],
        'headerFont' => null,
        'headerFontSize' => null,
        'headerFontStyle' => null,
        'headerFill' => false,
        'headerBorder' => null,
        'headerAlign' => null,
        'itemProperties' => [
            'fontFamily' => 'helvetica',
            'fontStyle' => '',
            'fontSize' => 8,
            'align' => 'L',
            'fill' => false,
            'fontColor' => '#000000',
        ]
    ];

    /**
     * @param bool $withPage
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\RecordSetPdf
     */
    public function makePdf($withPage = true)
    {
        $pdf = new RecordSetPdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
    }

    public function testSetRsWithoutWidth()
    {
        $this->expectException(\Exception::class);
        $this->makePdf()->setRs();
    }

    public function testSetsWithWidthInUnit()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->setRs(['w' => [30, 20, 50]])
        );
    }

    public function testSetsWithWidthTooLong()
    {
        $this->expectException(\Exception::class);
        $this->makePdf()->setRs(['w' => [150, 10, 200]]);
    }

    public function testSetsWithDiffHeaderNamesNbCols()
    {
        $this->expectException(\Exception::class);
        $this->makePdf()->setRs([
            'w' => [30, 20, 50],
            'headerNames' => ['col1', 'col2', 'col3', 'col4']
        ]);
    }

    public function testSetsWithWidthInPourc()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->setRs(['wInPourc' => [30, 20, 50]])
        );
    }
}