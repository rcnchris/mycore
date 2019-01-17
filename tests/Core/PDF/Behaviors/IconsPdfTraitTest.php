<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\PdfDoc;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class IconsPdfTraitTest extends PdfTestCase
{
    /**
     * @var IconsPdf
     */
    protected $pdf;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\IconsPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(IconsPdf::class, $withPage);
    }

    public function testPrintIcon()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(PdfDoc::class, $pdf->printIcon('envelope'));
        $this->assertInstanceOf(PdfDoc::class, $pdf->printIcon('envelope', 10, 20));
    }
}
