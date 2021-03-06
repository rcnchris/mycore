<?php
/**
 * Fichier TextExtension.php du 05/10/2017
 * Description : Fichier de la classe TextExtension
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

use Michelf\MarkdownExtra;
use Rcnchris\Core\Tools\Text;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Class TextExtension
 * <ul>
 * <li>Helper sur chaînes de caractères</li>
 * </ul>
 *
 * @category Twig
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
class TextExtension extends Twig_Extension
{

    /**
     * Obtenir la liste des filtres
     *
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('resume', [$this, 'resume'], ['is_safe' => ['html']]),
            new Twig_SimpleFilter('jsonDecode', [$this, 'jsonDecode'], ['is_safe' => ['html']]),
            new Twig_SimpleFilter('bitsSize', [$this, 'bitsSize'], ['is_safe' => ['html']]),
            new Twig_SimpleFilter('toSlug', [$this, 'toSlug'], ['is_safe' => ['html']]),
            new Twig_SimpleFilter('markdown', [$this, 'markdown'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Obtenir le slug d'une chaîne de caractères
     *
     * @codeCoverageIgnore
     *
     * @param $value
     *
     * @return string
     */
    public function toSlug($value)
    {
        return Text::slug($value, ['replacement' => '-']);
    }

    /**
     * Renvoie un extrait
     *
     * @param string   $content
     * @param int|null $maxLength
     *
     * @return string
     */
    public function resume($content = null, $maxLength = 100)
    {
        if (is_null($content)) {
            return '';
        }
        if (mb_strlen($content) > $maxLength) {
            $excerpt = mb_substr($content, 0, $maxLength);
            $lastSpaces = mb_strrpos($excerpt, ' ');
            $excerpt = mb_substr($excerpt, 0, $lastSpaces) . '...';
            return $excerpt;
        }
        return $content;
    }

    /**
     * Obtenir un tableau à partir d'une chaîne au format json
     *
     * @param string $value Chaîne de caractère au format json
     *
     * @return array|bool
     */
    public function jsonDecode($value)
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }
        return false;
    }

    /**
     * Obtenir une taille en bits
     * - Filtre
     *
     * @param     $value
     * @param int $round
     *
     * @return string
     */
    public static function bitsSize($value, $round = 0)
    {
        if (!is_numeric($value)) {
            $value = 0;
        }
        $sizes = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];
        for ($i = 0; $value > 1024 && $i < count($sizes) - 1; $i++) {
            $value /= 1024;
        }
        return round($value, $round) . $sizes[$i];
    }

    /**
     * Retourne un contenu HTML à partir d'une source en Markdown
     *
     * @param string $value Contenu Markdown
     *
     * @return string
     */
    public function markdown($value)
    {
        return MarkdownExtra::defaultTransform($value);
    }
}
