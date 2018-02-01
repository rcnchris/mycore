<?php
/**
 * Fichier Composer.php du 30/01/2018
 * Description : Fichier de la classe Composer
 *
 * PHP version 5
 *
 * @category Composer
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

use ArrayIterator;

/**
 * Class Composer
 * <ul>
 * <li>Facilite la manipulation d'un fichier composer.json</li>
 * </ul>
 *
 * @category Composer
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class Composer implements \IteratorAggregate, \ArrayAccess
{

    /**
     * Contenu du fichier
     *
     * @var array
     */
    private $content = [];

    /**
     * Nom complet du fichier
     *
     * @var string
     */
    private $fileName;

    /**
     * Constructeur
     *
     * ### Exemple
     * - `$composer = new Composer('/path/to/file/composer.json');`
     *
     * @param string $fileName Nom complet du fichier
     *
     * @throws \Exception
     */
    public function __construct($fileName)
    {
        if (file_exists($fileName)) {
            $this->fileName = $fileName;
            $content = json_decode(file_get_contents($fileName), true);
            if (is_array($content)) {
                foreach ($content as $key => $value) {
                    $this->content[$key] = $value;
                }
            }
        } else {
            throw new \Exception("Le fichier $fileName est introuvable");
        }
    }

    /**
     * Obtenir les clés du contenu
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->content);
    }

    /**
     * Vérifier la présence d'une clé dans le contenu
     *
     * ### Exemple
     * - `$composer->has('description');`
     *
     * @param string $key Nom de la clé
     *
     * @return bool
     */
    public function has($key)
    {
        return in_array($key, $this->keys());
    }

    /**
     * Obtenir le contenur d'une clé
     *
     * ### Exemple
     * - `$composer->get('description');`
     *
     * @param string $key Nom de la clé
     *
     * @return mixed|bool
     */
    public function get($key)
    {
        if ($this->has($key)) {
            return $this->content[$key];
        }
        return false;
    }

    /**
     * Obtenir la valeur d'une clé de content
     * dans le cas où la clé demandée n'existe pas
     *
     * @param string $key Nom de la clé
     *
     * @return mixed|bool
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Obtenir le contenu au format json
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->content);
    }

    /**
     * Obtenir la valeur d'une clé de content
     * dans le cas où la méthode demandée n'existe pas.
     * Possibilité de demander une ou plusieurs sous-clés présentent dans la valeur de <code>$key</code>.
     *
     * ### Exemple
     * - `$composer->require();`
     * - `$composer->require('php');`
     *
     * @param string     $key  Nom de la clé
     * @param array|null $args Nom de la clé de la valeur de la première clé
     *
     * @return bool|mixed
     */
    public function __call($key, array $args = [])
    {
        if (empty($args)) {
            return $this->get($key);
        } else {
            $content = $this->get($key);
            if (is_array($content)) {
                $ret = [];
                foreach ($args as $k => $values) {
                    if (is_string($values) && array_key_exists($values, $content)) {
                        $ret[$values] = $content[$values];
                    } elseif (is_array($values)) {
                        foreach ($values as $value) {
                            $ret[$value] = $content[$value];
                        }
                    } else {
                        return false;
                    }
                }
                if (count($ret) === 1) {
                    return current($ret);
                }
                return $ret;
            }
            return $content;
        }
    }

    /**
     * Obtenir le contenu sous forme de tableau
     *
     * ### Exemple
     * - `$composer->toArray();`
     * - `$composer->toArray('require');`
     *
     * @param string|null $key Nom de la clé
     *
     * @return array|string
     */
    public function toArray($key = null)
    {
        if (is_null($key)) {
            return $this->getIterator()->getArrayCopy();
        }
        return $this->$key;
    }

    /**
     * Obtenir le nombre de clés du contenu
     *
     * @return int
     */
    public function count()
    {
        return $this->getIterator()->count();
    }

    /**
     * Obtenir la taille d'une librairie
     *
     * ### Exemple
     * - `$composer->getSizeOf('intervention/image')`
     *
     * @param string $library Nom d'une librairie présente dans require ou require-dev
     *
     * @return string|bool
     */
    public function getSizeOf($library = null)
    {
        $dirPath = $this->getRequirePath($library);
        if (is_dir($dirPath)) {
            $cmd = 'du -sh ' . $dirPath;
            return explode("\t", shell_exec($cmd))[0];
        }
        return false;
    }

    /**
     * Obtenir une instance de Composer
     * à partir du fichier composer.json d'une librairie
     *
     * @param string $library Nom d'une librairie requise
     *
     * @return bool|self
     */
    public function getComposerOf($library)
    {
        $composer = $this->getRequirePath($library) . DIRECTORY_SEPARATOR . 'composer.json';
        if (file_exists($composer)) {
            return new self($composer);
        }
        return false;
    }

    /**
     * Obtenir la liste des requires
     *
     * ### Exemple
     * - `$composer->getRequires();`
     * - `$composer->getRequires('dev');`
     *
     * @param string|null $type
     *
     * @return array|bool
     */
    public function getRequires($type = 'all')
    {
        $requires = [
            'req' => $this->get('require')
            , 'dev' => $this->get('require-dev')
        ];
        if ($type === 'req') {
            return $requires['req'];
        } elseif ($type === 'dev') {
            return $requires['dev'];
        } elseif ($type === 'all') {
            return $requires;
        }
        return false;
    }

    /**
     * Obtenir le chemin du racine du fichier
     *
     * @param string $library Nom d'une librairie présente dans require ou require-dev
     *
     * @return string
     */
    private function getRequirePath($library)
    {
        return dirname($this->fileName) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . $library;
    }

    /**
     * Obtenir une instance de ArrayIterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->content);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception('Pas possible pour le moment');
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @throws \Exception
     */
    public function offsetUnset($offset)
    {
        throw new \Exception('Pas possible pour le moment');
    }
}