<?php
namespace Tests\Rcnchris\Core\PDF;

use Tests\Rcnchris\BaseTestCase;

class PdfTestCase extends BaseTestCase
{
    protected $filesPath = __DIR__ . '/files';
    protected $resultPath = __DIR__ . '/results';

    /**
     * Obtenir l'instance de AbstractPDF
     *
     * @param bool|null $withPage N'ajoute pas de première page si false
     *
     * @return \Rcnchris\Core\PDF\AbstractPDF
     */
    public function makePdf($withPage = true)
    {
        $pdf = new DocPdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
    }

    public function testDemo()
    {
        $pdf = $this->makePdf();
        $className = get_class($pdf);
        $shortName = explode('\\', $className);
        $shortName = array_pop($shortName);
        $this->ekoTitre("PDF - $shortName");
        $fileDest = __DIR__ . '/results/' . $shortName;
        $pdf->demo()->toFile($fileDest);
        $this->assertTrue(file_exists($fileDest . '.pdf'));
    }
}
