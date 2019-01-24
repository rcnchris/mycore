<?php
/**
 * Fichier File.php du 22/01/2019
 * Description : Fichier de la classe File
 *
 * PHP version 5
 *
 * @category New
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Tools;

use Rcnchris\Core\Office\Office;

/**
 * Class File
 *
 * @category Fichiers et dossiers
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class File implements \Countable
{
    /**
     * Singleton
     *
     * @var self
     */
    private static $instance;

    /**
     * Chemin du fichier
     *
     * @var string
     */
    private $path;

    /**
     * Instance des informations du fichier
     *
     * @var \SplFileInfo
     */
    private $infos;

    /**
     * Retourne l'instance *Singleton* de cette classe.
     *
     * @staticvar Singleton $instance L'instance *Singleton* de la classe.
     *
     * @param string $path Chemin du fichier/dossier
     *
     * @return \Rcnchris\Core\Tools\File
     */
    public static function getInstance($path = null)
    {
        if (null === self::$instance || !is_null($path)) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }

    /**
     * Obtenir un objet approprié au type Mime
     *
     * @return bool|\PhpOffice\PhpPresentation\PhpPresentation|\PhpOffice\PhpProject\PhpProject|\PhpOffice\PhpSpreadsheet\Spreadsheet|\PhpOffice\PhpWord\PhpWord
     * @throws \Exception
     */
    public function getObjectFromMime()
    {
        switch ($this->getMime()) {
            case 'application/pdf':
                return (new Office())->read($this->path, 'RTF');
            case 'application/msword':
                return (new Office())->read($this->path, 'MsDoc');
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return (new Office())->read($this->path);
            case 'text/plain':
                return $this->getInfos();
            default:
                throw new \Exception(
                    'Le type MIME "' . $this->getMime()
                    . '", du fichier ' . $this->path
                    . '", est inconnu dans la méthode ' . __FUNCTION__
                    . ' de la classe ' . __CLASS__
                );
        }
    }

    /**
     * Obtenir le type Mime du fichier de l'instance
     *
     * @return string
     */
    public function getMime()
    {
        return mime_content_type($this->path);
    }

    /**
     * Vérifier s'il s'agît d'un dossier
     *
     * @return bool
     */
    public function isDir()
    {
        return $this->getInfos()->isDir();
    }

    /**
     * Constructeur non public afin d'éviter la création d'une nouvelle instance du *Singleton* via l'opérateur `new`
     *
     * @param string $path Chemin du fichier/dossier
     */
    protected function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Obtenir le contenu d'un fichier dans une chaîne de caractères
     *
     * @return string
     */
    public function getContent()
    {
        return file_get_contents($this->path);
    }

    /**
     * Obtenir le contenu d'un fichier dans un tableau
     *
     * @return array
     */
    public function toArray()
    {
        return file($this->path);
    }

    /**
     * Obtenir une ligne d'un fichier par son numéro
     *
     * @param int $lineNumber Numéro de ligne
     *
     * @return null|string
     */
    public function getLine($lineNumber)
    {
        $a = $this->toArray();
        if (array_key_exists($lineNumber, $a)) {
            return $a[$lineNumber];
        }
        return null;
    }

    /**
     * Obtenir les informations sur un fichier
     *
     * @return \SplFileInfo
     */
    public function getInfos()
    {
        if (is_null($this->infos)) {
            $this->infos = new \SplFileInfo($this->path);
        }
        return $this->infos;
    }


    /**
     * Obtenir le contenu au format texte
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->isDir()) {
            return '';
        }
        return $this->getContent();
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *       </p>
     *       <p>
     *       The return value is cast to an integer.
     */
    public function count()
    {
        return $this->isDir()
            ? count(array_slice(scandir($this->path), 2))
            : count($this->toArray());
    }
}
