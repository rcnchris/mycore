<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\BaseTestCase;

class AbstractPDFTest extends BaseTestCase
{

    /**
     * @param array $options
     *
     * @return \Rcnchris\Core\PDF\AbstractPDF
     */
    private function makePdf($options = [])
    {
        return new AbstractPDF($options);
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Abstraction FPDF');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->makePdf()
            , $this->getMessage("La classe obtenue à l'instance est incorrecte")
        );
    }

    public function testGetTotalPages()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $this->assertEquals(
            1
            , $pdf->getTotalPages()
            , $this->getMessage("Le nombre total ne correspond pas")
        );
    }

    public function testGetFonts()
    {
        $this->assertContains(
            'courier'
            , $this->makePdf()->getFonts()
            , $this->getMessage("La police 'courier' est absente de la la liste des polices disponibles")
        );
    }

    public function testGetBodySize()
    {
        // Document A4 portrait
        $pdf = $this->makePdf();
        $this->assertEquals(
            ['width', 'height']
            , array_keys($pdf->getBodySize())
            , $this->getMessage("Les clés retournées sont incorrectes")
        );
    }

    public function testSizeA4_P()
    {
        // Document A4 portrait
        $pdf = $this->makePdf();
        $this->assertEquals(
            189
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document est incorrecte")
        );
        $this->assertEquals(
            266
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document est incorrecte")
        );
    }

    public function testSizeA4_L()
    {
        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'A4']);
        $this->assertEquals(
            276
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document A4 en paysage est incorrecte")
        );
        $this->assertEquals(
            179
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document A4 en paysage est incorrecte")
        );
    }

    public function testSizeA3_P()
    {
        // Document A3 paysage
        $pdf = $this->makePdf(['orientation' => 'P', 'unit' => 'mm', 'format' => 'A3']);
        $this->assertEquals(
            276
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document est incorrecte")
        );
        $this->assertEquals(
            389
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document est incorrecte")
        );
    }

    public function testSizeA3_L()
    {
        // Document A3 paysage
        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'A3']);
        $this->assertEquals(
            399
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document avec l'orientation paysage au format A3 est incorrecte")
        );
        $this->assertEquals(
            266
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document avec l'orientation paysage au format A3 est incorrecte")
        );
    }

    public function testSizeA5_P()
    {
        // Document A5 portrait
        $pdf = $this->makePdf(['orientation' => 'P', 'unit' => 'mm', 'format' => 'A5']);
        $this->assertEquals(
            128
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document A5 est incorrecte")
        );
        $this->assertEquals(
            179
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document A5 est incorrecte")
        );
    }

    public function testSizeA5_L()
    {
        // Document A5 paysage
        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'A5']);
        $this->assertEquals(
            189
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document avec l'orientation paysage au format A5 est incorrecte")
        );
        $this->assertEquals(
            118
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document avec l'orientation paysage au format A5 est incorrecte")
        );
    }

    public function testSizeLetter_P()
    {
        // Document Letter paysage
        $pdf = $this->makePdf(['orientation' => 'P', 'unit' => 'mm', 'format' => 'Letter']);
        $this->assertEquals(
            195
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document est incorrecte")
        );
        $this->assertEquals(
            249
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document est incorrecte")
        );
    }

    public function testSizeLetter_L()
    {
        // Document Letter paysage
        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'Letter']);
        $this->assertEquals(
            259
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document avec l'orientation paysage au format Letter est incorrecte")
        );
        $this->assertEquals(
            185
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document avec l'orientation paysage au format Letter est incorrecte")
        );
    }

    public function testSizeLegal_P()
    {
        $pdf = $this->makePdf(['orientation' => 'P', 'unit' => 'mm', 'format' => 'Legal']);
        $this->assertEquals(
            195
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document Legal en portrait est incorrecte")
        );
        $this->assertEquals(
            325
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document Legal en portrait est incorrecte")
        );
    }

    public function testSizeLegal_L()
    {
        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'Legal']);
        $this->assertEquals(
            335
            , intval($pdf->GetBodySize('width'))
            , $this->getMessage("La largeur du document avec l'orientation paysage au format Legal est incorrecte")
        );
        $this->assertEquals(
            185
            , intval($pdf->GetBodySize('height'))
            , $this->getMessage("La longueur du document avec l'orientation paysage au format Legal est incorrecte")
        );
    }

    public function testGetMargin()
    {
        $this->assertEquals(
            ['top', 'bottom', 'right', 'left', 'cell']
            , array_keys($this->makePdf()->getMargin())
            , $this->getMessage("La liste des marges ne contient pas les bonnes clés")
        );

        $this->assertEquals(
            10
            , intval($this->makePdf()->getMargin('r'))
            , $this->getMessage("La marge droite est incorrecte avec getMargin")
        );
    }

    public function testGetCursor()
    {
        $this->assertEquals(
            ['x', 'y']
            , array_keys($this->makePdf()->getCursor())
            , $this->getMessage("Les clés retournées sont incorrectes")
        );
    }

    public function testGetCursorX()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $this->assertEquals(
            10
            , intval($pdf->getCursor('x'))
            , $this->getMessage("La position X du curseur est incorrecte")
        );
    }

    public function testGetCursorY()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $this->assertEquals(
            10
            , intval($pdf->getCursor('y'))
            , $this->getMessage("La position Y du curseur est incorrecte")
        );
    }

    public function testSetCursorWithOnlyXParameter()
    {
        $pdf = $this->makePdf();
        $pdf->setCursor(25);
        $this->assertEquals(25, $pdf->GetX());
    }

    public function testSetCursorWithXYParameter()
    {
        $pdf = $this->makePdf();
        $pdf->setCursor(25, 5);
        $this->assertEquals(25, $pdf->GetX());
        $this->assertEquals(5, $pdf->GetY());
    }

    public function testGetMetadata()
    {
        $pdf = $this->makePdf();
        $pdf->SetTitle('Tests unitaires');
        $pdf->SetAuthor(get_class($this));
        $pdf->SetCreator('My Core');
        $this->assertArrayHasKey('Title', $pdf->getMetadata());
        $this->assertEquals('Tests unitaires', $pdf->getMetadata('Title'));
        $this->assertFalse($pdf->getMetadata('Fake'));
    }

    public function testSetMargin()
    {
        $pdf = $this->makePdf();
        $pdf->setMargin('right', 25);
        $this->assertEquals(25, $pdf->getMargin('r'));
    }

    public function testSetMetatdataWithString()
    {
        $pdf = $this->makePdf();
        $pdf->setMetadata('tests', 'unitaires');
        $this->assertEquals('unitaires', $pdf->getMetadata('tests'));
    }

    public function testSetMetatdataWithArray()
    {
        $pdf = $this->makePdf();
        //$pdf->setMetadata('tests', 'unitaires');
        $pdf->setMetadata(['tests' => 'unitaires']);
        $this->assertEquals('unitaires', $pdf->getMetadata('tests'));
    }

    public function testToString()
    {
        $this->assertInternalType(
            'string'
            , (string)$this->makePdf()
            , $this->getMessage("Le résultat de la conversion en chaîne de caractères est incorrecte")
        );
    }

    public function testHasFont()
    {
        $this->assertTrue(
            $this->makePdf()->hasFont('courier')
            , $this->getMessage("La police courier est censée être présente")
        );
    }

    public function testAddLine()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $pdf->addLine();
        $this->assertTrue(true);
    }

    public function testHasToolType()
    {
        $pdf = $this->makePdf();
        $this->assertTrue(
            $pdf->hasTool('text')
            , $this->getMessage("L'outil 'text' doit exister")
        );
        $this->assertTrue(
            $pdf->hasTool('fill')
            , $this->getMessage("L'outil 'fill' doit exister")
        );
        $this->assertTrue(
            $pdf->hasTool('draw')
            , $this->getMessage("L'outil 'draw' doit exister")
        );
        $this->assertFalse(
            $pdf->hasTool('fake')
            , $this->getMessage("L'outil 'fake' ne doit pas exister")
        );
    }

    public function testGetOrientation()
    {
        $this->assertEquals('P', $this->makePdf()->getOrientation());
    }

    public function testToFileWithFileName()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $pdf->SetFont();
        $pdf->MultiCell(0, 10, 'ola les gens');
        $fileName = __DIR__ . '/test_to_file';
        $pdf->toFile($fileName);
        $fileName .= '.pdf';
        $this->assertTrue(file_exists($fileName));
        $this->addUsedFile($fileName);
    }

    public function testToFileWithoutFileName()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $pdf->SetFont();
        $pdf->MultiCell(0, 10, 'ola les gens');
        $pdf->toFile();
        $fileName = 'MyCore_doc_' . date('Y-m-d-H-i') . '.pdf';
        $this->assertTrue(file_exists($fileName));
        $this->addUsedFile($fileName);
    }

    public function testGetTools()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(
            ['draw', 'fill', 'text']
            , $pdf->getTools()
            , $this->getMessage("La liste des outils est incorrecte")
        );
    }

    public function testGetUnits()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(
            ['pt', 'mm', 'cm', 'in']
            , $pdf->getUnits()
            , $this->getMessage("La liste des unités de mesures est incorrecte")
        );
    }

    public function testGetFormats()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(
            ['A3', 'A4', 'A5', 'Letter', 'Legal']
            , $pdf->getFormats()
            , $this->getMessage("La liste des formats de document est incorrecte")
        );
    }

    public function testHasTool()
    {
        $pdf = $this->makePdf();
        $this->assertTrue($pdf->hasTool('draw'));
        $this->assertTrue($pdf->hasTool('fill'));
        $this->assertTrue($pdf->hasTool('text'));
        $this->assertFalse($pdf->hasTool('fake'));
    }

    public function testHasUnit()
    {
        $pdf = $this->makePdf();
        $this->assertTrue($pdf->hasUnit('pt'));
        $this->assertTrue($pdf->hasUnit('cm'));
        $this->assertTrue($pdf->hasUnit('mm'));
        $this->assertTrue($pdf->hasUnit('in'));
        $this->assertFalse($pdf->hasUnit('fake'));
    }

    public function testHasFormat()
    {
        $pdf = $this->makePdf();
        $this->assertTrue($pdf->hasFormat('A3'));
        $this->assertTrue($pdf->hasFormat('A4'));
        $this->assertTrue($pdf->hasFormat('A5'));
        $this->assertTrue($pdf->hasFormat('Letter'));
        $this->assertTrue($pdf->hasFormat('Legal'));
        $this->assertFalse($pdf->hasFormat('fake'));
    }

    public function testGetPageBreak()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(
            276
            , intval($pdf->getPageBreak())
            , $this->getMessage("La valeur du saut de page est incorrecte")
        );
    }

    public function testGetToolColor()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(['draw', 'fill', 'text'], array_keys($pdf->getToolColor()));
        $this->assertEquals('0 G', $pdf->getToolColor('draw'));
        $this->assertEquals('0 g', $pdf->getToolColor('fill'));
        $this->assertEquals('0 g', $pdf->getToolColor('text'));
        $this->assertFalse($pdf->getToolColor('fake'));
    }

    public function testGetFontProperties()
    {
        $pdf = $this->makePdf();
        $pdf->SetFont();
        $this->assertEquals('helvetica', $pdf->getFontProperty('family'));
        $this->assertEquals('', $pdf->getFontProperty('style'));
        $this->assertEquals(10, $pdf->getFontProperty('size'));
    }

    public function testGetFontPropertiesWithoutParameter()
    {
        $this->assertEquals(
            [
                'family', 'style', 'size', 'sizeInUnit'
            ]
            , array_keys($this->makePdf()->getFontProperty())
            , $this->getMessage("Les propriétés de la police courante sont incorrectes")
        );
    }

    public function testGetFontPropertiesWithWrongParameter()
    {
        $this->assertFalse($this->makePdf()->getFontProperty('fake'));
    }

    public function testSetFontWithoutParameter()
    {
        $pdf = $this->makePdf();
        $pdf->SetFont();
        $this->assertEquals('helvetica', $pdf->getFontProperty('family'));
        $this->assertEquals('', $pdf->getFontProperty('style'));
        $this->assertEquals(10, $pdf->getFontProperty('size'));
    }

    public function testSetFontWithOnlyFontFamily()
    {
        $pdf = $this->makePdf();
        $pdf->SetFont('helvetica');

        $this->assertEquals(
            'helvetica'
            , $pdf->getFontProperty('family')
            , $this->getMessage("La police courante n'est pas celle définit")
        );

        $this->assertEquals('', $pdf->getFontProperty('style'));
        $this->assertEquals(10, $pdf->getFontProperty('size'));
    }

    public function testIsUnderline()
    {
        $this->assertFalse(
            $this->makePdf()->isUnderline()
            , $this->getMessage("Le document est instancié avec une police soulignable")
        );
    }

    public function testHexaToRgbWithCodeHexa()
    {
        $blackRgb = ['r' => 0, 'g' => 0, 'b' => 0];
        $pdf = $this->makePdf();
        $this->assertEquals($blackRgb, $pdf->hexaToRgb('#000000'));
    }

    public function testHexaToRgbWithWrongParameter()
    {
        $this->expectException(\Exception::class);
        $this->makePdf()->hexaToRgb('fake');
    }

    public function testSetToolColorWithoutParameter()
    {
        $pdf = $this->makePdf();
        $pdf->setToolColor(0);
        $this->assertEquals('0.000 g', $pdf->getToolColor('text'));
    }

    public function testSetToolColorWithToolParameter()
    {
        $pdf = $this->makePdf();
        $pdf->setToolColor(0, 'draw');
        $this->assertEquals('0.000 G', $pdf->getToolColor('draw'));
    }

    public function testSetToolColorWithRgbArray()
    {
        $pdf = $this->makePdf();
        $pdf->setToolColor(['r' => 0, 'g' => 0, 'b' => 0], 'draw');
        $this->assertEquals('0.000 G', $pdf->getToolColor('draw'));
    }

    public function testSetToolColorWithCodeHexa()
    {
        $pdf = $this->makePdf();
        $pdf->setToolColor('#000000', 'draw');
        $this->assertEquals('0.000 G', $pdf->getToolColor('draw'));
    }

    public function testSetToolColorWithWrongTool()
    {
        $pdf = $this->makePdf();
        $this->expectException(\Exception::class);
        $pdf->setToolColor('#000000', 'fake');
    }

    public function testSetToolColorWithNamedColor()
    {
        $pdf = $this->makePdf();
        $this->expectException(\Exception::class);
        $pdf->setToolColor('black');
    }

//    public function testFileToPdf()
//    {
//        $pdf = $this->makePdf();
//        $pdf->fileToPdf(__DIR__ .'/textFile.txt');
//    }
}
