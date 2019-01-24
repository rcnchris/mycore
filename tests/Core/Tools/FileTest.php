<?php
namespace Tests\Rcnchris\Core\Tools;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpWord\PhpWord;
use Rcnchris\Core\Tools\File;
use Tests\Rcnchris\BaseTestCase;

class FileTest extends BaseTestCase
{
    public function makeFile($path)
    {
        return File::getInstance($path);
    }

    public function testInstance()
    {
        $this->ekoTitre('Tools - File');
        $this->assertInstanceOf(File::class, $this->makeFile(__FILE__));
    }

    public function testGetMimeWithPhpFile()
    {
        $this->assertEquals('text/x-php', $this->makeFile(__FILE__)->getMime());
    }

    public function testGetMimeWithDir()
    {
        $this->assertEquals('directory', $this->makeFile($this->rootPath())->getMime());
    }

    public function testIsDir()
    {
        $this->assertTrue($this->makeFile(__DIR__)->isDir());
        $this->assertFalse($this->makeFile(__FILE__)->isDir());
    }

    public function testGetContent()
    {
        $this->assertInternalType('string', $this->makeFile(__FILE__)->getContent());
    }

    public function testGetContentWithDir()
    {
        $this->assertEquals('', $this->makeFile(__DIR__)->getContent());
    }

    public function testGetContentWithUrl()
    {
        $this->assertInternalType('string', $this->makeFile('http://localhost/')->getContent());
    }

    public function testGetContentToArray()
    {
        $this->assertInternalType('array', $this->makeFile(__FILE__)->toArray());
    }

    public function testGetContentToArrayWithUrl()
    {
        $this->assertInternalType('array', $this->makeFile('http://localhost/')->toArray());
    }

    public function testGetLine()
    {
        $this->assertInternalType('string', $this->makeFile(__FILE__)->getLine(66));
    }

    public function testGetLineMissing()
    {
        $this->assertNull($this->makeFile(__FILE__)->getLine(99999));
    }

    public function testMagicToString()
    {
        $this->assertInternalType('string', (string)$this->makeFile(__FILE__));
    }

    public function testMagicToStringWithDir()
    {
        $this->assertInternalType('string', (string)$this->makeFile(__DIR__));
    }

    public function testGetObjectWithDoc()
    {
        $this->assertInstanceOf(
            PhpWord::class,
            $this
                ->makeFile($this->rootPath() . '/tests/Core/Office/files/sample.doc')
                ->getObjectFromMime()
        );
    }

    public function testGetObjectWithDocx()
    {
        $this->assertInstanceOf(
            PhpWord::class,
            $this
                ->makeFile($this->rootPath() . '/tests/Core/Office/files/sample.docx')
                ->getObjectFromMime()
        );
    }

    public function testGetObjectWithPdf()
    {
        $this->assertInstanceOf(
            PhpWord::class,
            $this
                ->makeFile($this->rootPath() . '/tests/Core/Office/files/sample.pdf')
                ->getObjectFromMime()
        );
    }

    public function testGetObjectWithXls()
    {
        $this->assertInstanceOf(
            Spreadsheet::class,
            $this
                ->makeFile($this->rootPath() . '/tests/Core/Office/files/sample.xls')
                ->getObjectFromMime()
        );
    }

    public function testGetObjectWithXlsx()
    {
        $this->assertInstanceOf(
            Spreadsheet::class,
            $this
                ->makeFile($this->rootPath() . '/tests/Core/Office/files/sample.xlsx')
                ->getObjectFromMime()
        );
    }

    public function testGetObjectWithPpt()
    {
        $this->assertInstanceOf(
            PhpPresentation::class,
            $this
                ->makeFile($this->rootPath() . '/tests/Core/Office/files/sample.ppt')
                ->getObjectFromMime()
        );
    }

    public function testGetObjectWithPptx()
    {
        $this->assertInstanceOf(
            PhpPresentation::class,
            $this
                ->makeFile($this->rootPath() . '/tests/Core/Office/files/sample.pptx')
                ->getObjectFromMime()
        );
    }

    public function testGetObjectWithGan()
    {
        $this->expectException(\Exception::class);
        $this
            ->makeFile($this->rootPath() . '/tests/Core/Office/files/sample.gan')
            ->getObjectFromMime();
    }

    public function testGetObjectWithText()
    {
        $this->assertInstanceOf(
            \SplFileInfo::class,
            $this
                ->makeFile($this->rootPath() . '/composer.json')
                ->getObjectFromMime()
        );
    }

    public function testCountableImplements()
    {
        $this->assertInternalType('integer', $this->makeFile($this->rootPath() . '/tests/Core/')->count());
        $this->assertEquals(7, $this->makeFile($this->rootPath() . '/.htaccess')->count());
    }
}
