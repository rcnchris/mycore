<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class DesignerPdfTraitTest extends PdfTestCase
{
    /**
     * @var DesignerPdf
     */
    protected $pdf;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\DesignerPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(DesignerPdf::class, $withPage);
    }

    public function testDrawReturnInstance()
    {
        $this->assertInstanceOf(
            AbstractPDF::class,
            $this->makePdf()->draw('rect', [])
        );
    }

    public function testDrawWithWrongFormName()
    {
        $this->expectException(\Exception::class);
        $this->makePdf()->draw('fake');
    }

    public function testDrawLine()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('line', [
            'lnBefore' => 5,
            'lnAfter' => 0
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testDrawLineWithlnAfter()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('line', [
            'lnBefore' => 5,
            'lnAfter' => 2
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testDrawRectangle()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('rect', [
            'x' => 20,
            'y' => 40,
            'w' => 50,
            'h' => 25,
            'r' => 6,
            'corners' => '1234',
            'style' => ''
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testDrawRectangleWithStyleF()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('rect', [
            'x' => 20,
            'y' => 40,
            'w' => 50,
            'h' => 25,
            'r' => 6,
            'corners' => '1234',
            'style' => 'F'
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testDrawRectangleWithStyleFD()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('rect', [
            'x' => 20,
            'y' => 40,
            'w' => 50,
            'h' => 25,
            'r' => 6,
            'corners' => '1234',
            'style' => 'FD'
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testDrawCircle()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('circle', [
            'x' => 20,
            'y' => 40,
            'r' => 12,
            'style' => ''
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testDrawCircleWithStyleF()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('circle', [
            'x' => 20,
            'y' => 40,
            'r' => 12,
            'style' => 'F'
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testDrawCircleWithStyleFD()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('circle', [
            'x' => 20,
            'y' => 40,
            'r' => 12,
            'style' => 'FD'
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testDrawEllipse()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('ellipse', [
            'x' => 20,
            'y' => 40,
            'rx' => 12,
            'ry' => 6,
            'style' => ''
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }

    public function testDrawGrid()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('grid', [
            'spacing' => 5
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
    }
}
