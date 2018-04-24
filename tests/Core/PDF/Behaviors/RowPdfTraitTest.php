<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;


use Rcnchris\Core\PDF\AbstractPDF;
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
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->setColsWidth(20, 20, 50)
        );
    }

    public function testSetColWidthInPourc()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->setColsWidthInPourc(30, 20, 50)
        );
    }

    public function testSetColsAlign()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->setColsAlign('L', 'C', 'R')
        );
    }

    public function testSetColsBorder()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->setColsBorder('B', 'B', 'B')
        );
    }

    public function testSetColsFill()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->setColsFill(false, true, false)
        );
    }

    public function testSetColsDrawColor()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->setColsDrawColors('#CCCCCC', '#000000', '#000000')
        );
    }

    public function testSetColsFillColor()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->setColsFillColors('#CCCCCC', '#000000', '#000000')
        );
    }

    public function testSetColsTextColor()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->setColsTextColors('#CCCCCC', '#000000', '#000000')
        );
    }

    public function testSetColsFont()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->setColsFont('courier', 'helvetica', 'helvetica')
        );
    }

    public function testSetColsSize()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->setColsFontSize(10, 8, 10)
        );
    }

    public function testSetColsHeightline()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->setHeightLine(15)
        );
    }

    public function testSetRowCols()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()
                ->setColsWidth(20, 20, 50)
                ->rowCols('ola', 'les', 'gens')
        );
    }

    public function testGetColsWidths()
    {
        $this->assertEquals(
            30,
            $this->makePdf()
                ->setColsWidth(30, 20, 50)
                ->getColWidth(0)
        );
    }

    public function testGetColsWidthsWithWrongIndice()
    {
        $this->assertFalse(
            $this->makePdf()
                ->setColsWidthInPourc(30, 20, 50)
                ->getColWidth(3)
        );
    }

    public function testGetNbCols()
    {
        $this->assertEquals(
            3,
            $this->makePdf()
                ->setColsWidthInPourc(30, 20, 50)
                ->getNbCols()
        );
    }

    public function testGetColsProperties()
    {
        $this->assertNotEmpty(
            $this->makePdf()
                ->setColsWidthInPourc(30, 20, 50)
                ->getColsProperties()
        );

        $this->assertNotEmpty(
            $this->makePdf()
                ->setColsWidthInPourc(30, 20, 50)
                ->getColsProperties(1)
        );
    }
}
