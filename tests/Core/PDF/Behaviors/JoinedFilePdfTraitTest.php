<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class JoinedFilePdfTraitTest extends PdfTestCase
{
    /**
     * @var JoinedFilePdf
     */
    protected $pdf;

    /**
     * Fichier à attacher au document
     *
     * @var string
     */
    private $attachFile;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\JoinedFilePdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        $this->attachFile = $this->filesPath . '/textFile.txt';
        return parent::makePdf(JoinedFilePdf::class, $withPage);
    }

    public function testAttachReturnInstance()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->attach($this->attachFile)
        );
    }

    public function testAttachWithoutSlash()
    {
        $fileName = 'textFile.txt';
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->attach($fileName)
        );
    }

    public function testSetAttachPane()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->setAttachPane(true)
        );
    }

    public function testAttachFile()
    {
        $pdf = $this->makePdf()
            ->setAttachPane(true)
            ->attach($this->attachFile);
        $fileDest = $this->resultPath . '/joinedFile';
        $pdf->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }
}