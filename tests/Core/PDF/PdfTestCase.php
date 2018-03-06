<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\PdfFactory;
use Tests\Rcnchris\BaseTestCase;

class PdfTestCase extends BaseTestCase
{

    /**
     * @var object
     */
    protected $pdf;

    /**
     * Nom du fichier de sortie
     *
     * @var string
     */
    protected $fileName;

    /**
     * Instancie la classe qui correspond au type
     *
     * @param string|null $type Type de trait à utiliser dans le PDF
     *
     * @return object
     */
    protected function makePdf($type = null)
    {
        $this->makeFileName();
        $this->pdf = PdfFactory::make($type);
        return $this->pdf;
    }

    /**
     * Génère le nom du fichier de sortie
     */
    private function makeFileName()
    {
        $fileName = explode('\\', get_class($this));
        $fileName = str_replace('TraitTest', '', array_pop($fileName));
        $this->fileName = $fileName;
    }

    /**
     * Génère le fichier PDF du test et s'assure de sa présence
     *
     * @param string      $methodName Nom de la méthode du test
     * @param string      $label      Description de l'exemple
     * @param string      $code       Code PHP de l'exemple
     * @param object|null $pdf        Document PDF
     * @param bool|null   $delete     Le document doit être supprimé à l'issu du test
     */
    protected function assertPdfToFile($methodName, $label, $code, $pdf = null, $delete = false)
    {
        if (is_null($pdf)) {
            $pdf = $this->pdf;
        }
        $this->printBlocTestMethod($methodName, $label, $code, $pdf);

        $fileName = str_replace('test', '', $methodName);
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . 'res' . DIRECTORY_SEPARATOR . $fileName;
        $pdf->toFile($fileName);
        $this->assertTrue(file_exists($fileName . '.pdf'));
        if ($delete) {
            $this->addUsedFile($fileName . '.pdf');
        }
    }

    /**
     * Imprime le bloc de test qui correspond à une méthode
     *
     * @param string      $methodName Nom de la méthode qui lance le test
     * @param string      $label      Description de l'exemple
     * @param string      $code       Code PHP de l'exemple
     * @param object|null $pdf        Document PDF
     */
    private function printBlocTestMethod($methodName, $label, $code, $pdf = null)
    {
        if (is_null($pdf)) {
            $pdf = $this->pdf;
        }
        $pdf->MultiCell(0, 10, get_class($this) . ' - ' . $methodName);
        $pdf->MultiCell(0, 10, utf8_decode($label));
        $pdf->SetFont('courier', '', 10, ['fillColor' => '#CCCCCC']);
        if (is_string($code)) {
            $pdf->MultiCell(0, 10, $code, 4, 'L', true);
        } elseif (is_array($code)) {
            foreach ($code as $ligne) {
                $pdf->MultiCell(0, 10, $ligne, 4, 'L', true);
            }

        }
    }
}
