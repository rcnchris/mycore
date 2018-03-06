<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\Behaviors\ColorsPdfTrait;

class ColorsTraitTest extends PdfTestCase
{

    /**
     * @var ColorsPdfTrait
     */
    protected $pdf;

    /**
     * Instancie le document PDF
     */
    public function setUp()
    {
        $this->pdf = $this->makePdf('colors');
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Colors');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\ColorsPdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testGetColorsWithoutParameter()
    {
        $this->assertNotEmpty(
            $this->pdf->getColors()
            , $this->getMessage("La palette de couleurs est vide")
        );
    }

    public function testGetColorsWithColorName()
    {
        $this->assertEquals(
            '#1ABC9C'
            , $this->pdf->getColors('aloha')
            , $this->getMessage("Le code héxadécimal de la couleur est incorrect")
        );
    }

    public function testGetColorsWithCodeHexa()
    {
        $this->assertEquals(
            'aloha'
            , $this->pdf->getColors('#1ABC9C')
            , $this->getMessage("Le nom de la couleur est incorrect")
        );
    }

    public function testGetColorToRgbWithNameColor()
    {
        $this->assertEquals(
            [
                'r' => 26
                , 'g' => 188
                , 'b' => 156
            ]
            , $this->pdf->getColors('aloha', true)
            , $this->getMessage("Les couleurs RGB obtenues sont incorrectes")
        );
    }

    public function testGetColorToRgbWithCodeColor()
    {
        $this->assertEquals(
            [
                'r' => 26
                , 'g' => 188
                , 'b' => 156
            ]
            , $this->pdf->getColors('#1ABC9C', true)
            , $this->getMessage("Les couleurs RGB obtenues sont incorrectes")
        );
    }

    public function testGetColorToRgbWithMissingColorName()
    {
        $this->assertNull(
            $this->pdf->getColors('fake')
            , $this->getMessage("Une couleur absente de la palette doit retourner null")
        );
    }

    public function testGetColorToRgbWithWrongLengthHexa()
    {
        $this->expectException(\Exception::class);
        $this->makePdf('colors')->colorToRgb('#1234A');
    }

    public function testGetColorToRgbWithMissingColorCode()
    {
        $this->assertFalse(
            $this->pdf->getColors('#123456')
            , $this->getMessage("Une couleur absente de la palette doit retourner null")
        );
    }

    public function testToRgbWithHexa()
    {
        $this->assertEquals(
            [
                'r' => 26
                , 'g' => 188
                , 'b' => 156
            ]
            , $this->pdf->colorToRgb('#1ABC9C')
            , $this->getMessage("Les couleurs RGB obtenues sont incorrectes")
        );
    }

    public function testToRgbWithName()
    {
        $this->assertEquals(
            [
                'r' => 26
                , 'g' => 188
                , 'b' => 156
            ]
            , $this->pdf->colorToRgb('aloha')
            , $this->getMessage("Les couleurs RGB obtenues sont incorrectes")
        );
    }

    public function testToRgbWithMissingColor()
    {
        $this->expectException(\Exception::class);
        $this->pdf->colorToRgb('fake');
    }

    public function testAddColor()
    {
        $pdf = $this->makePdf('colors');
        $name = 'pinkjigglypuff';
        $hexa = '#FF9FF3';
        $pdf->addColor($name, $hexa);

        $this->assertEquals(
            $name
            , $pdf->getColors($hexa)
            , $this->getMessage("La couleur ajoutée n'est pas correcte")
        );

        $this->assertEquals(
            $hexa
            , $pdf->getColors($name)
            , $this->getMessage("La couleur ajoutée n'est pas correcte")
        );
    }

    public function testSetColors()
    {
        $pdf = $this->makePdf('colors');
        $pdf->setColors(
            [
                'pinkjigglypuff' => '#FF9FF3'
                , 'pinklianhonglotus' => '#F368E0'
            ]
        );
        $this->assertCount(2, $pdf->getColors());
        $this->assertNull($pdf->getColors('aloha'));
        $this->assertEquals('#F368E0', $pdf->getColors('pinklianhonglotus'));
        $this->assertEquals('pinkjigglypuff', $pdf->getColors('#FF9FF3'));
    }

    public function testHasColor()
    {
        $pdf = $this->makePdf('colors');
        $this->assertTrue($pdf->hasColor('aloha'));
        $this->assertTrue($pdf->hasColor('#1ABC9C'));
        $this->assertFalse($pdf->hasColor('fake'));
    }

    public function testColors()
    {
        $pdf = $this->makePdf('colors');
        $ln = 0;
        foreach ($pdf->getColors() as $name => $hexa) {
            $pdf->setToolColor($hexa, 'fill');
            $pdf->Cell(10, 5, '', 0, $ln, '', true);
            $ln = $pdf->GetX() + 10 > 190 ? 1 : 0;
        }
        $this->assertPdfToFile(
            __FUNCTION__,
            "Palette de couleurs.",
            '$pdf->getColors()',
            $pdf,
            true
        );
    }
}
