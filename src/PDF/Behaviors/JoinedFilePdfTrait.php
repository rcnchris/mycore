<?php
/**
 * Fichier JoinedFilePdfTrait.php du 25/02/2018
 * Description : Fichier de la classe JoinedFilePdfTrait
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF\Behaviors;

/**
 * Trait JoinedFilePdfTrait
 * <ul>
 * <li>Permet de joindre des fichiers au PDF</li>
 * </ul>
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF\Behaviors
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
trait JoinedFilePdfTrait
{

    /**
     * Tableau des fichiers attachés
     *
     * @var array
     */
    protected $files = array();

    /**
     * Nombre de fichiers
     *
     * @var int
     */
    protected $n_files;

    /**
     * Forcer l'affichage du panneau latéral des fichiers attachés
     *
     * @var bool
     */
    protected $attachPane = false;

    /**
     * Attache un fichier au document PDF
     *
     * @param string      $file Emplacement du fichier
     * @param string|null $name Nom du fichier dans le document
     * @param string|null $desc Description
     * @param bool|null   $isUTF8
     */
    public function attach($file, $name = '', $desc = '', $isUTF8 = false)
    {
        if ($name == '') {
            $p = strrpos($file, '/');
            if ($p === false) {
                $p = strrpos($file, '\\');
            }

            $name = $p !== false
                ? substr($file, $p + 1)
                : $file;
        }
        if (!$isUTF8) {
            $desc = utf8_encode($desc);
        }
        $this->files[] = array('file' => $file, 'name' => $name, 'desc' => $desc);
    }

    /**
     * Ajoute les fichiers attachés au document
     */
    protected function _putfiles()
    {
        $s = '';
        foreach ($this->files as $i => $info) {
            $file = $info['file'];
            $name = $info['name'];
            $desc = $info['desc'];

            $fc = file_get_contents($file);
            if ($fc === false) {
                $this->Error('Cannot open file: ' . $file);
            }
            $md = @date('YmdHis', filemtime($file));

            $this->_newobj();
            $s .= $this->_textstring(sprintf('%03d', $i)) . ' ' . $this->n . ' 0 R ';
            $this->_put('<<');
            $this->_put('/Type /Filespec');
            $this->_put('/F (' . $this->_escape($name) . ')');
            $this->_put('/UF ' . $this->_textstring(utf8_encode($name)));
            $this->_put('/EF <</F ' . ($this->n + 1) . ' 0 R>>');
            if ($desc) {
                $this->_put('/Desc ' . $this->_textstring($desc));
            }
            $this->_put('>>');
            $this->_put('endobj');

            $this->_newobj();
            $this->_put('<<');
            $this->_put('/Type /EmbeddedFile');
            $this->_put('/Length ' . strlen($fc));
            $this->_put("/Params <</ModDate (D:$md)>>");
            $this->_put('>>');
            $this->_putstream($fc);
            $this->_put('endobj');
        }
        $this->_newobj();
        $this->n_files = $this->n;
        $this->_put('<<');
        $this->_put('/Names [' . $s . ']');
        $this->_put('>>');
        $this->_put('endobj');
    }

    /**
     * Forcer l'affichage du panneau des fichiers attachés
     *
     * @param bool $view
     */
    public function setAttachPane($view = true)
    {
        $this->attachPane = $view;
    }

    /**
     * Ajoute les ressources au document
     */
    protected function _putresources()
    {
        parent::_putresources();
        if (!empty($this->files)) {
            $this->_putfiles();
        }
    }

    /**
     * Ajoute le catalogue au document
     */
    protected function _putcatalog()
    {
        parent::_putcatalog();
        if (!empty($this->files)) {
            $this->_put('/Names <</EmbeddedFiles ' . $this->n_files . ' 0 R>>');
        }
        if ($this->attachPane) {
            $this->_put('/PageMode /UseAttachments');
        }
    }
}
