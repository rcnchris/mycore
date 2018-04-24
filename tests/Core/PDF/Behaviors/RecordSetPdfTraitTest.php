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
     * Génère le PDF de la classe
     *
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\RecordSetPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(RecordSetPdf::class, $withPage);
    }

    public function testSetRsWithoutWidth()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->setRs()
        );
    }

    public function testSetWithWidthInUnit()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->setRs(['w' => [30, 20, 50]])
        );
    }

    public function testSetWithWidthTooLong()
    {
        $this->expectException(\Exception::class);
        $this->makePdf()->setRs(['w' => [150, 10, 200]]);
    }

    public function testSetWithDiffHeaderNamesNbCols()
    {
        $this->expectException(\Exception::class);
        $this->makePdf()->setRs([
            'w' => [30, 20, 50],
            'headerNames' => ['col1', 'col2', 'col3', 'col4']
        ]);
    }

    public function testSetWithDefaultOptions()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->setRs()
        );
    }

    public function testSetWithOptions()
    {
        $pdf = $this->makePdf()
            ->setRs([
                'wInPourc' => [30, 20, 50],
                'headerNames' => ['col1', 'col2', 'col3'],
                'headerFont' => 'courier',
                'headerFontSize' => 14,
                'headerFontStyle' => 'B',
                'headerAlign' => 'C'
            ]);
        $this->assertInstanceOf(AbstractPDF::class, $pdf);
    }

    public function testGetPropertiesWithoutOptions()
    {
        $pdf = $this->makePdf()->setRs();
        $this->assertNotEmpty($pdf->getRsProperties());
        $this->assertNotEmpty($pdf->getRsProperties('w'));
        $this->assertFalse($pdf->getRsProperties('fake'));
    }

    public function testGetPropertiesByCol()
    {
        $pdf = $this->makePdf()->setRs();
        $this->assertNotEmpty($pdf->getRsPropertiesByCol());
        $this->assertNotEmpty($pdf->getRsPropertiesByCol(0));
        $this->expectException(\Exception::class);
        $pdf->getRsPropertiesByCol(1);
    }

    public function testGetRsX()
    {
        $pdf = $this->makePdf()->setRs(['w' => [30, 20, 50]]);
        $this->assertEquals($pdf->getMargin('l'), $pdf->getRsX(0));
        $this->assertEquals($pdf->getMargin('l') + 30, $pdf->getRsX(1));
    }

    public function testGetRsHeadersNameWithoutOptions()
    {
        $pdf = $this->makePdf()->setRs();
        $this->assertEquals(['col0'], $pdf->getRsHeadersName());
    }

    public function testGetRsHeadersNameWithDefinedNames()
    {
        $names = ['ola', 'les', 'gens'];
        $pdf = $this->makePdf()
            ->setRs([
                'w' => [30, 20, 50],
                'headerNames' => $names
            ]);
        $this->assertEquals($names, $pdf->getRsHeadersName());
    }

    public function testPrintRsHeader()
    {
        $names = ['ola', 'les', 'gens'];
        $pdf = $this->makePdf()
            ->setRs([
                'w' => [30, 20, 50],
                'headerNames' => $names
            ]);
        $this->assertInstanceOf(AbstractPDF::class, $pdf->printRsHeader());
    }

    public function testPrintRsBody()
    {
        $names = ['name', 'year', 'genre'];
        $items = [
            ['name' => 'Mathis', 'year' => 2007, 'genre' => 'male'],
            ['name' => 'Raphaël', 'year' => 2007, 'genre' => 'male'],
            ['name' => 'Clara', 'year' => 2009, 'genre' => 'female'],
        ];
        $pdf = $this->makePdf()
            ->setRs([
                'w' => [30, 20, 50],
                'headerNames' => $names
            ]);
        $this->assertInstanceOf(AbstractPDF::class, $pdf->printRsBody($items));
    }
}
