<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\RowPdf;

class RowTraitTest extends PdfTestCase
{

    /**
     * @var RowPdf
     */
    protected $pdf;

    public function setUp()
    {
        $this->pdf = $this->makePdf('row');
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Rows');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\RowPdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testSetColsWidth()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 30, 45);
        $this->assertEquals(
            50
            , $pdf->getColWidth(0)
            , $this->getMessage("La largeur de la colonne 0 ne correspond pas à celle définit")
        );
        $this->assertEquals(
            30
            , $pdf->getColWidth(1)
            , $this->getMessage("La largeur de la colonne 1 ne correspond pas à celle définit")
        );
        $this->assertEquals(
            45
            , $pdf->getColWidth(2)
            , $this->getMessage("La largeur de la colonne 2 ne correspond pas à celle définit")
        );
        $this->assertFalse($pdf->getColWidth(4));
    }

    public function testSetColsWidthInPourcentage()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidthInPourc(50, 30, 20);
        $this->assertEquals(
            94
            , intval($pdf->getColWidth(0))
            , $this->getMessage("La largeur de la colonne 0 ne correspond pas à celle définit")
        );
        $this->assertEquals(
            56
            , intval($pdf->getColWidth(1))
            , $this->getMessage("La largeur de la colonne 1 ne correspond pas à celle définit")
        );
        $this->assertEquals(
            37
            , intval($pdf->getColWidth(2))
            , $this->getMessage("La largeur de la colonne 2 ne correspond pas à celle définit")
        );
        $this->assertFalse($pdf->getColWidth(4));
    }

    public function testGetColsPropertiesWithoutParameter()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $properties = $pdf->getColsProperties();
        $this->assertCount(3, $properties);
        $this->assertEquals([
            'width', 'align', 'border', 'fill', 'fillColor', 'textColor', 'drawColor', 'font', 'fontSize'
        ], array_keys($properties[0]));
    }

    public function testGetColsPropertiesWithIndiceParameter()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $properties = $pdf->getColsProperties(0);
        $this->assertInternalType('array', $properties);
        $this->assertEquals([
            'width', 'align', 'border', 'fill', 'fillColor', 'textColor', 'drawColor', 'font', 'fontSize'
        ], array_keys($properties));
    }

    public function testSetAlign()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $pdf->setColsAlign('L', 'C', 'R');
        $this->assertEquals('L', $pdf->getColsProperties(0)['align']);
        $this->assertEquals('C', $pdf->getColsProperties(1)['align']);
        $this->assertEquals('R', $pdf->getColsProperties(2)['align']);
    }

    public function testSetBorder()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $pdf->setColsBorder(0, 'B', 'T');
        $this->assertEquals(0, $pdf->getColsProperties(0)['border']);
        $this->assertEquals('B', $pdf->getColsProperties(1)['border']);
        $this->assertEquals('T', $pdf->getColsProperties(2)['border']);
    }

    public function testSetFill()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $pdf->setColsFill(false, true, false);
        $this->assertEquals(false, $pdf->getColsProperties(0)['fill']);
        $this->assertEquals(true, $pdf->getColsProperties(1)['fill']);
        $this->assertEquals(false, $pdf->getColsProperties(2)['fill']);
    }

    public function testSetFillColor()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $pdf->setColsFillColors('gray', 'graylight', 'aloha');
        $this->assertEquals('gray', $pdf->getColsProperties(0)['fillColor']);
        $this->assertEquals('graylight', $pdf->getColsProperties(1)['fillColor']);
        $this->assertEquals('aloha', $pdf->getColsProperties(2)['fillColor']);
    }

    public function testSetTextColor()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $pdf->setColsTextColors('gray', 'graylight', 'aloha');
        $this->assertEquals('gray', $pdf->getColsProperties(0)['textColor']);
        $this->assertEquals('graylight', $pdf->getColsProperties(1)['textColor']);
        $this->assertEquals('aloha', $pdf->getColsProperties(2)['textColor']);
    }

    public function testSetDrawColor()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $pdf->setColsDrawColors(0, '#CCCCCC', 128);
        $this->assertEquals(0, $pdf->getColsProperties(0)['drawColor']);
        $this->assertEquals('#CCCCCC', $pdf->getColsProperties(1)['drawColor']);
        $this->assertEquals(128, $pdf->getColsProperties(2)['drawColor']);
    }

    public function testSetFont()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $pdf->setColsFont('courier', 'helvetica', 'courier');
        $this->assertEquals('courier', $pdf->getColsProperties(0)['font']);
        $this->assertEquals('helvetica', $pdf->getColsProperties(1)['font']);
        $this->assertEquals('courier', $pdf->getColsProperties(2)['font']);
    }

    public function testSetFontSize()
    {
        $pdf = $this->makePdf('row');
        $pdf->setColsWidth(50, 25, 45);
        $pdf->setColsFontSize(15, 8, 10);
        $this->assertEquals(15, $pdf->getColsProperties(0)['fontSize']);
        $this->assertEquals(8, $pdf->getColsProperties(1)['fontSize']);
        $this->assertEquals(10, $pdf->getColsProperties(2)['fontSize']);
    }

    public function testToFileWithCol()
    {
        $pdf = $this->makePdf('row');
        $pdf->AddPage();
        $this->assertPdfToFile(
            __FUNCTION__,
            "Définir trois colonnes.",
            '$pdf->setColsWidth(50, 25, 45)',
            $pdf,
            true
        );
    }
}
