<?php
/**
 * Fichier Cdn.php du 19/07/2018
 * Description : Fichier de la classe Cdn
 *
 * PHP version 5
 *
 * @category CDN
 *
 * @package  Rcnchris\Core\Html
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Html;

use Rcnchris\Core\Tools\Items;

/**
 * Class Cdn
 *
 * @category CDN
 *
 * @package  Rcnchris\Core\Html
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Cdn extends Html
{
    /**
     * Liste des CDN
     *
     * @var \Rcnchris\Core\Tools\Items
     */
    private $items;

    /**
     * @param mixed $items Liste des CDN
     */
    public function __construct($items)
    {
        $this->items = new Items($items);
    }

    /**
     * Obtenir un CDN
     *
     * @param string $key Clé du CDN
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->items->get($key);
    }

    /**
     * Obtenir une clé des items
     *
     * @param string|null $key Clé à retourner
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function get($key = null)
    {
        return $this->items->get($key);
    }

    /**
     * Vérifier la présence d'une clé
     *
     * @param mixed $key Clé dont il faut vérifier la présence
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->items->has($key);
    }

    /**
     * Obtenir la balise `script` d'un CDN
     *
     * @param string      $key        Nom du CDN
     * @param string|null $type       Type de script (src ou min)
     * @param array|null  $attributes Attributs de la balise `script`
     *
     * @return null|string
     */
    public function script($key, $type = 'src', array $attributes = [])
    {
        if (!$this->has($key)) {
            return null;
        }
        $script = $this->get($key)->get('core.js')->get($type);
        if ($script) {
            $defaultAttributes = [
                'src' => $this->get($key)->get('path') . $script,
            ];
            return $this->surround(
                '',
                'script',
                array_merge($defaultAttributes, $attributes)
            );
        }
        return null;
    }

    /**
     * Obtenir la balise `link` pour le CSS d'un CDN
     *
     * @param string      $key        Nom du CDN
     * @param string|null $type       Type de css (src ou min)
     * @param array|null  $attributes Attributs de la balise `link`
     *
     * @return null|string
     */

    public function css($key, $type = 'src', array $attributes = [])
    {
        if (!$this->has($key)) {
            return null;
        }
        $css = $this->get($key)->get('core.css')->get($type);
        if ($css) {
            $defaultAttributes = [
                'rel' => 'stylesheet',
                'href' => $this->get($key)->get('path') . $css,
            ];
            return '<link' . $this->parseAttributes(array_merge($defaultAttributes, $attributes)) . '/>';
        }
        return null;
    }
}
