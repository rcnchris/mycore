<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\PdfDoc;
use Rcnchris\Core\PDF\Writer;

class PdfDocTest extends PdfTestCase
{
    /**
     * @var DocPdf
     */
    protected $pdf;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\DocPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(DocPdf::class, $withPage);
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - FPDF');
        $this->assertInstanceOf(
            PdfDoc::class,
            $this->makePdf(),
            $this->getMessage("La classe obtenue à l'instance est incorrecte")
        );
    }

    public function testGetTotalPages()
    {
        $pdf = $this->makePdf(null, false);
        $this->assertEquals(
            0,
            $pdf->getTotalPages(),
            $this->getMessage("Le nombre total de pages ne correspond pas")
        );

        $pdf->AddPage();
        $this->assertEquals(
            1,
            $pdf->getTotalPages(),
            $this->getMessage("Le nombre total de pages ne correspond pas")
        );
    }

    public function testToString()
    {
        $this->assertInternalType('string', $this->makePdf()->__toString());
    }

    public function testAddPage()
    {
        $pdf = $this->makePdf(null, false);
        $this->assertNull($pdf->AddPage());
        $this->assertEquals(1, $pdf->getTotalPages());
    }

    public function testGetBodySizeWithoutParameter()
    {
        $pdf = $this->makePdf();
        $sizes = $pdf->getBodySize();
        $this->assertInternalType('array', $sizes);
        $this->assertArrayHasKey('width', $sizes);
        $this->assertArrayHasKey('height', $sizes);
    }

    public function testGetBodySizeWithParameter()
    {
        $pdf = $this->makePdf();
        $this->assertInternalType('double', $pdf->getBodySize('width'));
        $this->assertInternalType('double', $pdf->getBodySize('height'));
    }

    public function testGetMargin()
    {
        $pdf = $this->makePdf();
        $margins = $pdf->getMargin();
        $this->assertInternalType('array', $margins);
        $this->assertEquals(['top', 'bottom', 'right', 'left', 'cell'], array_keys($margins));
        $this->assertEquals(10, intval($pdf->getMargin('top')));
    }

    public function testSetMargin()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(PdfDoc::class, $pdf->setMargin('top', 20));
        $this->assertEquals(20, $pdf->getMargin('top'));
        $this->assertInstanceOf(PdfDoc::class, $pdf->setMargin('bottom', 20));
        $this->assertEquals(20, $pdf->getMargin('bottom'));
    }

    public function testSetCursor()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(PdfDoc::class, $pdf->setCursor(10));
        $this->assertEquals(10, $pdf->getCursor('x'));
    }

    public function testGetCursorWithoutParameter()
    {
        $pdf = $this->makePdf();
        $cursor = $pdf->getCursor();
        $this->assertInternalType('array', $cursor);
        $this->assertArrayHasKey('x', $cursor);
        $this->assertArrayHasKey('y', $cursor);
    }

    public function testGetCursorWithParameter()
    {
        $pdf = $this->makePdf();
//        $pdf->AddPage();
        $this->assertInternalType('double', $pdf->getCursor('x'));
        $this->assertInternalType('double', $pdf->getCursor('y'));
    }

    public function testGetOrientation()
    {
        $pdf = $this->makePdf();
        $this->assertEquals('P', $pdf->getOrientation());
    }

    public function testGetToolColor()
    {
        $pdf = $this->makePdf();
        $this->assertInternalType('array', $pdf->getToolColor());
        $this->assertEquals(['draw', 'fill', 'text'], array_keys($pdf->getToolColor()));
        $this->assertEquals('0 G', $pdf->getToolColor('draw'));
        $this->assertEquals('0 g', $pdf->getToolColor('fill'));
        $this->assertEquals('0.000 g', $pdf->getToolColor('text'));
        $this->assertFalse($pdf->getToolColor('fake'));
    }

    public function testSetMetaData()
    {
        $pdf = $this->makePdf();
        $expect = 'Tests unitaires';
        $pdf->setMetadata('Title', $expect);
        $this->assertInternalType('array', $pdf->getMetadata());
        $this->assertEquals($expect, $pdf->getMetadata('Title'));
        $this->assertFalse($pdf->getMetadata('fake'));
    }

    public function testSetMetaDataWithArray()
    {
        $pdf = $this->makePdf();
        $metas = [
            'Title' => 'Tests unitaires',
            'Creator' => 'rcn'
        ];
        $pdf->setMetadata($metas);
        $this->assertInternalType('array', $pdf->getMetadata());
        $this->assertEquals('Tests unitaires', $pdf->getMetadata('Title'));
    }

    public function testToFileWithFileName()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testToFileWithoutPage()
    {
        $pdf = $this->makePdf(null, false);
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testToFileWithoutFileName()
    {
        $pdf = $this->makePdf();
        $fileName = get_class($pdf);
        $fileName = explode('\\', $fileName);
        $fileName = array_pop($fileName);
        $pdf->toFile();
        $fileName .= '.pdf';
        $this->assertTrue(file_exists($fileName));
        $this->addUsedFile($fileName);
    }

    public function testHasFont()
    {
        $pdf = $this->makePdf();
        $this->assertTrue($pdf->hasFont('courier'));
        $this->assertFalse($pdf->hasFont('fake'));
    }

    public function testGetFonts()
    {
        $pdf = $this->makePdf();
        $this->assertInternalType('array', $pdf->getFonts());
        $this->assertContains('courier', $pdf->getFonts());
    }

    public function testGetFontPropertiesWithoutParameter()
    {
        $pdf = $this->makePdf();
        $this->assertInternalType('array', $pdf->getFontProperty());
    }

    public function testGetFontProperties()
    {
        $pdf = $this->makePdf();
        $this->assertEquals('helvetica', $pdf->getFontProperty('family'));
        $this->assertFalse($pdf->getFontProperty('fake'));
    }

    public function testSetFontWithoutParameter()
    {
        $pdf = $this->makePdf();
        $pdf->SetFont();
        $this->assertEquals('helvetica', $pdf->getFontProperty('family'));
        $this->assertEquals('', $pdf->getFontProperty('style'));
        $this->assertEquals(10, $pdf->getFontProperty('size'));
    }

    public function testSetFontWithNativesParameters()
    {
        $pdf = $this->makePdf();
        $pdf->SetFont('courier', 'B', 8);
        $this->assertEquals('courier', $pdf->getFontProperty('family'));
        $this->assertEquals('B', $pdf->getFontProperty('style'));
        $this->assertEquals(8, $pdf->getFontProperty('size'));
    }

    public function testSetFontWithArrayParameter()
    {
        $pdf = $this->makePdf();
        $pdf->SetFont('courier', 'B', 8, [
            'textColor' => '#123456',
            'fillColor' => '#7890AB',
            'drawColor' => '#CDEF01',
            'heightline' => 10,
            'border' => 'B',
            'align' => 'L'
        ]);
        $this->assertEquals('courier', $pdf->getFontProperty('family'));
        $this->assertEquals('B', $pdf->getFontProperty('style'));
        $this->assertEquals(8, $pdf->getFontProperty('size'));
        $this->assertEquals('0.071 0.204 0.337 rg', $pdf->getToolColor('text'));
        $this->assertEquals('0.471 0.565 0.671 rg', $pdf->getToolColor('fill'));
        $this->assertEquals('0.804 0.937 0.004 RG', $pdf->getToolColor('draw'));
    }

    public function testFileToPdf()
    {
        $fileSrc = $this->filesPath . '/textFile.txt';
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $this->makePdf()
            ->fileToPdf($fileSrc)
            ->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testFileToPdfWithoutPage()
    {
        $fileSrc = $this->filesPath . '/textFile.txt';
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $this->makePdf(null, false)
            ->fileToPdf($fileSrc)
            ->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testFileToPdfWithMissingFileSrc()
    {
        $fileSrc = $this->filesPath . '/fake.txt';
        $this->assertFalse($this->makePdf()->fileToPdf($fileSrc));
    }

    public function testFileToPdfWithEmptyFileSrc()
    {
        $fileSrc = $this->filesPath . '/EmptyFile.txt';
        $this->assertFalse($this->makePdf()->fileToPdf($fileSrc));
    }

    public function testSetToolColor()
    {
        $pdf = $this->makePdf();
        $pdf->setToolColor('#123456', 'text');
        $this->assertEquals('0.071 0.204 0.337 rg', $pdf->getToolColor('text'));
    }

    public function testSetToolColorWithWrongToolName()
    {
        $pdf = $this->makePdf();
        $this->expectException(\Exception::class);
        $pdf->setToolColor('#123456', 'fake');
    }

    public function testSetToolColorWithWrongLenHexa()
    {
        $pdf = $this->makePdf();
        $this->expectException(\Exception::class);
        $pdf->setToolColor('#123', 'text');
    }

    public function testSetToolColorWithIntColor()
    {
        $pdf = $this->makePdf();
        $pdf->setToolColor(15, 'text');
        $this->assertEquals('0.059 0.000 0.000 rg', $pdf->getToolColor('text'));
    }

    public function testSetToolColorWithArrayRGB()
    {
        $pdf = $this->makePdf();
        $rgb = ['r' => 15, 'g' => 10, 'b' => 75];
        $pdf->setToolColor($rgb, 'text');
        $this->assertEquals('0.059 0.039 0.294 rg', $pdf->getToolColor('text'));
    }

    public function testGetLinks()
    {
        $pdf = $this->makePdf()->demo();
        $this->assertNotEmpty($pdf->getLinks());
        $this->assertNotEmpty($pdf->getLinks(1));
    }

    public function testSetWriter()
    {
        $this->ekoMessage("Définir le Writer");
        $this->assertInstanceOf(
            PdfDoc::class,
            $this->makePdf()->setWriter()
        );
    }

    public function testGetWriter()
    {
        $this->ekoMessage("Obtenir l'instance du Writer");
        $this->assertInstanceOf(Writer::class, $this->makePdf()->writer());
    }
}
