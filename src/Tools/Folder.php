<?php
/**
 * Fichier Folder.php du 03/11/2017
 * Description : Fichier de la classe Folder
 *
 * PHP version 5
 *
 * @category Fichiers
 *
 * @package  Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Tools;

/**
 * Class Folder<br/>
 *
 * <ul>
 * <li>Facilite la manipulation de fichiers et dossiers.</li>
 * </ul>
 *
 * @category Fichiers
 *
 * @package  Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <0.0.1>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class Folder
{
    /**
     * Chemin
     *
     * @var string
     */
    public $path;

    /**
     * Contenu du dossier
     *
     * @var array
     */
    private $content = [
        'files' => [],
        'folders' => []
    ];

    /**
     * Instance du répertoire
     *
     * @var \Directory
     */
    private $dir;

    /**
     * Constructeur
     *
     * @param string $path
     *
     * @throws \Exception
     */
    public function __construct($path = __DIR__)
    {
        if (is_dir($path)) {
            $this->dir = dir($path);
            $this->path = $path;
        } elseif (is_file($path)) {
            $this->dir = dir(dirname($path));
            $this->path = dirname($path);
        } else {
            throw new \Exception('Paramètre invalide');
        }
    }

    /**
     * Fermeture de l'instance \Directory.
     *
     * @return bool
     */
    public function __destroy()
    {
        $this->dir->close();
        return true;
    }

    /**
     * Obtenir la taille du dossier
     *
     * @param bool|null $human Si true la valeur retournée est en byte,
     *                         sinon en kilo octets
     *
     * @return string|int
     */
    public function size($human = true)
    {
        $cmd = 'du -sh ' . $this->path;
        if ($human) {
            $cmd = $cmd . ' -sh ';
            $size = explode('/', shell_exec($cmd))[0];
            return trim($size);
        } else {
            $cmd = $cmd . ' -sb ';
            $size = explode('/', shell_exec($cmd))[0];
            return intval(trim($size));
        }
    }

    /**
     * Obtenir le contenu du dossier.
     *
     * @param string $key 'files', 'folders' ou les deux si null
     *
     * @return array
     */
    public function content($key = null)
    {
        while (false !== ($entry = $this->dir->read())) {
            if (is_file($this->path . DIRECTORY_SEPARATOR . $entry)) {
                $this->content['files'][] = $entry;
            } elseif (is_dir($this->path . DIRECTORY_SEPARATOR . $entry)) {
                $this->content['folders'][] = $entry;
            }
        }
        $this->content['folders'] = array_slice($this->content['folders'], 2);

        return $key
            ? $this->content[$key]
            : $this->content;
    }

    /**
     * Obtenir la liste des fichiers à la racine du dossier.
     *
     * @return mixed
     */
    public function files()
    {
        return $this->content('files');
    }

    /**
     * Obtenir les dossiers de la racine.
     *
     * @return array
     */
    public function folders()
    {
        return $this->content('folders');
    }

    /**
     * Vérifier la présence d'un fichier à la racine.
     *
     * @param string $name Nom du fichier
     *
     * @return bool
     */
    public function hasFile($name)
    {
        return in_array($name, $this->files(), true);
    }

    /**
     * Vérifier la présence d'un dossier à la racine.
     *
     * @param string $name Nom du dossier
     *
     * @return bool
     */
    public function hasFolder($name)
    {
        return in_array($name, $this->folders(), true);
    }

    /**
     * Obtenir la liste des extensions de l'instance
     *
     * @param null $name
     *
     * @return array
     */
    public function extensions($name = null)
    {
        $ret = [];
        foreach ($this->files() as $file) {
            $ret[] = substr($file, strpos($file, '.') + 1, strlen($file) - strpos($file, '.'));
        }
        return is_null($name)
            ? array_unique($ret)
            : in_array($name, $ret);
    }
}
