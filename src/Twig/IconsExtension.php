<?php
/**
 * Fichier IconsExtension.php du 19/10/2017
 * Description : Fichier de la classe IconsExtension
 *
 * PHP version 5
 *
 * @category Icônes
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
 * Class IconsExtension
 *
 * @category Icônes
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class IconsExtension extends \Twig_Extension
{

    /**
     * Obtenir la liste des filtres
     *
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('iconFile', [$this, 'iconFile'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Obtenir une icône de fichier selon une extension
     *
     * @param string $extension Extension de fichier
     *
     * @return string
     */
    public function iconFile($extension)
    {
        $html = $extension;
        $extension = strtolower($extension);
        if (in_array($extension, ['txt', 'csv', 'log'])) {
            $html = $this->icon('file-text-o');
        } elseif (in_array($extension, ['iso', 'rar', 'zip', 'gz'])) {
            $html = $this->icon('file-archive-o');
        } elseif (in_array($extension, ['pdf'])) {
            $html = $this->icon('file-pdf-o');
        } elseif (in_array($extension, ['xls', 'xlsx', 'csv'])) {
            $html = $this->icon('file-excel-o');
        } elseif (in_array($extension, ['doc', 'docx'])) {
            $html = $this->icon('file-word-o');
        } elseif (in_array($extension, ['ppt', 'pptx', 'odp'])) {
            $html = $this->icon('file-powerpoint-o');
        } elseif (in_array($extension, ['mp3', 'wav'])) {
            $html = $this->icon('file-audio-o');
        } elseif (in_array($extension, ['jpg', 'jpeg', 'jpe', 'bmp', 'png'])) {
            $html = $this->icon('file-picture-o');
        }
        return $html;
    }

    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('icon', [$this, 'icon'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Génère une icône
     *
     * @param string $name Nom de l'icône
     * @param string $lib  Librairie a utiliser
     *
     * @return string
     */
    public function icon($name, $lib = 'fa')
    {
        $html = '';
        $classParts = explode(' ', $name);
        $icon = $classParts[0];
        array_shift($classParts);
        if ($lib === 'fa') {
            $class = 'fa fa-' . $icon . ' ' . implode(' ', $classParts);
            $html = '<i class="' . trim($class) . '"></i>';
        }
        return $html;
    }
}
