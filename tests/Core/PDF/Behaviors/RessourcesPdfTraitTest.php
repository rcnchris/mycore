<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class RessourcesPdfTraitTest extends PdfTestCase
{
    /**
     * @var RessourcesPdf
     */
    protected $pdf;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\RessourcesPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(RessourcesPdf::class, $withPage);
    }

    public function testHasNativesRessourcesMethods()
    {
        $pdf = $this->makePdf();
        $methodNames = ['_putresources', '_putcatalog'];
        $this->assertObjectHasMethods($pdf, $methodNames);
        $pdf->Close();
    }

    /**
     * BOOOKMARKS
     */


    public function testHasBookmarksMethods()
    {
        $pdf = $this->makePdf();
        $methodNames = [
            'addBookmark',
            'getBookmarks',
            'getBookmarksMaxLevel',
            'putBookmarks',
            '_putresources',
            '_putcatalog'
        ];
        $this->assertObjectHasMethods($pdf, $methodNames);
        $pdf->Close();
    }

    public function testHasBookmarksProperties()
    {
        $pdf = $this->makePdf();
        $this->assertObjectHasAttribute('bookmarks', $pdf);
        $this->assertObjectHasAttribute('bookmarkRoot', $pdf);
        $pdf->Close();
    }

    public function testAddBookmarks()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->addBookmark('Le premier'));
        $this->assertCount(1, $pdf->getBookmarks());
    }

    public function testGetBookmarks()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->addBookmark('Le premier'));
        $this->assertCount(1, $pdf->getBookmarks());
        $this->assertEquals(['t', 'l', 'y', 'p'], array_keys($pdf->getBookmarks(0)));
        $this->assertEquals('Le premier', $pdf->getBookmarks(0, 't'));
    }

    public function testGetBookmarksWithKey()
    {
        $pdf = $this->makePdf()->addBookmark('Tests');
        $this->assertEquals('Tests', $pdf->getBookmarks(0, 't'));
        $this->assertEquals(
            [
                't',
                'l',
                'y',
                'p'
            ],
            array_keys($pdf->getBookmarks(0, 'z'))
        );
        $this->assertFalse($pdf->getBookmarks(12));
    }

    public function testGetMaxLevelTitle()
    {
        $pdf = $this->makePdf()->addBookmark('Tests');
        $this->assertEquals(0, $pdf->getBookmarksMaxLevel());
        $pdf = $this->makePdf()->addBookmark('Tests 2', 1);
        $this->assertEquals(1, $pdf->getBookmarksMaxLevel());
    }

    /**
     * JOINED FILES
     */

    public function testHasJoinedFilesMethods()
    {
        $pdf = $this->makePdf();
        $methodNames = ['setJoinedPane', 'joinFile', 'putJoinedFiles'];
        $this->assertObjectHasMethods($pdf, $methodNames);
        $pdf->Close();
    }

    public function testHasJoinedFilesProperties()
    {
        $pdf = $this->makePdf();
        $this->assertObjectHasAttribute('joinedPane', $pdf);
        $this->assertObjectHasAttribute('joinedFiles', $pdf);
        $this->assertObjectHasAttribute('nFiles', $pdf);
        $pdf->Close();
    }

    public function testJoinFileReturnInstance()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->joinFile($this->filesPath . '/textFile.txt')
        );
    }

    public function testJoinedWithoutSlash()
    {
        $fileName = 'textFile.txt';
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->joinFile($fileName)
        );
    }

    public function testSetJoinedFilesPane()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->setJoinedPane(true)
        );
    }

    public function testJoinFile()
    {
        $pdf = $this->makePdf()
            ->setJoinedPane(true)
            ->joinFile($this->filesPath . '/textFile.txt');
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    /**
     * INDEX
     */

    public function testCreateIndex()
    {
        $pdf = $this->makePdf();
        // Page 1
        $pdf->AddPage();
        $pdf->addBookmark('Section 1', 0)->Cell(0, 6, 'Section 1', 0, 1);
        $pdf->addBookmark('Sous-section 1', 1)->Cell(0, 6, 'Sous-section 1');
        $pdf->Ln(50);
        $pdf->addBookmark('Sous-section 2', 1)->Cell(0, 6, 'Sous-section 2');

        // Page 2
        $pdf->AddPage();
        $pdf->addBookmark('Section 2', 0)->Cell(0, 6, 'Section 2', 0, 1);
        $pdf->addBookmark('Sous-section 1', 1)->Cell(0, 6, 'Sous-section 1');

        // Page index
        $pdf->AddPage();
        $pdf->addBookmark('Index', 0);
        $pdf->createIndex();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->toFile($fileDest);
        $this->assertTrue(file_exists($fileDest . '.pdf'));
        $this->addUsedFile($fileDest . '.pdf');
    }
}
