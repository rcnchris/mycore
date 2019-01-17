<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\PdfDoc;
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
        $this->assertInstanceOf(PdfDoc::class, $this->makePdf()->title('Ola'));
    }

    public function testCodeBloc()
    {
        $this->assertInstanceOf(PdfDoc::class, $this->makePdf()->codeBloc('Ola'));
    }

    public function testAlert()
    {
        $this->assertInstanceOf(PdfDoc::class, $this->makePdf()->alert('Ola'));
    }

    public function testPrintInfoClass()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(PdfDoc::class, $pdf->printInfoClass(get_class($this)));
        $this->assertInstanceOf(PdfDoc::class, $pdf->printInfoClass($this));
        $this->expectException(\Exception::class);
        $pdf->printInfoClass('fake');
    }

    public function testPrintDocumentProperties()
    {
        $this->assertInstanceOf(PdfDoc::class, $this->makePdf()->printDocumentProperties());
    }

    public function testSetTitleTemplates()
    {
        $templates = [
            ['fontFamily' => 'courier'],
            ['fontFamily' => 'helvetica']
        ];
        $this->assertInstanceOf(PdfDoc::class, $this->makePdf()->setTitleTemplates($templates));
    }

    public function testPuces()
    {
        $pdf = $this->makePdf();
        $pdf->SetFont('Times', '', 12);

        $column_width = ($pdf->GetPageWidth() - 30) / 2;
        $sample_text = 'Ceci est un paragraphe avec puce. Le texte est indenté et la puce apparaît uniquement sur la première ligne.';

        for ($n = 1; $n <= 3; $n++) {
            $pdf->puce($column_width, 6, chr(149), $sample_text);
        }
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->toFile($fileDest);
        $this->assertTrue(file_exists($fileDest . '.pdf'));
        $this->addUsedFile($fileDest.'.pdf');
        $pdf->Close();
    }

    public function testPucesWithArrayString()
    {
        $pdf = $this->makePdf();
        $pdf->SetFont('Times', '', 12);

        $column_width = ($pdf->GetPageWidth() - 30) / 2;
        $sample_text = [
            'Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus aliquam commodi earum fugiat inventore repudiandae ullam? Accusamus, dolores fuga, inventore ipsum magni, maiores officiis quae tempore ullam unde voluptates!',
            'Accusamus culpa nesciunt nisi pariatur? Aspernatur, consequuntur itaque. Accusamus animi cum delectus dolore ducimus esse facilis, fugiat, laudantium nemo porro, quo rem sequi voluptas? Odio officia praesentium tenetur vero voluptatibus.',
            'Blanditiis consectetur corporis, cupiditate dolores esse illum minus! Alias, deserunt dolores eveniet fugiat fugit ipsum modi nulla officia omnis perferendis quam quod repudiandae similique suscipit temporibus veniam veritatis. Animi, illo?'
        ];
        $pdf->puce($column_width, 6, chr(149), $sample_text);
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->toFile($fileDest);
        $this->assertTrue(file_exists($fileDest . '.pdf'));
        $this->addUsedFile($fileDest.'.pdf');
        $pdf->Close();
    }
}
