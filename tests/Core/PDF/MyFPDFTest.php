<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\MyFPDF;
use Tests\Rcnchris\BaseTestCase;

class MyFPDFTest extends BaseTestCase
{

    public function makePdf()
    {
        return new MyFPDF();
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - My FPDF');
        $pdf = $this->makePdf();
        $this->assertInstanceOf(MyFPDF::class, $pdf);
        $this->assertEquals(1, $pdf->PageNo(), $this->getMessage("Le document doit avoir une page une fois instancié"));
        $this->assertEquals(10, $pdf->getMargin('top'), $this->getMessage("Le document doit avoir une marge haute de 10"));
        $this->assertEquals(10, $pdf->getMargin('left'), $this->getMessage("Le document doit avoir une marge gauche de 10"));
        $this->assertEquals(10, $pdf->getMargin('right'), $this->getMessage("Le document doit avoir une marge droite de 10"));
        $this->assertEquals(10, $pdf->getMargin('bottom'), $this->getMessage("Le document doit avoir une marge basse de 10"));
    }

    public function testSetMargins()
    {
        $pdf = $this->makePdf();
        $pdf->setMargin('top', 15);
        $this->assertEquals(15
            , $pdf->getMargin('top')
            , $this->getMessage("La valeur attendue est incorrecte")
        );

        $pdf->setMargin(['top' => 10]);
        $this->assertEquals(10
            , $pdf->getMargin('top')
            , $this->getMessage("La valeur attendue est incorrecte")
        );

        $pdf->setMargin('bottom', 15);
        $this->assertEquals(15
            , $pdf->getMargin('bottom')
            , $this->getMessage("La valeur attendue est incorrecte")
        );

        $pdf->setMargin(['bottom' => 15]);
        $this->assertEquals(15
            , $pdf->getMargin('bottom')
            , $this->getMessage("La valeur attendue est incorrecte")
        );
    }

    public function testGetMargin()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(
            10
            , $pdf->getMargin('top')
            , $this->getMessage("La valeur de la marge est incorrecte")
        );

        $this->assertEquals(
            [
                'top'    => 10,
                'bottom' => 10,
                'left'   => 10,
                'right'  => 10,
                'cell'  => 1.000125
            ]
            , $pdf->getMargin()
            , $this->getMessage("Le tableau des marges est incorrect")
        );

        $this->assertFalse(
            $pdf->getMargin('fake')
            , $this->getMessage("La marge fake doit me retourner false")
        );
    }

    public function testGetDocSize()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(210, $pdf->getDocSize('width'));
        $this->assertEquals(297, $pdf->getDocSize('height'));
        $this->assertEquals([
            'width' => 210,
            'height' => 297
        ], $pdf->getDocSize());
    }

    public function testGetBodySize()
    {
        $pdf = $this->makePdf();
        $this->assertEquals(190, $pdf->getBodySize('width'));
        $this->assertEquals(277, $pdf->getBodySize('height'));
        $this->assertEquals([
            'width' => 190,
            'height' => 277
        ], $pdf->getBodySize());
    }

    public function testGetFont()
    {
        $this->assertEquals('courier', $this->makePdf()->getFont());
    }

    public function testGetFontSize()
    {
        $this->assertEquals(12, $this->makePdf()->getFontSize());
    }

    public function testGetFontSizeInUnit()
    {
        $this->assertEquals(4.2, round($this->makePdf()->getFontSizeInUnit(), 1));
    }

    public function testGetFonts()
    {
        $this->assertContains('courier', $this->makePdf()->getFonts());
    }

    public function testGetFontsPath()
    {
        $this->assertTrue(file_exists($this->makePdf()->getFontsPath()));
    }

    public function testGetFontStyle()
    {
        $this->assertEquals('', $this->makePdf()->getFontStyle());
    }

    public function testIsUnderline()
    {
        $this->assertFalse($this->makePdf()->isUnderline());
    }

    public function testGetOrientation()
    {
        $this->assertEquals('P', $this->makePdf()->getOrientation());
    }

    public function testGetNbPages()
    {
        $this->assertEquals(1, $this->makePdf()->getTotalPages());
    }

    public function testSetColor()
    {
        $this->assertTrue($this->makePdf()->setColor());
        $this->assertTrue($this->makePdf()->setColor('#000000'));
        $this->assertTrue($this->makePdf()->setColor('#000000', 'fill'));
        $this->expectException(\Exception::class);
        $this->makePdf()->setColor('fake');
    }

    public function testGetColor()
    {
        $this->assertEquals('0 g', $this->makePdf()->getColor());
        $this->assertFalse($this->makePdf()->getColor('fake'));
    }

    public function testGetMetadata()
    {
        $this->assertFalse($this->makePdf()->getMetadata());
    }

    public function testSetMetadata()
    {
        $pdf = $this->makePdf()->setMetadata('fake', 'test fake');
        $this->assertArrayHasKey('fake', $pdf->getMetadata());
        $this->assertEquals('test fake', $pdf->getMetadata('fake'));

        $pdf = $pdf->setMetadata('fake', 'fake test');
        $this->assertEquals('fake test', $pdf->getMetadata('fake'));
    }

    public function testGetWidthOf()
    {
        $this->assertEquals(0.200025, $this->makePdf()->getWidth('line'));
        $this->assertEquals(0, $this->makePdf()->getWidth('lastCell'));
        $this->assertFalse($this->makePdf()->getWidth('fake'));
    }
}
