<?php
/**
 * Fichier RessourcesPdfTrait.php du 26/04/2018
 * Description : Fichier de la classe RessourcesPdfTrait
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
 * Class RessourcesPdfTrait
 * <ul>
 * <li>Gestion des signets</li>
 * <li>Gestion des fichiers joints</li>
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
trait RessourcesPdfTrait
{

    /**
     * Liste des signets
     *
     * @var array
     */
    private $bookmarks = [];

    /**
     * Sais pas...
     *
     * @var mixed
     */
    private $bookmarkRoot;

    /**
     * Forcer l'affichage du panneau latéral des fichiers attachés
     *
     * @var bool
     */
    private $joinedPane = false;

    /**
     * Tableau des fichiers attachés
     *
     * @var array
     */
    private $joinedFiles = array();

    /**
     * Nombre de fichiers
     *
     * @var int
     */
    private $nFiles;

    /**
     * Ajoute les différentes ressources si besoin
     */
    protected function _putresources()
    {
        parent::_putresources();

        if (!empty($this->bookmarks)) {
            $this->putBookmarks();
        }

        if (!empty($this->joinedFiles)) {
            $this->putJoinedFiles();
        }
    }

    /**
     * Ajoute les ressources au catalogues
     */
    protected function _putcatalog()
    {
        parent::_putcatalog();

        /**
         * Bookmarks
         */
        if (count($this->bookmarks) > 0) {
            $this->_put('/Outlines ' . $this->bookmarkRoot . ' 0 R');
            $this->_put('/PageMode /UseOutlines');
        }

        /**
         * Fichiers joints
         */
        if (!empty($this->joinedFiles)) {
            $this->_put('/Names <</EmbeddedFiles ' . $this->nFiles . ' 0 R>>');
        }
        if ($this->joinedPane) {
            $this->_put('/PageMode /UseAttachments');
        }
    }

    /**
     * Obtenir la liste des bookmarks
     *
     * ### Exemple
     * - `$pdf->getBookmarks();`
     * - `$pdf->getBookmarks(2);`
     *
     * @param int|null    $ind Indice du tableau des favoris
     * @param string|null $key Clé du favori à retourner
     *
     * @return array|mixed|bool
     */
    public function getBookmarks($ind = null, $key = null)
    {
        if (is_null($ind)) {
            return $this->bookmarks;
        } elseif (array_key_exists($ind, $this->bookmarks)) {
            if (!is_null($key) && array_key_exists($key, $this->bookmarks[$ind])) {
                return $this->bookmarks[$ind][$key];
            }
            return $this->bookmarks[$ind];
        }
        return false;
    }

    /**
     * Ajoute un signet au document
     *
     * ### Exemple
     * - `$pdf->addBookmark('Page' . $pdf->PageNo);`
     * - `$pdf->addBookmark('Titre 1', 1);`
     *
     * @param string   $label Label du favoris
     * @param int|null $level Niveau
     * @param int|null $y     Position dans le document
     *
     * @return $this
     */
    public function addBookmark($label, $level = 0, $y = -1)
    {
        if ($y == -1) {
            $y = $this->GetY();
        }
        $this->bookmarks[] = [
            't' => $label,
            'l' => $level,
            'y' => ($this->h - $y) * $this->k,
            'p' => $this->PageNo()
        ];

        return $this;
    }

    /**
     * Obtenir le niveau max des signets
     *
     * @return int
     */
    public function getBookmarksMaxLevel()
    {
        if (empty($this->bookmarks)) {
            return 0;
        } else {
            $max = array_column($this->bookmarks, 'l');
            return max($max);
        }
    }

    /**
     * Ajoute les signets à la liste des signets
     *
     * @return void
     */
    private function putBookmarks()
    {
        $nb = count($this->bookmarks);
        $lru = [];
        $level = 0;
        foreach ($this->bookmarks as $i => $o) {
            if ($o['l'] > 0) {
                $parent = $lru[$o['l'] - 1];
                // Set parent and last pointers
                $this->bookmarks[$i]['parent'] = $parent;
                $this->bookmarks[$parent]['last'] = $i;
                if ($o['l'] > $level) {
                    // Level increasing: set first pointer
                    $this->bookmarks[$parent]['first'] = $i;
                }
            } else {
                $this->bookmarks[$i]['parent'] = $nb;
            }
            if ($o['l'] <= $level && $i > 0) {
                // Set prev and next pointers
                $prev = $lru[$o['l']];
                $this->bookmarks[$prev]['next'] = $i;
                $this->bookmarks[$i]['prev'] = $prev;
            }
            $lru[$o['l']] = $i;
            $level = $o['l'];
        }
        // Outline items
        $n = $this->n + 1;
        foreach ($this->bookmarks as $i => $o) {
            $this->_newobj();
            $this->_put('<</Title ' . $this->_textstring($o['t']));
            $this->_put('/Parent ' . ($n + $o['parent']) . ' 0 R');
            if (isset($o['prev'])) {
                $this->_put('/Prev ' . ($n + $o['prev']) . ' 0 R');
            }
            if (isset($o['next'])) {
                $this->_put('/Next ' . ($n + $o['next']) . ' 0 R');
            }
            if (isset($o['first'])) {
                $this->_put('/First ' . ($n + $o['first']) . ' 0 R');
            }
            if (isset($o['last'])) {
                $this->_put('/Last ' . ($n + $o['last']) . ' 0 R');
            }
            $this->_put(sprintf('/Dest [%d 0 R /XYZ 0 %.2F null]', $this->PageInfo[$o['p']]['n'], $o['y']));
            $this->_put('/Count 0>>');
            $this->_put('endobj');
        }
        // Outline root
        $this->_newobj();
        $this->bookmarkRoot = $this->n;
        $this->_put('<</Type /Outlines /First ' . $n . ' 0 R');
        $this->_put('/Last ' . ($n + $lru[0]) . ' 0 R>>');
        $this->_put('endobj');
    }

    /**
     * Fichiers joints
     */

    /**
     * Forcer l'affichage du panneau des fichiers attachés
     *
     * @param bool $view Afficher le panneau latéral ?
     *
     * @return $this
     */
    public function setJoinedPane($view = true)
    {
        $this->joinedPane = $view;
        return $this;
    }

    /**
     * Attache un fichier au document PDF
     *
     * ### Exemple
     * - `$pdf->attach('path/to/file/filename');`
     *
     * @param string      $file Emplacement du fichier
     * @param string|null $name Nom du fichier dans le document
     * @param string|null $desc Description
     * @param bool|null   $isUTF8
     *
     * @return $this
     */
    public function joinFile($file, $name = '', $desc = '', $isUTF8 = false)
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
        $this->joinedFiles[] = array('file' => $file, 'name' => $name, 'desc' => $desc);
        return $this;
    }

    /**
     * Ajoute les fichiers attachés au document
     */
    private function putJoinedFiles()
    {
        $s = '';
        foreach ($this->joinedFiles as $i => $info) {
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
        $this->nFiles = $this->n;
        $this->_put('<<');
        $this->_put('/Names [' . $s . ']');
        $this->_put('>>');
        $this->_put('endobj');
    }

    /**
     * INDEX
     */

    /**
     * Crée une page d'index à partir des bookmarks
     */
    public function createIndex()
    {
        //Index title
        $this->SetFontSize(20);
        $this->Cell(0, 5, 'Index', 0, 1, 'C');
        $this->SetFontSize(15);
        $this->Ln(10);

        $size = sizeof($this->bookmarks);
        $pageCellSize = $this->GetStringWidth('p. ' . $this->bookmarks[$size - 1]['p']) + 2;

        for ($i = 0; $i < $size; $i++) {
            // Offset
            $level = $this->bookmarks[$i]['l'];
            if ($level > 0) {
                $this->Cell($level * 8);
            }

            // Caption
            $str = utf8_decode($this->bookmarks[$i]['t']);
            $strsize = $this->GetStringWidth($str);
            $avail_size = $this->w - $this->lMargin - $this->rMargin - $pageCellSize - ($level * 8) - 4;
            while ($strsize >= $avail_size) {
                $str = substr($str, 0, -1);
                $strsize = $this->GetStringWidth($str);
            }
            $this->Cell($strsize + 2, $this->FontSize + 2, $str);

            // Filling dots
            $w = $this->w - $this->lMargin - $this->rMargin - $pageCellSize - ($level * 8) - ($strsize + 2);
            $nb = $w / $this->GetStringWidth('.');
            $dots = str_repeat('.', $nb);
            $this->Cell($w, $this->FontSize + 2, $dots, 0, 0, 'R');

            // Page number
            $this->Cell($pageCellSize, $this->FontSize + 2, 'p. ' . $this->bookmarks[$i]['p'], 0, 1, 'R');
        }
    }
}
