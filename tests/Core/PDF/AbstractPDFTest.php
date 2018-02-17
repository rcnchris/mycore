<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\BaseTestCase;

class AbstractPDFTest extends BaseTestCase {

    private function makePdf($options = [], $data = null)
    {
        if (is_null($data)) {
            $data = [
                ['name' => 'Mathis', 'year' => 2007],
                ['name' => 'Rapahël', 'year' => 2007],
                ['name' => 'Clara', 'year' => 2009],
            ];
        }
        return new AbstractPDF($options, $data);
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
        $this->assertEquals(
            1
            , $this->makePdf()->getTotalPages()
            , $this->getMessage("Le nombre total de page est incorrect à l'instanciation")
        );
    }

    public function testGetFont()
    {
        $this->assertEquals(
            'courier'
            , $this->makePdf()->getFont()
            , $this->getMessage("La police par défaut est incorrecte")
        );

        $this->assertEquals(
            10
            , $this->makePdf()->getFont('size')
            , $this->getMessage("La taille de la police par défaut est incorrecte")
        );

        $this->assertEquals(
            ['family', 'style', 'size', 'sizeInUnit', 'color', 'isUnderline']
            , array_keys($this->makePdf()->getFont(null, true))
            , $this->getMessage("Les clés du retour de getFont sont incorrectes")
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
        $pdf = $this->makePdf();
        $this->assertEquals(
            ['width' => 189, 'height' => 266]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document par défaut est incorrecte")
        );

        $pdf = $this->makePdf(['orientation' => 'P', 'unit' => 'mm', 'format' => 'A3']);
        $this->assertEquals(
            ['width' => 276, 'height' => 389]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document au format A3 est incorrecte")
        );

        $pdf = $this->makePdf(['orientation' => 'P', 'unit' => 'mm', 'format' => 'A5']);
        $this->assertEquals(
            ['width' => 128, 'height' => 179]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document au format A5 est incorrecte")
        );

        $pdf = $this->makePdf(['orientation' => 'P', 'unit' => 'mm', 'format' => 'Letter']);
        $this->assertEquals(
            ['width' => 195, 'height' => 249]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document au format Letter est incorrecte")
        );

        $pdf = $this->makePdf(['orientation' => 'P', 'unit' => 'mm', 'format' => 'Legal']);
        $this->assertEquals(
            ['width' => 195, 'height' => 325]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document au format Legal est incorrecte")
        );

        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'A4']);
        $this->assertEquals(
            ['width' => 276, 'height' => 179]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document avec l'orientation paysage est incorrecte")
        );

        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'A3']);
        $this->assertEquals(
            ['width' => 399, 'height' => 266]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document avec l'orientation paysage au format A3 est incorrecte")
        );

        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'A5']);
        $this->assertEquals(
            ['width' => 189, 'height' => 118]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document avec l'orientation paysage au format A5 est incorrecte")
        );

        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'Letter']);
        $this->assertEquals(
            ['width' => 259, 'height' => 185]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document avec l'orientation paysage au format Letter est incorrecte")
        );

        $pdf = $this->makePdf(['orientation' => 'L', 'unit' => 'mm', 'format' => 'Legal']);
        $this->assertEquals(
            ['width' => 335, 'height' => 185]
            , $pdf->getBodySize()
            , $this->getMessage("La taille du document avec l'orientation paysage au format Legal est incorrecte")
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
            ['x' => 10, 'y' => 10]
            , $this->makePdf()->getCursor()
            , $this->getMessage("La position du curseur est incorrecte")
        );

        $this->assertEquals(
            10
            , $this->makePdf()->getCursor('x')
            , $this->getMessage("La position X du curseur est incorrecte")
        );

        $this->assertEquals(
            10
            , $this->makePdf()->getCursor('y')
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

    public function testGetColor()
    {
        $this->assertInternalType(
            'string'
            , $this->makePdf()->getToolColor()
            , $this->getMessage("Le type de retour de getToolColor est incorrect sans paramètre")
        );
        $this->assertInternalType(
            'string'
            , $this->makePdf()->getToolColor('text')
            , $this->getMessage("Le type de retour de getToolColor est incorrect avec 'text'")
        );
        $this->assertInternalType(
            'string'
            , $this->makePdf()->getToolColor('fill')
            , $this->getMessage("Le type de retour de getToolColor est incorrect avec 'fill'")
        );
        $this->assertInternalType(
            'string'
            , $this->makePdf()->getToolColor('draw')
            , $this->getMessage("Le type de retour de getToolColor est incorrect avec 'draw'")
        );

        $this->assertFalse($this->makePdf()->getToolColor('fake'));
    }

    public function testGetColors()
    {
        $pdf = $this->makePdf();
        $this->assertArrayHasKey('aloha', $pdf->getColors());
        $this->assertEquals('#1ABC9C', $pdf->getColors('aloha'));
        $this->assertEquals('aloha', $pdf->getColors('#1ABC9C'));
    }

    public function testHexaToRgb()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(
            ['r' => 26, 'g' => 188, 'b' => 156]
            , $pdf->hexaToRgb('#1ABC9C')
            , $this->getMessage("Les valeurs RGB de la couleur 'aloha' sont incorrectes")
        );
    }

    public function testHexaToRgbWithWrongParameterText()
    {
        $pdf = $this->makePdf();
        $this->expectException(\Exception::class);
        $pdf->hexaToRgb('fake');
    }

    public function testHexaToRgbWithWrongParameterCodeHexa()
    {
        $pdf = $this->makePdf();
        $this->expectException(\Exception::class);
        $pdf->hexaToRgb('#45');
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

    public function testSetColorWithoutType()
    {
        $pdf = $this->makePdf();
        $pdf->setColor('aloha');
        $this->assertEquals('0.102 0.737 0.612 rg', $pdf->getToolColor());
        $this->assertEquals('0.102 0.737 0.612 rg', $pdf->getToolColor('text'));
    }

    public function testSetColorWithType()
    {
        $pdf = $this->makePdf();
        $pdf->setColor('aloha', 'fill');
        $this->assertEquals('0.102 0.737 0.612 rg', $pdf->getToolColor('fill'));
    }

    public function testSetMargin()
    {
        $pdf  = $this->makePdf();
        $pdf->setMargin('right', 25);
        $this->assertEquals(25, $pdf->getMargin('r'));
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
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->makePdf()->addLine()
            , $this->getMessage("L'ajout d'une ligne doit retournée l'instance")
        );
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

    public function testSetFont()
    {
        $pdf = $this->makePdf();

        $pdf->SetFont();
        $this->assertEquals('courier', $pdf->getFont());

        $pdf->SetFont('helvetica');
        $this->assertEquals('helvetica', $pdf->getFont());

        $pdf->SetFont('helvetica', 'B');
        $this->assertEquals('helvetica', $pdf->getFont('family'));
        $this->assertEquals('B', $pdf->getFont('style'));
        $this->assertFalse($pdf->getFont('isUnderline'));

        $pdf->SetFont('helvetica', 'B', 15);
        $this->assertEquals('helvetica', $pdf->getFont('family'));
        $this->assertEquals('B', $pdf->getFont('style'));
        $this->assertEquals(15, $pdf->getFont('size'));
        $this->assertFalse($pdf->getFont('isUnderline'));

        $pdf->SetFont('helvetica', 'B', 15, 'blue');
        $this->assertEquals('helvetica', $pdf->getFont('family'));
        $this->assertEquals('B', $pdf->getFont('style'));
        $this->assertEquals(15, $pdf->getFont('size'));
        $this->assertEquals('0.000 0.000 1.000 rg', $pdf->getToolColor('text'));
        $this->assertFalse($pdf->getFont('isUnderline'));

        $pdf->SetFont('helvetica', 'B', 15, 'blue', true);
        $this->assertEquals('helvetica', $pdf->getFont('family'));
        $this->assertEquals('B', $pdf->getFont('style'));
        $this->assertEquals(15, $pdf->getFont('size'));
        $this->assertEquals('0.000 0.000 1.000 rg', $pdf->getToolColor('text'));
        $this->assertTrue($pdf->getFont('isUnderline'));

        $pdf->SetFont('helvetica', 'B', 15, 'blue', false, 'blue');
        $this->assertEquals('helvetica', $pdf->getFont('family'));
        $this->assertEquals('B', $pdf->getFont('style'));
        $this->assertEquals(15, $pdf->getFont('size'));
        $this->assertEquals('0.000 0.000 1.000 rg', $pdf->getToolColor('text'));
        $this->assertFalse($pdf->getFont('isUnderline'));
        $this->assertEquals('0.000 0.000 1.000 rg', $pdf->getToolColor('fill'));

        $pdf->SetFont('helvetica', 'B', 15, 123);
        $this->assertEquals('helvetica', $pdf->getFont('family'));
        $this->assertEquals('B', $pdf->getFont('style'));
        $this->assertEquals(15, $pdf->getFont('size'));
        $this->assertEquals('0.482 g', $pdf->getToolColor('text'));
        $this->assertFalse($pdf->getFont('isUnderline'));

        $pdf->SetFont('helvetica', 'B', 15, [123, 105, 45]);
        $this->assertEquals('helvetica', $pdf->getFont('family'));
        $this->assertEquals('B', $pdf->getFont('style'));
        $this->assertEquals(15, $pdf->getFont('size'));
        $this->assertEquals('0.482 0.412 0.176 rg', $pdf->getToolColor('text'));
        $this->assertFalse($pdf->getFont('isUnderline'));
    }

    public function testToFileWithFileName()
    {
        $pdf = $this->makePdf();
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

    public function testHasColor()
    {
        $pdf = $this->makePdf();
        $this->assertTrue($pdf->hasColor('aloha'));
        $this->assertFalse($pdf->hasColor('fake'));
    }

    public function testAddColor()
    {
        $pdf = $this->makePdf();
        $pdf->addColor('flatflesh', '#fad390');
        $this->assertEquals('#FAD390', $pdf->getColors('flatflesh'));
    }

    public function testSetColors()
    {
        $palette = [
            'livid' => '#6a89cc',
            'spray' => '#82ccdd'
        ];
        $pdf = $this->makePdf();
        $pdf->setColors($palette);
        $this->assertTrue($pdf->hasColor('spray'));
    }

    public function testSetColor()
    {
        $pdf = $this->makePdf();
        $pdf->setColor('black');
        $this->assertEquals('0.000 g', $pdf->getToolColor('text'));

        $pdf->setColor('black', 'draw');
        $this->assertEquals('0.000 G', $pdf->getToolColor('draw'));

        $pdf->setColor('black', 'fill');
        $this->assertEquals('0.000 g', $pdf->getToolColor('fill'));

        $pdf->setColor('black', 'text');
        $this->assertEquals('0.000 g', $pdf->getToolColor('text'));

        $this->expectException(\Exception::class);
        $pdf->setColor('black', 'fake');
    }

    public function testGetPageBreak()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(
            276
            , $pdf->getPageBreak()
            , $this->getMessage("La valeur du saut de page est incorrecte")
        );
    }
}
