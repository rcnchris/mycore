<?php
/**
 * Fichier FileExtension.php du 06/01/2018
 * Description : Fichier de la classe FileExtension
 *
 * PHP version 5
 *
 * @category Twig
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Twig;

use SplFileInfo;

/**
 * Class FileExtension
 * <ul>
 * <li>Helper sur fichiers et dossiers</li>
 * </ul>
 *
 * @category Twig
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.1.0>
 */
class FileExtension extends \Twig_Extension
{
    /**
     * Obtenir la liste des filtres
     *
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('getFile', [$this, 'getFile'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('baseName', [$this, 'baseName'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('dirName', [$this, 'dirName'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('fileExtension', [$this, 'fileExtension'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('mime', [$this, 'getMime'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('isImage', [$this, 'isImage'], ['is_safe' => ['html']])
        ];
    }


    /**
     * Obtenir un fichier sous différentes formes
     *
     * @param string $path Chemin d'un fichier
     * @param string $to   (info, array, uploaded, text)
     *
     * @return array|\SplFileInfo|string|null
     */
    public function getFile($path, $to = 'info')
    {
        if (!is_file($path)) {
            return null;
        }
        switch ($to) {
            case 'info':
                return new SplFileInfo($path);
                break;
            case 'array':
                return file($path);
                break;
            case 'text':
                return file_get_contents($path);
                break;
            default:
                return new SplFileInfo($path);
                break;
        }
    }

    /**
     * Obtenir le type MIME d'un fichier
     * - Filtre
     *
     * @param string $path Chemion d'un fichier
     *
     * @return null|string
     */
    public function getMime($path)
    {
        if (is_file($path)) {
            return mime_content_type($path);
        }
        return null;
    }

    /**
     * Savoir si le fichier est une image
     * - Filtre
     *
     * @param string $path Chemin d'un fichier
     *
     * @return bool
     */
    public function isImage($path)
    {
        $mime = $this->getMime($path);
        $parts = explode('/', $mime);
        return $parts[0] === 'image';
    }

    /**
     * Obtenir uniquement le nom du fichier à partir d'un chemin.
     * - Filtre
     *
     * @param string $value Chemin avec fichier
     * @param string $sep   Séparateur de parties
     *
     * @return string
     */
    public function baseName($value, $sep = '/')
    {
        $parts = explode($sep, $value);
        return array_pop($parts);
    }

    /**
     * Obtenir uniquement le nom du chemin sans le fichier
     * - Filtre
     *
     * @param string $value Chemin avec nom de fichier
     *
     * @return string
     */
    public function dirName($value)
    {
        return dirname($value);
    }

    /**
     * Obtenir l'extension d'un fichier à partir d'un chemin
     * - Filtre
     *
     * @param string $value Emplacement ou nom d'un fichier avec une extension
     *
     * @return string
     */
    public function fileExtension($value)
    {
        $fileName = $this->baseName($value);
        $fileName = trim($fileName, '.');
        $partsName = explode('.', $fileName);
        return array_pop($partsName);
    }
}
