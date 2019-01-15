<?php
namespace Core\PDF;

use Psr\Http\Message\ResponseInterface;
use Rcnchris\Core\PDF\Wkhtmltopdf;
use Tests\Rcnchris\BaseTestCase;

class WkhtmltopdfTest extends BaseTestCase
{
    /**
     * @param $options
     *
     * @return \Rcnchris\Core\PDF\Wkhtmltopdf
     */
    public function makePdf($options = null)
    {
        return new Wkhtmltopdf($options);
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Wkhtmltopdf');
        $this->assertInstanceOf(Wkhtmltopdf::class, $this->makePdf());
    }

    public function testHelp()
    {
        $this->assertHasHelp($this->makePdf());
    }

    public function testEnabled()
    {
        $this->assertTrue($this->makePdf()->wkhtmltopdfEnabled());
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->makePdf()->getVersion());
    }

    public function testGetOptions()
    {
        $this->assertNotEmpty($this->makePdf()->getOptions());
    }

    public function testGetOptionsWithKey()
    {
        $this->assertNotEmpty('A4', $this->makePdf()->getOptions('page-size'));
    }

    public function testGetOptionsWithValueKeyLess()
    {
        $this->assertEquals('disable-smart-shrinking', $this->makePdf()->getOptions('disable-smart-shrinking'));
    }

    public function testGetOptionsWithMissingKey()
    {
        $this->assertNull($this->makePdf()->getOptions('fake'));
    }

    public function testHasOptions()
    {
        $this->assertTrue($this->makePdf()->hasOption('page-size'));
        $this->assertTrue($this->makePdf()->hasOption('disable-smart-shrinking'));
        $this->assertFalse($this->makePdf()->hasOption('fake'));
    }

    public function testWithCss()
    {
        $pdf = $this->makePdf();
        $css = $this->rootPath() . '/public/css/pdf.css';
        $this->assertInstanceOf(Wkhtmltopdf::class, $pdf->withCss($css));
        $this->assertTrue($pdf->hasOption('user-style-sheet'));
    }

    public function testWithWrongFileCss()
    {
        $pdf = $this->makePdf();
        $css = '/fake/css/pdf.css';
        $this->assertInstanceOf(Wkhtmltopdf::class, $pdf->withCss($css));
        $this->assertFalse($pdf->hasOption('user-style-sheet'));
    }

    public function testSetOption()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(Wkhtmltopdf::class, $pdf->setOption('header-line'));
        $this->assertTrue($pdf->hasOption('header-line'));
    }

    public function testAddPage()
    {
        $this->assertInstanceOf(
            Wkhtmltopdf::class,
            $this->makePdf()->addPage('<html><h1>Oyé les gens !</h1></html>')
        );
    }

    public function testAddPageWithPath()
    {
        $this->assertInstanceOf(
            Wkhtmltopdf::class,
            $this->makePdf()->addPage('/')
        );
    }

    public function testGetDocWithoutParameter()
    {
        $this->assertInternalType('string', $this->makePdf()->getDoc());
    }

    public function testGetDocHelp()
    {
        $this->assertInternalType('string', $this->makePdf()->getDoc('help'));
    }

    public function testGetDocExtendedHelp()
    {
        $this->assertInternalType('string', $this->makePdf()->getDoc('extended-help'));
    }

    public function testGetDocReadme()
    {
        $this->assertInternalType('string', $this->makePdf()->getDoc('readme'));
    }

    public function testGetDocHtmlDoc()
    {
        $this->assertInternalType('string', $this->makePdf()->getDoc('htmldoc'));
    }

    public function testGetDocManpage()
    {
        $this->assertInternalType('string', $this->makePdf()->getDoc('manpage'));
    }

    public function testRender()
    {
        $response = $this
            ->makePdf()
            ->addPage('<h1>Oyé les gens</h1>')
            ->render();
        $this->assertInstanceOf(ResponseInterface::class, $response);
        if ($response->getStatusCode() !== 200) {
            $this->ekoMessage((string)$response->getBody());
        }
    }

    public function testDownload()
    {
        $file = $this->rootPath() . $this::TESTS_FOLDER . '/Core/PDF/results/oye-test.pdf';
        $response = $this
            ->makePdf()
            ->addPage('<h1>Oyé les gens</h1>')
            ->download(null, [], $file);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        if ($response->getStatusCode() !== 200) {
            $this->ekoMessage((string)$response->getBody());
        }
    }
}
