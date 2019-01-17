<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\PdfDoc;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class RowPdfTraitTest extends PdfTestCase
{
    /**
     * @var RowPdf
     */
    protected $pdf;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\RowPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(RowPdf::class, $withPage);
    }

    public function testSetColWidth()
    {
        $pdf = $this->makePdf()->setColsWidth(20, 20, 50);
        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testSetColWidthInPourc()
    {
        $pdf = $this->makePdf()->setColsWidthInPourc(30, 20, 50);
        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testSetColsAlign()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->setColsAlign('L', 'C', 'R');

        $this->assertInstanceOf(PdfDoc::class, $pdf);

        $pdf->Close();
    }

    public function testSetColsBorder()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->setColsBorder('B', 'B', 'B');

        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testSetColsFill()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->setColsFill(false, true, false);

        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testSetColsDrawColor()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->setColsDrawColors('#CCCCCC', '#000000', '#000000');

        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testSetColsFillColor()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->setColsFillColors('#CCCCCC', '#000000', '#000000');
        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testSetColsTextColor()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->setColsTextColors('#CCCCCC', '#000000', '#000000');

        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testSetColsFont()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->setColsFont('courier', 'helvetica', 'helvetica');

        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testSetColsSize()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->setColsFontSize(10, 8, 10);

        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testSetColsHeightline()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->setHeightLine(15);

        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testRowCols()
    {
        $pdf = $this->makePdf()
            ->setColsWidth(20, 20, 50)
            ->rowCols('ola', 'les', 'gens');

        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testRowColsWithPageBreak()
    {
        $pdf = $this->makePdf()->setColsWidth(20, 20, 50);
        for ($i = 0; $i <= 99; $i++) {
            $pdf->rowCols("ola$i", "les$i", "gens$i");
        }
        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testRowColsWithEmptyContent()
    {
        $pdf = $this->makePdf()->setColsWidth(20, 20, 50, 10);
        for ($i = 0; $i <= 99; $i++) {
            $pdf->rowCols("ola$i", "les$i", "gens$i", '');
        }
        $this->assertInstanceOf(PdfDoc::class, $pdf);
        $pdf->Close();
    }

    public function testGetColsWidths()
    {
        $pdf = $this->makePdf()->setColsWidth(30, 20, 50);
        $this->assertEquals(30, $pdf->getColWidth(0));
        $pdf->Close();
    }

    public function testGetColsWidthsWithWrongIndice()
    {
        $pdf = $this->makePdf()->setColsWidthInPourc(30, 20, 50);
        $this->assertFalse($pdf->getColWidth(3));
        $pdf->Close();
    }

    public function testGetNbCols()
    {
        $pdf = $this->makePdf()->setColsWidthInPourc(30, 20, 50);
        $this->assertEquals(3, $pdf->getNbCols());
        $pdf->Close();
    }

    public function testGetColsProperties()
    {
        $pdf = $this->makePdf()->setColsWidthInPourc(30, 20, 50);
        $this->assertNotEmpty($pdf->getColsProperties());
        $this->assertNotEmpty($pdf->getColsProperties(1));
        $pdf->Close();
    }
}
