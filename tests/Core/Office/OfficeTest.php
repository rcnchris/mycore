<?php
namespace Tests\Rcnchris\Core\Office;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpProject\PhpProject;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpWord\PhpWord;
use Rcnchris\Core\Office\Office;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class OfficeTest extends BaseTestCase
{
    /**
     * @var array
     */
    protected $metas = [
        'title' => 'Oyé les gens',
        'company' => 'MRC Consulting',
        'creator' => 'Raoul CHRISMANN',
        'category' => 'Tests unitaires',
        'subject' => "On test la génération d'un document Office avec du PHP",
        'description' => 'Avec la librairie <code>PHPOffice/PhpSpreadsheet</code>',
        'keywords' => 'php,document,office,tableur,document,présentation,projet',
        'customs' => [
            'Service' => 'Financier',
            'Contact' => 'Le copain'
        ]
    ];

    /**
     * Chemin des fichiers d'exemple pour la lecture
     *
     * @var string
     */
    protected $pathSampleFiles = __DIR__ . '/files';

    /**
     * @param array $metas
     *
     * @return \Rcnchris\Core\Office\Office
     */
    public function makeOffice(array $metas = [])
    {
        $metas = array_merge($this->metas, $metas);
        return new Office($metas);
    }

    public function testInstance()
    {
        $this->ekoTitre('Office - Office');
        $this->assertInstanceOf(Office::class, $this->makeOffice());
    }

    public function testGetMetas()
    {
        $office = $this->makeOffice();
        $this->assertInstanceOf(Items::class, $office->getMetas());
        $this->assertEquals($this->metas['creator'], $office->getMetas()->creator);
        $this->assertEquals($this->metas['customs']['Service'], $office->getMetas()->customs->Service);
    }

    public function testReadMissingDoc()
    {
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/fake.xlsx';
        $this->assertFalse($office->read($fileName));
    }

    public function testReadXlsDoc()
    {
        $this->ekoMessage('Lire un tableur XLS');
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/sample.xls';
        $doc = $office->read($fileName);
        $this->assertInstanceOf(Spreadsheet::class, $doc);
    }

    public function testReadXlsxDoc()
    {
        $this->ekoMessage('Lire un tableur XLSX');
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/sample.xlsx';
        $doc = $office->read($fileName);
        $this->assertInstanceOf(Spreadsheet::class, $doc);
    }

    public function testReadDocWithReaderRTF()
    {
        $this->ekoMessage('Lire un document DOC au format RTF');
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/sample.doc';
        $doc = $office->read($fileName, 'RTF');
        $this->assertInstanceOf(PhpWord::class, $doc);
    }

    public function testReadDocDoc()
    {
        $this->ekoMessage('Lire un document DOC');
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/sample.doc';
        $doc = $office->read($fileName);
        $this->assertInstanceOf(PhpWord::class, $doc);
    }

    public function testReadDocxDoc()
    {
        $this->ekoMessage('Lire un document DOCX');
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/sample.docx';
        $doc = $office->read($fileName);
        $this->assertInstanceOf(PhpWord::class, $doc);
    }

    public function testReadPdfDoc()
    {
        $this->ekoMessage('Lire un document PDF');
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/sample.pdf';
        $this->assertInstanceOf(PhpWord::class, $office->read($fileName));
    }

    public function testReadPptDoc()
    {
        $this->ekoMessage('Lire une présentation PPT');
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/sample.ppt';
        $this->assertInstanceOf(PhpPresentation::class, $office->read($fileName));
    }

    public function testReadPptxDoc()
    {
        $this->ekoMessage('Lire une présentation PPTX');
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/sample.pptx';
        $this->assertInstanceOf(PhpPresentation::class, $office->read($fileName));
    }

    public function testReadGanttDoc()
    {
        $this->ekoMessage('Lire un projet GANTT');
        $office = $this->makeOffice();
        $fileName = $this->pathSampleFiles . '/sample.gan';
        $this->assertInstanceOf(PhpProject::class, $office->read($fileName));
    }

    public function testReadNotOfficeFile()
    {
        $this->expectException(\Exception::class);
        $this->makeOffice()->read(__FILE__);
    }

    public function testMakeTableur()
    {
        $this->ekoMessage('Génération d\'un tableur');
        $office = $this->makeOffice();
        $tableur = $office->makeTableur();
        $this->assertInstanceOf(Spreadsheet::class, $tableur);
        $this->assertEquals($this->metas['title'], $tableur->getProperties()->getTitle());
        $this->assertEquals(
            $this->metas['customs']['Service'],
            $tableur->getProperties()->getCustomPropertyValue('Service')
        );
        $this->assertEquals(
            $this->metas['customs']['Service'],
            $office->getObjectAllMetas($tableur)->get('Service')
        );
    }

    public function testMakeWord()
    {
        $this->ekoMessage('Génération d\'un document');
        $office = $this->makeOffice();
        $word = $office->makeWord();
        $this->assertInstanceOf(PhpWord::class, $word);
        $this->assertEquals($this->metas['title'], $word->getDocInfo()->getTitle());
        $this->assertEquals(
            $this->metas['customs']['Service'],
            $word->getDocInfo()->getCustomPropertyValue('Service')
        );
        $this->assertEquals(
            $this->metas['customs']['Service'],
            $office->getObjectAllMetas($word)->get('Service')
        );
    }

    public function testMakePresentation()
    {
        $this->ekoMessage('Génération d\'une présentation');
        $office = $this->makeOffice(['Subject' => __FUNCTION__]);
        $presentation = $office->makePresentation();
        $this->assertInstanceOf(PhpPresentation::class, $presentation);
        $this->assertEquals(
            $this->metas['title'],
            $presentation->getDocumentProperties()->getTitle()
        );
        $this->assertEquals(
            $this->metas['title'],
            $office->getObjectAllMetas($presentation)->get('title')
        );
    }

    public function testMakeProject()
    {
        $this->ekoMessage('Génération d\'un projet');
        $office = $this->makeOffice(['Subject' => __FUNCTION__]);
        $project = $office->makeProject();
        $this->assertInstanceOf(PhpProject::class, $project);
        $this->assertEquals(
            $this->metas['title'],
            $project->getProperties()->getTitle()
        );
        $this->assertEquals(
            $this->metas['customs']['Service'],
            $office->getObjectAllMetas($project)->get('Service')
        );
    }

    public function testSaveTableurXlsx()
    {
        $this->ekoMessage('Sauvegarde d\'un tableur XLSX');
        $fileName = __DIR__ . '/results/test_office_save.xlsx';
        if (is_file($fileName)) {
            unlink($fileName);
        }
        $office = $this->makeOffice(['subject' => __FUNCTION__]);
        $tableur = $office->makeTableur();
        $tableur->getActiveSheet()->setCellValue('A1', __FUNCTION__);
        $office->save($tableur, $fileName);
        $this->assertTrue(is_file($fileName));
    }

    public function testSaveWordDocx()
    {
        $this->ekoMessage('Sauvegarde d\'un document DOCX');
        $fileName = __DIR__ . '/results/test_office_save.docx';
        if (is_file($fileName)) {
            unlink($fileName);
        }
        $office = $this->makeOffice(['subject' => __FUNCTION__]);
        $word = $office->makeWord();
        $word->addSection()->addText(__FUNCTION__);
        $office->save($word, $fileName);
        $this->assertTrue(is_file($fileName));
    }

    public function testSaveWordDoc()
    {
        $this->ekoMessage('Sauvegarde d\'un document DOC');
        $fileName = __DIR__ . '/results/test_office_save.doc';
        if (is_file($fileName)) {
            unlink($fileName);
        }
        $office = $this->makeOffice(['subject' => __FUNCTION__]);
        $word = $office->makeWord();
        $word->addSection()->addText(__FUNCTION__);
        $office->save($word, $fileName);
        $this->assertTrue(is_file($fileName));
    }

    public function testSavePresentationPpt()
    {
        $this->ekoMessage('Sauvegarde d\'une présentation PPT');
        $fileName = __DIR__ . '/results/test_office_save.ppt';
        if (is_file($fileName)) {
            unlink($fileName);
        }
        $office = $this->makeOffice(['subject' => __FUNCTION__]);
        $presentation = $office->makePresentation();
        $presentation->getActiveSlide()->createRichTextShape()->createTextRun(__FUNCTION__);
        $office->save($presentation, $fileName);
        $this->assertTrue(is_file($fileName));
    }

    public function testSavePresentationPptx()
    {
        $this->ekoMessage('Sauvegarde d\'une présentation PPTX');
        $fileName = __DIR__ . '/results/test_office_save.pptx';
        if (is_file($fileName)) {
            unlink($fileName);
        }
        $office = $this->makeOffice(['subject' => __FUNCTION__]);
        $presentation = $office->makePresentation();
        $presentation->getActiveSlide()->createRichTextShape()->createTextRun(__FUNCTION__);
        $office->save($presentation, $fileName);
        $this->assertTrue(is_file($fileName));
    }

    public function testSaveProjectGan()
    {
        $this->ekoMessage('Sauvegarde d\'un projet GAN');
        $fileName = __DIR__ . '/results/test_office_save.gan';
        if (is_file($fileName)) {
            unlink($fileName);
        }
        $office = $this->makeOffice(['subject' => __FUNCTION__]);
        $project = $office->makeProject();
        $ressource = $project->createResource();
        $ressource->setTitle($this->metas['creator']);
        $task = $project->createTask();
        $task->setName('Test unitaire ' . __FUNCTION__);
        $task->setStartDate(date('d-m-Y'));
        $task->setEndDate(date('d-m-Y'));
        $task->setProgress(0.5);
        $task->addResource($ressource);
        $office->save($project, $fileName);
        $this->assertTrue(is_file($fileName));
    }
}
