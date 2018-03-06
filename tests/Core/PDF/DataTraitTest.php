<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\DataPdf;

class DataTraitTest extends PdfTestCase
{

    /**
     * @var DataPdf
     */
    protected $pdf;

    public function setUp()
    {
        $this->pdf = $this->makePdf('data');
        $this->pdf->setData(['name' => 'Clara', 'year' => 2009]);
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Données');
        $this->assertInstanceOf(AbstractPDF::class, $this->pdf);
        $this->assertTrue(method_exists($this->pdf, 'setData'));
        $this->assertTrue(method_exists($this->pdf, 'getData'));
        $this->assertTrue(method_exists($this->pdf, 'hasKey'));
        $this->assertTrue(method_exists($this->pdf, 'hasValue'));
    }

    public function testHasKey()
    {
        $this->assertTrue($this->pdf->hasKey('name'));
    }

    public function testHasWrongKey()
    {
        $this->assertFalse($this->pdf->hasKey('fake'));
    }

    public function testHasKeyWithObject()
    {
        $o = new \stdClass();
        $o->name = 'Mathis';
        $o->year = 2007;
        $pdf = $this->makePdf('data');
        $pdf->setData($o);
        $this->assertTrue($pdf->hasKey('name'));
    }

    public function testHasValue()
    {
        $this->assertTrue($this->pdf->hasValue(2009));
    }

    public function testHasWrongValue()
    {
        $this->assertFalse($this->pdf->hasValue('fake'));
    }

    public function testHasValueWithObject()
    {
        $o = new \stdClass();
        $o->name = 'Mathis';
        $o->year = 2007;
        $pdf = $this->makePdf('data');
        $pdf->setData($o);
        $this->assertTrue($pdf->hasValue(2007));
    }

    public function testSetData()
    {
        $pdf = $this->makePdf('data');
        $pdf->setData(['ola' => 'ole']);
        $this->assertTrue($pdf->hasKey('ola'));
        $this->assertTrue($pdf->hasValue('ole'));
    }

    public function testGetData()
    {
        $this->assertEquals(
            ['name' => 'Clara', 'year' => 2009]
            , $this->pdf->getData()
        );
    }

    public function testGetDataWithKey()
    {
        $this->assertEquals('Clara', $this->pdf->getData('name'));
    }

    public function testGetDataWithWrongKey()
    {
        $this->assertFalse($this->pdf->getData('fake'));
    }

    public function testGetDataWithObject()
    {
        $o = new \stdClass();
        $o->name = 'Mathis';
        $o->year = 2007;
        $pdf = $this->makePdf('data');
        $pdf->setData($o);
        $this->assertEquals($o, $pdf->getData());
    }

    public function testGetDataWithObjectProperty()
    {
        $o = new \stdClass();
        $o->name = 'Mathis';
        $o->year = 2007;
        $pdf = $this->makePdf('data');
        $pdf->setData($o);
        $this->assertEquals('Mathis', $pdf->getData('name'));
    }

    public function testGetDataWithObjectWrongProperty()
    {
        $o = new \stdClass();
        $o->name = 'Mathis';
        $o->year = 2007;
        $pdf = $this->makePdf('data');
        $pdf->setData($o);
        $this->assertFalse($pdf->getData('fake'));
    }


    public function testData()
    {
        $pdf = $this->makePdf('data');
        $this->assertPdfToFile(
            __FUNCTION__,
            "Document avec des données.",
            '$pdf->getData()',
            $pdf
        );
    }
}
