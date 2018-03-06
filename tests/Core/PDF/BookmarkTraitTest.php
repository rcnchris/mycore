<?php
use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\BookmarkPdf;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class BookmarkTraitTest extends PdfTestCase
{
    /**
     * @var BookmarkPdf
     */
    protected $pdf;

    public function setUp()
    {
        $this->pdf = $this->makePdf('bookmark');
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Bookmarks');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\BookmarkPdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testAddBookmark()
    {
        $pdf = $this->makePdf('bookmark');
        $pdf->addBookmark('Test');

        $this->assertCount(
            1
            , $pdf->getBookmarks()
            , $this->getMessage("Il ne doit y avoir qu'un seul favori")
        );
        $this->assertEquals(
            ['t', 'l', 'y', 'p']
            , array_keys($pdf->getBookmarks(0))
            , $this->getMessage("Les clés d'un favori sont incorrecte")
        );
        $this->assertEquals(
            'Test'
            , $pdf->getBookmarks(0)['t']
            , $this->getMessage("Le titre du favori ne correspond pas à celui définit")
        );
        $this->assertEquals('0', $pdf->getBookmarks(0)['l']);
    }

    public function testAddBookmarkWithYValue()
    {
        $pdf = $this->makePdf('bookmark');
        $pdf->addBookmark('Ola', 0, -1);
        $this->assertCount(1, $pdf->getBookmarks());
    }

    public function testGetBookmarksWithoutParameter()
    {
        $pdf = $this->makePdf('bookmark');
        $pdf->addBookmark('Le premier');

        $this->assertNotEmpty($pdf->getBookmarks());
        $this->assertCount(1, $pdf->getBookmarks());
    }

    public function testGetBookmarksWithParameter()
    {
        $pdf = $this->makePdf('bookmark');
        $pdf->addBookmark('Le premier');

        $this->assertNotEmpty($pdf->getBookmarks());
        $this->assertEquals(['t', 'l', 'y', 'p'], array_keys($pdf->getBookmarks(0)));
        $this->assertEquals('Le premier', $pdf->getBookmarks(0)['t']);
        $this->assertEquals('0', $pdf->getBookmarks(0)['l']);
    }

    public function testGetBookmarkWithKey()
    {
        $pdf = $this->makePdf('bookmark');
        $pdf->addBookmark('Le premier');
        $this->assertEquals('Le premier', $pdf->getBookmarks(0, 't'));
    }

    public function testGetBookmarksWithWrongParameter()
    {
        $pdf = $this->makePdf('bookmark');
        $pdf->addBookmark('Le seul');
        $this->assertFalse($pdf->getBookmarks(1));
    }

    public function testWithoutBookmark()
    {
        $pdf = $this->makePdf('bookmark');
        $this->assertPdfToFile(
            __FUNCTION__,
            "Générer un fichier sans signet.",
            '$pdf->toFile();',
            $pdf,
            true
        );
    }

    public function testBookmarks()
    {
        $pdf = $this->makePdf('bookmark');
        $pdf->addBookmark('Le titre');
        $pdf->addBookmark('Le sous-titre', 1);
        $pdf->addBookmark('Le contenu', 2);

        $code = [
            '$pdf->addBookmark("Le titre")',
            '$pdf->addBookmark("Le sous-titre, 1")',
            '$pdf->addBookmark("Le contenu, 2")',
        ];
        $this->assertPdfToFile(
            __FUNCTION__,
            "Générer un fichier avec des signets.",
            $code,
            $pdf
        );
    }
}