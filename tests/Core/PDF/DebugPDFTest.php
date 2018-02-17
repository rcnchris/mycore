<?php
/**
 * Fichier BookmarkTraitTest.php du 16/02/2018 
 * Description : Fichier de la classe BookmarkTraitTest 
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Tests\Rcnchris\Core\PDF
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\PDF;

use Psr\Http\Message\ResponseInterface;
use Rcnchris\Core\PDF\DebugPDF;
use Slim\Http\Response;
use Tests\Rcnchris\BaseTestCase;

/**
 * Class BookmarkTraitTest
 *
 * @category New
 *
 * @package Tests\Rcnchris\Core\PDF
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris/fmk-php GPL
 *
 * @version Release: <1.0.0>
 *
 * @link https://github.com/rcnchris/fmk-php on Github
 */
class DebugPDFTest extends BaseTestCase
{
    /**
     * @var \Rcnchris\Core\PDF\DebugPDF
     */
    private $pdf;

    /**
     * @param array $options
     * @param null  $data
     *
     * @return \Rcnchris\Core\PDF\DebugPDF
     */
    private function makePdf($options = [], $data = null)
    {
        if (is_null($data)) {
            $data = [
                ['name' => 'Mathis', 'year' => 2007],
                ['name' => 'Rapahël', 'year' => 2007],
                ['name' => 'Clara', 'year' => 2009],
            ];
        }
        return new DebugPDF($options, $data);
    }

    public function setUp()
    {
        $this->pdf = $this->makePdf();
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Debug PDF');
        $this->assertInstanceOf(
            DebugPDF::class
            , $this->pdf
            , $this->getMessage("La classe obtenue à l'instance est incorrecte")
        );
    }

    public function testAddDebug()
    {
        $this->pdf->addDebug();
        $fileName = __DIR__ . '/test_DebugPDF';
        $this->pdf->toFile($fileName);
        $fileName .= '.pdf';
        $this->assertTrue(file_exists($fileName));
        $this->addUsedFile($fileName);
    }

    public function testGetBookmarks()
    {
        $this->pdf->addDebug();
        $this->assertNotEmpty($this->pdf->getBookmarks());
    }

    public function testAddBookmark()
    {
        $this->assertEmpty($this->pdf->getBookmarks());
        $this->pdf->addBookmark('Test unitaire');
        $bookmarks = $this->pdf->getBookmarks();
        $this->assertNotEmpty($bookmarks);
        $this->assertEquals('Test unitaire', $bookmarks[0]['t']);
        $this->assertEquals(0, $bookmarks[0]['l']);


        $this->pdf->addBookmark('Sous-titre', 1);
        $bookmarks = $this->pdf->getBookmarks();
        $this->assertEquals('Sous-titre', $bookmarks[1]['t']);
        $this->assertEquals(1, $bookmarks[1]['l']);

        $this->pdf->addBookmark('detail', 2, -1);
        $bookmarks = $this->pdf->getBookmarks();
        $this->assertEquals('detail', $bookmarks[2]['t']);
        $this->assertEquals(2, $bookmarks[2]['l']);
    }

    public function testSetDataWithArray()
    {
        $this->pdf->setData(['name' => 'Mathis', 'year' => 2007]);
        $this->assertNotEmpty($this->pdf->getData());
        $this->assertEquals('Mathis', $this->pdf->getData('name'));
    }

    public function testSetDataWithObject()
    {
        $mathis = new \stdClass();
        $mathis->name = 'Mathis';
        $mathis->year = 2007;
        $this->pdf->setData($mathis);
        $this->assertNotEmpty($this->pdf->getData());
        $this->assertEquals('Mathis', $this->pdf->getData('name'));
    }

    public function testGetData()
    {
        $this->pdf->setData(['name' => 'Mathis', 'year' => 2007]);
        $this->assertNotEmpty($this->pdf->getData());
        $this->assertEquals('Mathis', $this->pdf->getData('name'));
        $this->assertFalse($this->pdf->getData('fake'));
    }

    public function testHasKey()
    {
        $this->pdf->setData(['name' => 'Mathis', 'year' => 2007]);
        $this->assertTrue($this->pdf->hasKey('year'));
    }

    public function testHasValue()
    {
        $this->pdf->setData(['name' => 'Mathis', 'year' => 2007]);
        $this->assertTrue($this->pdf->hasValue(2007));
    }

    public function testToView()
    {
        $this->assertInstanceOf(
            ResponseInterface::class
            , $this->pdf->toView(new Response())
            , $this->getMessage("La réponse de 'toView' est incorrecte")
        );
    }

    public function testToDownload()
    {
        $this->assertInstanceOf(
            ResponseInterface::class
            , $this->pdf->toDownload(new Response())
            , $this->getMessage("La réponse de 'toDownload' est incorrecte")
        );
    }

    public function testPrintIcon()
    {
        $this->pdf->printIcon('envelop', ['x' => 10, 'y' => 10]);
        $this->assertTrue(true);
    }

    public function testSetCols()
    {
        $this->pdf->setColsWidth(30, 50, 25);
        $this->assertEquals(3, $this->pdf->getNbCols());
        $this->assertEquals(30, intval($this->pdf->getColWidth(0)));
        $this->assertEquals(50, intval($this->pdf->getColWidth(1)));
        $this->assertEquals(25, intval($this->pdf->getColWidth(2)));
    }

    public function testSetColsInPourcentage()
    {
        $this->pdf->setColsWidthInPourc(30, 50, 25);
        $this->pdf->setColsAlign('L', 'L', 'L');
        $this->pdf->setColsBorder('B', 'B', 'B');
        $this->pdf->setColsDrawColors('black', 'red', 'blue');

        $bodyWidth = $this->pdf->GetPageWidth();

        $expextCol1 = $bodyWidth * 0.3;
        $expextCol2 = $bodyWidth * 0.5;
        $expextCol3 = $bodyWidth * 0.25;

        $this->assertEquals(3, $this->pdf->getNbCols());
        $this->assertEquals($expextCol1, $this->pdf->getColWidth(0));
        $this->assertEquals($expextCol2, $this->pdf->getColWidth(1));
        $this->assertEquals($expextCol3, $this->pdf->getColWidth(2));
        $this->assertFalse($this->pdf->getColWidth(15));
    }
}