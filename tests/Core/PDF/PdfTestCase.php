<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\PdfDoc;
use Tests\Rcnchris\BaseTestCase;

class PdfTestCase extends BaseTestCase
{
    /**
     * Emplacement des fichiers sources
     *
     * @var string
     */
    protected $filesPath = __DIR__ . '/files';

    /**
     * Emplacement des fichiers générés
     *
     * @var string
     */
    protected $resultPath = __DIR__ . '/results';

    /**
     * Instance du document PDF
     *
     * @var PdfDoc
     */
    protected $pdf;

    /**
     * Constructeur
     */
    public function setUp()
    {
        $this->pdf = $this->makePdf();
    }

    /**
     * Obtenir l'instance de AbstractPDF
     *
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Rcnchris\Core\PDF\PdfDoc
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        if (is_null($className)) {
            $pdf = new DocPdf();
        } else {
            if (!class_exists($className)) {
                throw new \Exception("La classe $className est introuvable !");
            }
            $pdf = new $className();
        }

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
