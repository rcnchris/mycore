<?php
/**
 * Fichier BookmarkPdfTrait.php du 15/02/2018
 * Description : Fichier de la classe BookmarkPdfTrait
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
 * Trait ColorsPdfTrait
 * <ul>
 * <li>Ajouter des signets hiérarchisables dans un document PDF</li>
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
trait BookmarkPdfTrait
{

    /**
     * Liste des signets
     *
     * @var array
     */
    private $outlines = [];

    /**
     * Sais pas...
     *
     * @var mixed
     */
    private $outlineRoot;

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
     * @return array|bool
     */
    public function getBookmarks($ind = null, $key = null)
    {
        if (is_null($ind)) {
            return $this->outlines;
        } elseif (array_key_exists($ind, $this->outlines)) {
            if (!is_null($key) && array_key_exists($key, $this->outlines[$ind])) {
                return $this->outlines[$ind][$key];
            }
            return $this->outlines[$ind];
        }
        return false;
    }

    /**
     * Ajoute un favoris au document
     *
     * ### Exemple
     * - `$pdf->addBookmark('Page' . $pdf->PageNo);`
     * - `$pdf->addBookmark('Titre 1', 1);`
     *
     * @param string   $label Label du favoris
     * @param int|null $level Niveau
     * @param int|null $y     Position dans le document
     *
     * @return void
     */
    public function addBookmark($label, $level = 0, $y = 0)
    {
        if ($y == -1) {
            $y = $this->GetY();
        }
        $this->outlines[] = [
            't' => $label,
            'l' => $level,
            'y' => ($this->h - $y) * $this->k,
            'p' => $this->PageNo()
        ];
    }

    /**
     * Ajoute les favoris
     *
     * @return $this|void
     */
    private function putBookmarks()
    {
        $nb = count($this->outlines);
        if ($nb == 0) {
            return;
        }
        $lru = [];
        $level = 0;
        foreach ($this->outlines as $i => $o) {
            if ($o['l'] > 0) {
                $parent = $lru[$o['l'] - 1];
                // Set parent and last pointers
                $this->outlines[$i]['parent'] = $parent;
                $this->outlines[$parent]['last'] = $i;
                if ($o['l'] > $level) {
                    // Level increasing: set first pointer
                    $this->outlines[$parent]['first'] = $i;
                }
            } else {
                $this->outlines[$i]['parent'] = $nb;
            }
            if ($o['l'] <= $level && $i > 0) {
                // Set prev and next pointers
                $prev = $lru[$o['l']];
                $this->outlines[$prev]['next'] = $i;
                $this->outlines[$i]['prev'] = $prev;
            }
            $lru[$o['l']] = $i;
            $level = $o['l'];
        }
        // Outline items
        $n = $this->n + 1;
        foreach ($this->outlines as $i => $o) {
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
        $this->outlineRoot = $this->n;
        $this->_put('<</Type /Outlines /First ' . $n . ' 0 R');
        $this->_put('/Last ' . ($n + $lru[0]) . ' 0 R>>');
        $this->_put('endobj');
        return $this;
    }

    /**
     * Ajoute les ressources... et exécute putBookmarks
     */
    protected function _putresources()
    {
        parent::_putresources();
        $this->putBookmarks();
    }

    /**
     * Ajoute les favoris au document
     */
    protected function _putcatalog()
    {
        parent::_putcatalog();
        if (count($this->outlines) > 0) {
            $this->_put('/Outlines ' . $this->outlineRoot . ' 0 R');
            $this->_put('/PageMode /UseOutlines');
        }
    }

    /**
     * Imprime les informations du trait
     */
    public function infosBookmarkPdfTrait()
    {
        $this->AddPage();
        $this->title('Signets', 1);
        $this->alert("Permet d'ajouter des favoris au document.");

        $this->printInfoClass(BookmarkPdfTrait::class);

        $this->title('addBookmark', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Ajouter un signet."));
        $this->codeBloc("\$pdf->addBookmark('Sous-Titre', 1);");
        $this->Ln();

        $this->title('getBookmarks', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Obtenir tous les signets."));
        $this->codeBloc("\$pdf->getBookmarks();");

        $this->MultiCell(0, 10, utf8_decode("Obtenir un signet."));
        $this->codeBloc("\$pdf->getBookmarks(3);");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getBookmarks(3)));

        $this->MultiCell(0, 10, utf8_decode("Obtenir la valeur d'une clé d'un signet."));
        $this->codeBloc("\$pdf->getBookmarks(3, 't');");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getBookmarks(3, 't')));
        $this->Ln();
    }
}
