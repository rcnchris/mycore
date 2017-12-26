<?php
/**
 * Fichier Composer.php du 14/11/2017
 * Description : Fichier de la classe Composer
 *
 * PHP version 5
 *
 * @category Tools
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

/**
 * Class Composer<br/>
 *
 * <ul>
 * <li>Facilite la lecture d'un fichier composer.json</li>
 * </ul>
 *
 * @category Tools
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <0.0.1>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class Composer
{

    /**
     * Contenu du fichier composer
     *
     * @var Collection
     */
    private $content;

    /**
     * Constructeur
     *
     * @param string $path
     *
     * @throws \Exception
     */
    public function __construct($path)
    {
        $this->content = new Collection(json_decode(file_get_contents($path), true), "Ficher $path");
    }

    /**
     * Obtenir la valeur d'une clé en appellant une propriété qui n'existe pas
     *
     * @param string $key
     *
     * @return Collection|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Obtenir la valeur d'une clé
     *
     * @param string $key
     *
     * @return Collection|null
     */
    public function get($key)
    {
        return $this->content->get($key);
    }

    /**
     * Obtenir la version de composer
     *
     * @return string
     */
    public function getVersion()
    {
        $ret = `composer --version`;
        $ret = trim(str_replace('Composer version ', '', $ret), "\n");
        return $ret;
    }

    /**
     * Obtenir le résultat de la commande 'composer show' dans un tableau
     *
     * @return array
     */
    public function show()
    {
        $res = `composer show`;
        $items = explode("\n", $res);
        $ret = [];
        foreach ($items as $item) {
            $parts = array_filter(explode(" ", $item));
            $package = array_shift($parts);
            $version = array_shift($parts);
            $desc = implode(' ', $parts);
            if ($package != '') {
                $ret[] = compact('package', 'version', 'desc');
            }
        }
        return $ret;
    }

    /**
     * Obtenir le contenu sous forme de tableau
     *
     * @return array
     */
    public function toArray()
    {
        return $this->content->toArray();
    }
}
