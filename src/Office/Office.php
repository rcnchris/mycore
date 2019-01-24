<?php
/**
 * Fichier Office.php du 22/01/2019
 * Description : Fichier de la classe Office
 *
 * PHP version 5
 *
 * @category Office
 *
 * @package  Rcnchris\Core\Office
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Office;

use PhpOffice\Common\File;
use PhpOffice\PhpPresentation\DocumentProperties as PresentationMetas;
use PhpOffice\PhpPresentation\IOFactory as PresentationFactory;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Writer\PowerPoint2007 as PresentationWriter;
use PhpOffice\PhpProject\DocumentProperties as ProjectMetas;
use PhpOffice\PhpProject\IOFactory as ProjectFactory;
use PhpOffice\PhpProject\PhpProject;
use PhpOffice\PhpProject\Writer\GanttProject as ProjectWriter;
use PhpOffice\PhpSpreadsheet\Document\Properties as TableurMetas;
use PhpOffice\PhpSpreadsheet\IOFactory as TableurFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as TableurReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as TableurWriter;
use PhpOffice\PhpWord\IOFactory as DocumentFactory;
use PhpOffice\PhpWord\Metadata\DocInfo as DocumentMetas;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\RTF as DocumentReaderRtf;
use PhpOffice\PhpWord\Reader\Word2007 as DocumentReader2007;
use PhpOffice\PhpWord\Writer\PDF as DocumentWriterPdf;
use PhpOffice\PhpWord\Writer\Word2007 as DocumentWriter2007;
use Rcnchris\Core\Tools\Items;

/**
 * Class Office
 *
 * @category Office
 *
 * @package  Rcnchris\Core\Office
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 * @see      https://github.com/PHPOffice
 * @see      https://phpspreadsheet.readthedocs.io/en/develop/
 * @see      https://phpword.readthedocs.io/en/latest/
 * @see      https://phppresentation.readthedocs.io/en/latest/
 * @see      https://phpproject.readthedocs.io/en/latest/
 * @see      https://github.com/PHPOffice/PHPVisio
 */
class Office
{
    /**
     * Liste des métadonnées
     *
     * @var \Rcnchris\Core\Tools\Items
     */
    private $metas;

    /**
     * Noms des propriétés natives communes à tous les documents
     *
     * @var array
     */
    private $nativesDocMetasKeys = [
        'title',
        'subject',
        'category',
        'description',
        'keywords',
        'creator',
        'manager',
        'company',
        'created',
        'modified',
    ];

    /**
     * Constructeur
     *
     * @param array $metas Métadonnées des documents à créer
     */
    public function __construct(array $metas = [])
    {
        $this->setMetas($metas);
    }

    /**
     * Obtenir les métadonnées
     *
     * @return Items
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * Définir les métadonnées de l'insance Office
     *
     * @param array $metas Tableau des métadonnées natives et customs
     *
     * @return self
     */
    public function setMetas(array $metas)
    {
        $this->metas = new Items($metas);
        return $this;
    }

    /**
     * Définir les métadonnées d'un objet à partir des métas de cette instance
     *
     * @param PhpPresentation|PhpProject|Spreadsheet|PhpWord $object Objet dont il faut définir les métadonnées
     *
     * @return PhpPresentation|PhpProject|Spreadsheet|PhpWord
     */
    private function setDocumentPropertiesWithMetas($object)
    {
        foreach ($this->metas as $metaKey => $metaValue) {
            $setMethodName = 'set' . ucfirst($metaKey);
            $getPropertiesMethodName = $this->getMetasMethodName($object);
            if (method_exists($object->$getPropertiesMethodName(), $setMethodName)) {
                $object->$getPropertiesMethodName()->$setMethodName($metaValue);
            } elseif (strtolower($metaKey) === 'customs' && is_array($metaValue)) {
                if (method_exists($object->$getPropertiesMethodName(), 'setCustomProperty')) {
                    foreach ($metaValue as $customKey => $customValue) {
                        $object->$getPropertiesMethodName()->setCustomProperty($customKey, $customValue);
                    }
                }
            }
        }
        return $object;
    }

    /**
     * Obtenir le nom de la méthode appropriée pour obtenir les propriétés d'un objet
     *
     * @param PhpPresentation|PhpProject|Spreadsheet|PhpWord $object Objet dont il faut retourner le nom de la méthode
     *                                                               qui permet d'accéder aux métadonnées
     *
     * @return bool|TableurMetas|DocumentMetas|PresentationMetas|ProjectMetas
     */
    private function getMetasMethodName($object)
    {
        $mapMethods = [
            Spreadsheet::class => 'getProperties',
            PhpWord::class => 'getDocInfo',
            PhpPresentation::class => 'getDocumentProperties',
            PhpProject::class => 'getProperties'
        ];
        return $mapMethods[get_class($object)];
    }

