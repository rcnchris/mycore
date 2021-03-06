<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\PdfDoc;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class DataPdfTraitTest extends PdfTestCase
{
    /**
     * @var DataPdf
     */
    protected $pdf;

    /**
     * Données du document PDF
     *
     * @var array
     */
    private $data = ['name' => 'Mathis', 'year' => 2007, 'genre' => 'male'];

    /**
     * Données sous forme d'objet
     *
     * @var \stdClass
     */
    private $user;

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\DataPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        $this->user = new \stdClass();
        $this->user->name = 'Mathis';
        $this->user->year = 2007;
        $this->user->genre = 'males';
        return parent::makePdf(DataPdf::class, $withPage);
    }

    public function testSetData()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(PdfDoc::class, $pdf->setData($this->data));
    }

    public function testGetData()
    {
        $pdf = $this->makePdf()->setData($this->data);
        $this->assertEquals($this->data, $pdf->getData());
        $this->assertEquals($this->data['name'], $pdf->getData('name'));
        $this->assertFalse($pdf->getData('fake'));
    }

    public function testGetDataWithObject()
    {
        $pdf = $this->makePdf()->setData($this->user);
        $this->assertEquals($this->user, $pdf->getData());
        $this->assertEquals($this->user->name, $pdf->getData('name'));
        $this->assertFalse($pdf->getData('fake'));
    }

    public function testHasKey()
    {
        $pdf = $this->makePdf()->setData($this->data);
        $this->assertTrue($pdf->hasKey('name'));
    }

    public function testHasKeyWithObject()
    {
        $pdf = $this->makePdf()->setData($this->user);
        $this->assertTrue($pdf->hasKey('name'));
    }

    public function testHasValue()
    {
        $pdf = $this->makePdf()->setData($this->data);
        $this->assertTrue($pdf->hasValue(2007));
    }

    public function testHasValueWithObject()
    {
        $pdf = $this->makePdf()->setData($this->user);
        $this->assertTrue($pdf->hasValue(2007));
    }
}
