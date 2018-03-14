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
            new \Twig_SimpleFilter('baseName', [$this, 'baseName'])
            ,
            new \Twig_SimpleFilter('dirName', [$this, 'dirName'])
            ,
            new \Twig_SimpleFilter('fileExtension', [$this, 'fileExtension'])
        ];
    }

    /**
     * Obtenir uniquement le nom du fichier à partir d'un chemin.
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