    /**
     * Obtenir tous les métas d'un objet (natives and customs)
     *
     * @param PhpPresentation|PhpProject|Spreadsheet|PhpWord $object Objet dont il faut retourner les métadonnées
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getObjectAllMetas($object)
    {
        $methodGetPropertiesName = $this->getMetasMethodName($object);
        $metas = [];
        foreach ($this->nativesDocMetasKeys as $keyName) {
            $methodGetMetaName = 'get' . ucfirst($keyName);
            if (method_exists($object->$methodGetPropertiesName(), $methodGetMetaName)) {
                $metas[$keyName] = $object->$methodGetPropertiesName()->$methodGetMetaName();
            }
        }
        if (method_exists($object->$methodGetPropertiesName(), 'getCustomProperties')) {
            foreach ($object->$methodGetPropertiesName()->getCustomProperties() as $customKeyName) {
                $metas[$customKeyName] = $object->$methodGetPropertiesName()->getCustomPropertyValue($customKeyName);
            }
        }
        return new Items($metas);
    }

    /**
     * Obtenir une instance d'un tableur
     *
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    public function makeTableur()
    {
        return $this->setDocumentPropertiesWithMetas(new Spreadsheet());
    }

    /**
     * Obtenir une instance d'un document
     *
     * @return \PhpOffice\PhpWord\PhpWord
     */
    public function makeWord()
    {
        return $this->setDocumentPropertiesWithMetas(new PhpWord());
    }

    /**
     * Obtenir une instance d'une présentation
     *
     * @return \PhpOffice\PhpPresentation\PhpPresentation
     */
    public function makePresentation()
    {
        return $this->setDocumentPropertiesWithMetas(new PhpPresentation());
    }

    /**
     * Obtenir une instance d'un projet
     *
     * @return \PhpOffice\PhpProject\PhpProject
     */
    public function makeProject()
    {
        return $this->setDocumentPropertiesWithMetas(new PhpProject());
    }

    /**
     * Obtenir l'instance d'un document office à partir de l'emplacement d'un fichier
     *
     * @param string      $fileName Nom du fichier
     * @param string|null $reader   Nom du reader au format texte (MsDoc, RTF...)
     *
     * @return bool|PhpPresentation|PhpProject|Spreadsheet|PhpWord
     * @throws \Exception
     */
    public function read($fileName, $reader = null)
    {
        if (!File::fileExists($fileName)) {
            return false;
        }
        return $this->makeReader($fileName, $reader);
    }

    /**
     * Obtenir le reader approprié au type mime envoyé
     *
     * @param string      $fileName Nom d'un fichier Office
     * @param string|null $reader   Reader souhaité
     *
     * @return PhpPresentation|PhpProject|Spreadsheet|PhpWord
     * @throws \Exception
     */
    private function makeReader($fileName, $reader = null)
    {
        $mime = mime_content_type($fileName);
        switch ($mime) {
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return TableurFactory::load($fileName);
            case 'application/msword':
                return DocumentFactory::load($fileName, is_null($reader) ? 'MsDoc' : $reader);
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return DocumentFactory::load($fileName, is_null($reader) ? 'Word2007' : $reader);
            case 'application/pdf':
                return DocumentFactory::load($fileName, is_null($reader) ? 'RTF' : $reader);
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                return PresentationFactory::load($fileName);
            case 'application/xml':
                return ProjectFactory::load($fileName);
            default:
                throw new \Exception(
                    'Le type MIME "' . $mime
                    . '", du fichier ' . $fileName
                    . '", est inconnu dans la méthode ' . __FUNCTION__
                    . ' de la classe ' . __CLASS__
                );
        }
    }

    /**
     * Sauvegarder un objet dans un fichier
     *
     * @param Spreadsheet|PhpWord|PhpPresentation|PhpProject $object   Objet à sauvegarder
     * @param string                                         $fileName Nom du fichier
     *
     * @return $this
     */
    public function save($object, $fileName)
    {
        $writer = $this->getWriter($object);
        $writer->save($fileName);
        return $this;
    }

    /**
     * Obtenir le writer approprié à l'objet
     *
     * @param Spreadsheet|PhpWord|PhpPresentation|PhpProject $object Objet dont il faut retourner le Writer
     *
     * @return TableurWriter|DocumentWriter2007|PresentationWriter|ProjectWriter
     */
    private function getWriter($object)
    {
        $mapDoc = [
            Spreadsheet::class => TableurWriter::class,
            PhpWord::class => DocumentWriter2007::class,
            PhpPresentation::class => PresentationWriter::class,
            PhpProject::class => ProjectWriter::class,
        ];
        $writerClass = $mapDoc[get_class($object)];
        return new $writerClass($object);
    }
}
