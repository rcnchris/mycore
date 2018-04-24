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
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\ComponentsPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(ComponentsPdf::class, $withPage);
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
        $this->assertInstanceOf(AbstractPDF::class, $this->makePdf()->title('Ola'));
    }

    public function testCodeBloc()
    {
        $this->assertInstanceOf(AbstractPDF::class, $this->makePdf()->codeBloc('Ola'));
    }

    public function testAlert()
    {
        $this->assertInstanceOf(AbstractPDF::class, $this->makePdf()->alert('Ola'));
    }

    public function testPrintInfoClass()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(AbstractPDF::class, $pdf->printInfoClass(get_class($this)));
        $this->assertInstanceOf(AbstractPDF::class, $pdf->printInfoClass($this));
        $this->expectException(\Exception::class);
        $pdf->printInfoClass('fake');
    }

    public function testPrintDocumentProperties()
    {
        $this->assertInstanceOf(AbstractPDF::class, $this->makePdf()->printDocumentProperties());
    }

    public function testSetTitleTemplates()
    {
        $templates = [
            ['fontFamily' => 'courier'],
            ['fontFamily' => 'helvetica']
        ];
        $this->assertInstanceOf(AbstractPDF::class, $this->makePdf()->setTitleTemplates($templates));
    }
}
