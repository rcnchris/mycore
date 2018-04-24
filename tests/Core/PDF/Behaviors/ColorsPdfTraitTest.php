<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Tests\Rcnchris\Core\PDF\PdfTestCase;

class ColorsPdfTraitTest extends PdfTestCase
{
    /**
     * @var ColorsPdf
     */
    protected $pdf;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\ColorsPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(ColorsPdf::class, $withPage);
    }

    public function testGetColorsWithoutParameter()
    {
        $pdf = $this->makePdf();
        $this->assertNotEmpty($pdf->getColors());
    }

    public function testGetColorsWithNamedColor()
    {
        $pdf = $this->makePdf();
        $this->assertEquals('#000000', $pdf->getColors('black'));
        $this->assertEquals(['r' => 0, 'g' => 0, 'b' => 0], $pdf->getColors('black', true));
    }

    public function testGetColorsWithCodeHexa()
    {
        $pdf = $this->makePdf();
        $this->assertEquals('black', $pdf->getColors('#000000'));
        $this->assertEquals(['r' => 0, 'g' => 0, 'b' => 0], $pdf->getColors('#000000', true));
    }

    public function testColorToRGB()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(['r' => 0, 'g' => 0, 'b' => 0], $pdf->colorToRgb('#000000'));
        $this->assertEquals(['r' => 0, 'g' => 0, 'b' => 0], $pdf->colorToRgb('black'));
    }

    public function testColorToRGBWithMissingColor()
    {
        $pdf = $this->makePdf();
        $this->expectException(\Exception::class);
        $pdf->colorToRgb('fake');
    }

    public function testColorToRGBWithWrongLenHexa()
    {
        $pdf = $this->makePdf();
        $this->expectException(\Exception::class);
        $pdf->colorToRgb('#132');
    }

    public function testAddColor()
    {
        $pdf = $this->makePdf()->addColor('test', '#123456');
        $this->assertEquals('test', $pdf->getColors('#123456'));
    }

    public function testHasColor()
    {
        $pdf = $this->makePdf();
        $this->assertTrue($pdf->hasColor('black'));
        $this->assertTrue($pdf->hasColor('#000000'));
        $this->assertFalse($pdf->hasColor('fake'));
        $this->assertFalse($pdf->hasColor('#123456'));
    }

    public function testSetColors()
    {
        $colors = [
            'flatflesh' => '#fad390',
            'melonmelody' => '#f8c291'
        ];
        $pdf = $this->makePdf()->setColors($colors);
        $this->assertEquals($colors, $pdf->getColors());
    }

    public function testSetToolColorWithNamedColor()
    {
        $pdf = $this->makePdf();
        $pdf->setToolColor('aloha', 'text');
        $this->assertEquals('0.102 0.737 0.612 rg', $pdf->getToolColor('text'));
    }
}
