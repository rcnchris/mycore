<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Tests\Rcnchris\Core\PDF\PdfTestCase;

class RotatePdfTraitTest extends PdfTestCase
{
    /**
     * @var RotatePdf
     */
    protected $pdf;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\RotatePdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(RotatePdf::class, $withPage);
    }

    public function testRotateText()
    {
        $fileDest = $this->resultPath . '/rotateImage';
        $this->makePdf()
            ->rotatedText(20, 40, 'ola', 45)
            ->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testRotateImage()
    {
        $fileDest = $this->resultPath . '/rotateText';
        $this->makePdf()
            ->rotatedImage($this->filesPath . '/circle.png', 20, 50, 35, 40, 45)
            ->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }



    public function testRotateTextWithAngleZero()
    {
        $fileDest = $this->resultPath . '/rotateImage';
        $this->makePdf()
            ->rotatedText(20, 40, 'ola', 0)
            ->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }
}