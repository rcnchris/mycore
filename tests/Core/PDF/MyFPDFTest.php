<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\MyFPDF;
use Tests\Rcnchris\BaseTestCase;

class MyFPDFTest extends BaseTestCase
{

    /**
     * @param array $options
     *
     * @return \Rcnchris\Core\PDF\MyFPDF
     */
    public function makePdf(array $options = [])
    {
        return new MyFPDF($options);
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

    public function testParseOptionsWithWrongOrientation()
    {
        $this->expectException(\Exception::class);
        $this->makePdf(['orientation' => 'F']);
    }

    public function testParseOptionsWithWrongUnit()
    {
        $this->expectException(\Exception::class);
        $this->makePdf(['unit' => 'fa']);
    }

    public function testParseOptionsWithWrongFormat()
    {
        $this->expectException(\Exception::class);
        $this->makePdf(['format' => 'Fake']);
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

    public function testGetPageBreak()
    {
        $this->assertEquals(276, $this->makePdf()->getPageBreak());
    }

    public function testAddLine()
    {
        $this->assertInstanceOf(MyFPDF::class, $this->makePdf()->addLine());
    }

    public function testAddBookmark()
    {
        $pdf = $this->makePdf();
        $pdf->addBookmark('Title');
        $pdf->addBookmark('SubTitle', 1);
        $pdf->addBookmark('Phrase', 2, -1);
        $this->assertInstanceOf(MyFPDF::class, $pdf);
    }

    public function testToFile()
    {
        $pdf = $this->makePdf();

        $fileName = __DIR__ . '/test-' . date('YmdHi');

        // Meta
        $pdf->SetAuthor('rcn.chris@gmail.com');
        $pdf->SetCreator('My Core');
        $pdf->setMetadata('created', date('Y-m-d H:i'));

        // Police
        $pdf->SetFont('helvetica');

        // Add Debug block et sauvegarde dans un fichier
        $pdf->addDebug();
        $pdf2 = clone($pdf);
        $pdf->toFile($fileName);
        $this->assertTrue(file_exists($fileName . '.pdf'));

        $fileNameExpected = $this->rootPath() . '/MyCore_doc_' . date('Y-m-d-H-i') . '.pdf';
        $pdf2->toFile();
        $this->assertTrue(file_exists($fileNameExpected));
        $this->addUsedFile($fileNameExpected);
    }

    public function testToView()
    {
        $response = $this->runApp('GET', '/_lab/mycore/pdf/home/toView');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testToDownload()
    {
        $response = $this->runApp('GET', '/_lab/mycore/pdf/home/toDownload');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetCursor()
    {
        $pdf = $this->makePdf();
        $this->assertArrayHasKey(
            'x'
            , $pdf->getCursor()
            , $this->getMessage("La clé x est absente")
        );
        $this->assertArrayHasKey(
            'y'
            , $pdf->getCursor()
            , $this->getMessage("La clé y est absente")
        );
        $this->assertInternalType(
            'float'
            , $pdf->getCursor('x')
            , $this->getMessage("Le type attendu est incorrect")
        );
    }

    public function testGetBookmarks()
    {
        $pdf = $this->makePdf();
        $pdf->addBookmark('Title', 0, -1);
        $this->assertNotEmpty(
            $pdf->getBookmarks()
            , $this->getMessage("La liste des favoris ne doit pas être vide")
        );
    }

    public function testToString()
    {
        $this->assertInternalType(
            'string'
            , (string)$this->makePdf()
            , $this->getMessage("La conversion en chaîne de caractères est incorrecte")
        );
    }

    public function testSetOptions()
    {
        $pdf = $this->makePdf();
        $pdf->setOptions('ola', 'ole');
        $this->assertTrue(
            $pdf->hasOption('ola')
            , $this->getMessage("La clé attendue est absente des options")
        );
        $this->assertEquals(
            'ole'
            , $pdf->getOptions('ola')
            , $this->getMessage("La valeur attendue de l'option est incorrecte")
        );

        $options = [
            'oli' => 'olu'
            , 'oly' => 'olé'
        ];
        $pdf->setOptions($options);
        $this->assertTrue(
            $pdf->hasOption('oly')
            , $this->getMessage("La clé ettendue est absente des options")
        );
        $this->assertEquals(
            'olé'
            , $pdf->getOptions('oly')
            , $this->getMessage("La valeur attendue de l'option est incorrecte")
        );
    }

    public function testGetOptionsWithWrongParameter()
    {
        $pdf = $this->makePdf();
        $this->assertFalse($pdf->getOptions('fake'));
    }

    public function testSetCol()
    {
        $pdf = $this->makePdf();
        $pdf->setCol(2);
        $this->assertEquals(2, $pdf->col);
    }

    public function testSetColWithNumberCol()
    {
        $pdf = $this->makePdf();
        $pdf->setCol(2, 4);
        $this->assertEquals(2, $pdf->col);
        $this->assertEquals(4, $pdf->colNb);
    }
}
