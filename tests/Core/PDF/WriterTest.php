<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\Writer;

class WriterTest extends PdfTestCase
{

    public function makeWriter($pdf = null, array $options = [])
    {
        if (is_null($pdf)) {
            $pdf = $this->makePdf(false);
        }
        return new Writer($pdf, $options);
    }

    public function testDemo()
    {
        $pdf = $this->makePdf();
        $className = get_class($this->makeWriter());
        $shortName = explode('\\', $className);
        $shortName = array_pop($shortName);

        $this->ekoTitre("PDF - $shortName");

        $fileDest = __DIR__ . '/results/' . $shortName;
        $pdf->demo($className)->toFile($fileDest);
        $this->assertTrue(file_exists($fileDest . '.pdf'));
    }

    public function testInstance()
    {
        $writer = new Writer($this->makePdf());
        $this->assertInstanceOf(Writer::class, $writer);
    }

    public function testInstanceWithOptions()
    {
        $writer = new Writer($this->makePdf(), ['orientation' => 'L']);
        $this->assertInstanceOf(Writer::class, $writer);
    }

    public function testWriteSimpleTextWithoutParameter()
    {
        $writer = $this->makeWriter();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $content = 'ola les gens !';
        $pdf = $writer->write($content);
        $this->assertInstanceOf(AbstractPDF::class, $pdf);
        $pdf->toFile($fileDest);
    }

    public function testWriteSimpleTextWithOptions()
    {
        $writer = $this->makeWriter();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $content = 'ola les gens !';
        $pdf = $writer->write($content, [
            'heightline' => 20
        ]);
        $this->assertInstanceOf(AbstractPDF::class, $pdf);
        $pdf->toFile($fileDest);
    }

    public function testWriteArrayString()
    {
        $writer = $this->makeWriter();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $content = [
            'Ola les gens !',
            'Bien ou bien ?'
        ];
        $pdf = $writer->write($content, [
            'heightline' => 6
        ]);
        $this->assertInstanceOf(AbstractPDF::class, $pdf);
        $pdf->toFile($fileDest);
    }

    public function testWriteObject()
    {
        $writer = $this->makeWriter();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $content = new \stdClass();
        $content->name = 'Mathis';
        $content->year = 2007;
        $content->genre = 'male';
        $pdf = $writer->write($content, [
            'heightline' => 6,
            'keys' => ['name', 'year']
        ]);
        $this->assertInstanceOf(AbstractPDF::class, $pdf);
        $pdf->toFile($fileDest);
    }
}
