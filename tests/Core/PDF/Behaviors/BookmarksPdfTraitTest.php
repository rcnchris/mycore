<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Tests\Rcnchris\Core\PDF\PdfTestCase;

class BookmarksPdfTraitTest extends PdfTestCase
{
    /**
     * @var BookmarksPdf
     */
    protected $pdf;

    /**
     * @param bool $withPage
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\BookmarksPdf
     */
    public function makePdf($withPage = true)
    {
        $pdf = new BookmarksPdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
    }

    public function testGetBookmarks()
    {
        $pdf = $this->makePdf()->addBookmark('Tests');
        $this->assertNotEmpty($pdf->getBookmarks());
        $this->assertEquals(
            [
                't', 'l', 'y', 'p'
            ],
            array_keys($pdf->getBookmarks(0))
        );
    }

    public function testGetBookmarksWithKey()
    {
        $pdf = $this->makePdf()->addBookmark('Tests');
        $this->assertEquals('Tests', $pdf->getBookmarks(0, 't'));
        $this->assertEquals(
            [
                't', 'l', 'y', 'p'
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
}
