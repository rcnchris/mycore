<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\MyFPDF;
use Rcnchris\Core\PDF\PDFFactory;
use Tests\Rcnchris\BaseTestCase;

class PDFFactoryTest extends BaseTestCase {

    public function testMake()
    {
        $this->ekoTitre('PDF - PDF Factory');
        $this->assertInstanceOf(MyFPDF::class, PDFFactory::make());
    }
}
