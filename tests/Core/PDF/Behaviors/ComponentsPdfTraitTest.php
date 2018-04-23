<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\AbstractPDF;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class ComponentsPdfTraitTest extends PdfTestCase
{
    /**
     * @var ComponentsPdf
     */
    protected $pdf;

    /**
     * @param bool $withPage
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\ComponentsPdf
     */
    public function makePdf($withPage = true)
    {
        $pdf = new ComponentsPdf();
        if ($withPage) {
            $pdf->AddPage();
        }
        return $pdf;
    }

    public function testGetTitleTemplates()
    {
        $pdf = $this->makePdf();
        $template = $pdf->getTitleTemplates(0);
        $this->assertInternalType('array', $template);
        $this->assertEquals(
            [
                'fontFamily',
                'fontStyle',
                'fontSize',
                'heightline',
                'border',
                'align',
                'underline',
                'fill',
                'fillColor',
                'drawColor',
                'textColor'
            ],
            array_keys($template)
        );

        $this->assertFalse($pdf->getTitleTemplates(12));
    }

    public function testGetTitleTemplatesWithKey()
    {
        $pdf = $this->makePdf();
        $this->assertEquals('helvetica', $pdf->getTitleTemplates(0, 'fontFamily'));
        $this->assertFalse($pdf->getTitleTemplates(0, 'fake'));
    }

    public function testTitle()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->title('Ola'));
    }

    public function testCodeBloc()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->codeBloc('Ola'));
    }

    public function testAlert()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->alert('Ola'));
    }

    public function testPrintInfoClass()
    {
        $pdf = $this->makePdf();
        $pdf->AddPage();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->printInfoClass(get_class($this)));
    }

    public function testPrintDocumentProperties()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->printDocumentProperties());
    }

    public function testSetTitleTemplates()
    {
        $pdf = $this->makePdf();
        $templates = [
            ['fontFamily' => 'courier'],
            ['fontFamily' => 'helvetica']
        ];
        $this->assertInstanceOf(AbstractPDF::class, $pdf->setTitleTemplates($templates));
    }
}
