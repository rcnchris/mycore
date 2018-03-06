<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\PdfFactory;
use Tests\Rcnchris\BaseTestCase;

class PdfFactoryTest extends BaseTestCase
{
    public function testMakeDefault()
    {
        $this->ekoTitre('PDF - Factory');
        $this->assertInstanceOf(AbstractPDF::class, PdfFactory::make());
    }

    public function testMakeData()
    {
        $pdf = PdfFactory::make('data');
        $this->assertInstanceOf(AbstractPDF::class, $pdf);
        $this->assertTrue(method_exists($pdf, 'getData'));
    }

    public function testMakeWithWrongType()
    {
        $this->expectException(\Exception::class);
        PdfFactory::make('fake');
    }
}
