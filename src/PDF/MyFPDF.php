<?php
/**
 * Fichier MyFPDF.php du 24/01/2018
 * Description : Fichier de la classe MyFPDF
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF;

require dirname(dirname(__DIR__)) . '/vendor/fpdf/fpdf.php';

/**
 * Class MyFPDF
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class MyFPDF extends \FPDF
{
    /**
     * Colonne courante
     *
     * @var int
     */
    public $col = 0;

    /**
     * Nombre de colonnes définies
     *
     * @var int
     */
    public $colNb = 1;

    /**
     * Largeur d'une colonne
     *
     * @var int
     */
    public $colWidth = 0;

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
     * Options par défaut d'un document
     *
     * @var array
     */
    private $defaultOptions = [
        'orientation' => 'P',
        'unit' => 'mm',
        'format' => 'A4',
        'marges' => [
            'top' => 10,
            'bottom' => 10,
            'left' => 10,
            'right' => 10
        ]
    ];

    /**
     * Types d'écriture
     *
     * @var array
     */
    private $writeTypes = ['text', 'fill', 'draw'];

    /**
     * Constructeur
     *
     * ### Exemple
     * - `$pdf = new MyFPDF();`
     * - `$pdf = new MyFPDF(['orientation' => 'L', 'format' => 'A3']);`
     *
     * @param array $options Options du document
     */
    public function __construct(array $options = [])
    {
        $options = array_merge($this->defaultOptions, $options);
        parent::__construct($options['orientation'], $options['unit'], $options['format']);

        $this->AddPage($this->getOrientation());
        $this->AliasNbPages();

        $this->setMargin($this->defaultOptions['marges']);
        $this->SetFont(current($this->getFonts()));
    }

    /**
     * Définir les marges du document
     *
     * ### Exemple
     * - `$pdf->setMargin('top', 10);`
     * - `$pdf->setMargin(['top' => 10, 'bottom' => 10]);`
     *
     * @param string|array $types Type de marge
     * @param int|null     $value Valeur de la marge
     *
     * @return void
     */
    public function setMargin($types, $value = 10)
    {
        if (is_string($types) && array_key_exists($types, $this->defaultOptions['marges'])) {
            if (strtolower($types) === 'bottom') {
                $this->bMargin = $value;
            } else {
                $method = 'Set' . ucfirst($types) . 'Margin';
                parent::$method($value);
            }
        } elseif (is_array($types)) {
            foreach ($types as $type => $value) {
                if (strtolower($type) === 'bottom') {
                    $this->bMargin = $value;
                } else {
                    $method = 'Set' . ucfirst($type) . 'Margin';
                    parent::$method($value);
                }
            }
        }
    }

    /**
     * Obtenir les marges du document ou l'une d'entre elles
     *
     * ### Exemple
     * - `$pdf->getMagin();`
     * - `$pdf->getMagin('top');`
     *
     * ### Type
     * - top
     * - left
     * - right
     * - bottom
     *
     * @param string|null $type Type de marge
     *
     * @return array|mixed|bool
     */
    public function getMargin($type = null)
    {
        $margins = [
            'top' => $this->tMargin,
            'left' => $this->lMargin,
            'right' => $this->rMargin,
            'bottom' => $this->bMargin,
            'cell' => $this->cMargin
        ];
        if (is_null($type)) {
            return $margins;
        } elseif (array_key_exists($type, $margins)) {
            return $margins[$type];
        } else {
            return false;
        }
    }

    /**
     * Obtenir la taille d'un élémént
     *
     * ### Exemple
     * - `$pdf->getWidth('line');`
     *
     * ### Types
     * - line, lastCell
     *
     * @param string $type type d'élément
     *
     * @return mixed
     */
    public function getWidth($type)
    {
        $types = [
            'line' => $this->LineWidth,
            'lastCell' => $this->lasth
        ];
        if (array_key_exists($type, $types)) {
            return $types[$type];
        }
        return false;
    }

    /**
     * Définir une clé et sa valeur dans les meta données du document
     *
     * @param string     $key   Nom de la meta donnée
     * @param mixed|null $value Valeur de la meta donnée
     *
     * @return $this
     */
    public function setMetadata($key, $value = null)
    {
        if (!is_null($this->metadata)) {
            $this->metadata[$key] = $value;
        } else {
            $this->metadata = [$key => $value];
        }
        return $this;
    }

    /**
     * Obtenir les meta données ou la valeur de l'une d'entre elle
     *
     * @param string|null $name Nom de la clé à retourner
     *
     * @return array|bool
     */
    public function getMetadata($name = null)
    {
        if (!is_null($this->metadata)) {
            if (is_null($name)) {
                return $this->metadata;
            } elseif (array_key_exists($name, $this->metadata)) {
                return utf8_decode($this->metadata[$name]);
            }
        }
        return false;
    }

    /**
     * Obtenir la taille du document
     *
     * ### Exemple
     * - `$pdf->getBodySize('width');`
     * - `$pdf->getBodySize('height');`
     *
     * @param string|null $type Type de taille
     *
     * @return int
     */
    public function getBodySize($type = null)
    {
        $sizes = [
            'width' => intval($this->GetPageWidth() - ($this->getMargin('left') + $this->getMargin('right'))),
            'height' => intval($this->GetPageHeight() - ($this->getMargin('top') + $this->getMargin('bottom')))
        ];
        if (!is_null($type) && array_key_exists(strtolower($type), $sizes)) {
            return $sizes[$type];
        }
        return $sizes;
    }

    /**
     * Obtenir la taille du document
     *
     * ### Exemple
     * - `$pdf->getDocSize('width');`
     * - `$pdf->getDocSize('height');`
     *
     * ### Type
     * - width
     * - height
     *
     * @param string|null $type Type de taille
     *
     * @return bool|int
     */
    public function getDocSize($type = null)
    {
        $sizes = [
            'width' => intval($this->GetPageWidth()),
            'height' => intval($this->GetPageHeight())
        ];
        if (!is_null($type) && array_key_exists(strtolower($type), $sizes)) {
            return $sizes[$type];
        }
        return $sizes;
    }

    /**
     * Obtenir la police courante
     *
     * @return string
     */
    public function getFont()
    {
        return $this->FontFamily;
    }

    /**
     * Obtenir la liste des polices disponibles
     *
     * @return array
     */
    public function getFonts()
    {
        return $this->CoreFonts;
    }

    /**
     * Obtenir le chemin des polices
     *
     * @return string
     */
    public function getFontsPath()
    {
        return $this->fontpath;
    }

    /**
     * Obtenir le nombre total de pages
     *
     * @return int
     */
    public function getTotalPages()
    {
        return count($this->pages);
    }

    /**
     * Obtenir l'orientation du document
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->CurOrientation;
    }

    /**
     * Obtenir le style courant de la police
     *
     * @return string
     */
    public function getFontStyle()
    {
        return $this->FontStyle;
    }

    /**
     * Obtenir la taille de la police courante dans l'unité du document
     *
     * @return mixed
     */
    public function getFontSize()
    {
        return $this->FontSizePt;
    }

    /**
     * Obtenir la taille de la police courante dans l'unité du document
     *
     * @return mixed
     */
    public function getFontSizeInUnit()
    {
        return $this->FontSize;
    }

    /**
     * Défnir la couleur du texte, du trait ou du remplissage
     *
     * @param string|null $hexaColor
     * @param string|null $type
     *
     * @return bool
     */
    public function setColor($hexaColor = '#000000', $type = 'text')
    {
        $rgb = $this->hexaToRgb($hexaColor);
        if (in_array($type, $this->writeTypes)) {
            $method = 'Set' . ucfirst($type) . 'Color';
            parent::$method($rgb['r'], $rgb['g'], $rgb['b']);
        }
        return true;
    }

    public function getColor($type = 'text')
    {
        if (in_array($type, $this->writeTypes)) {
            $property = ucfirst($type) . 'Color';
            return $this->$property;
        }
        return false;
    }

    /**
     * Vérifie si le style de la police courant est souligné
     *
     * @return bool
     */
    public function isUnderline()
    {
        return $this->underline;
    }

    /**
     * Obtenir l'ordonnée du saut de page
     *
     * @return int
     */
    public function getPageBreak()
    {
        return intval($this->PageBreakTrigger);
    }

    /**
     * Ajoute une ligne horizontale sur toute la largeur de la page
     *
     * @param int|null $ln Saut de ligne après la ligne
     *
     * @return $this
     */
    public function addLine($ln = 0)
    {
        //$this->setCol(0);
        $this->Line($this->GetX(), $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
        if (!is_null($ln)) {
            $this->Ln(intval($ln));
        }
        return $this;
    }

    /**
     * Ajoute le pied répété sur chaque page
     */
    public function Footer()
    {
        $this->SetY($this->getMargin('bottom') * -1);
        $this->addLine();
        $this->SetFont($this->getFont(), 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' sur ' . '{nb}', 0, 0, 'C');
        return true;
    }

    /**
     * Ajoute un favoris au document
     *
     * @param string   $content Label du favoris
     * @param int|null $level   Niveau
     * @param int|null $y       Position dans le document
     *
     * @return $this
     */
    public function addBookmark($content, $level = 0, $y = 0)
    {
        if ($y == -1) {
            $y = $this->GetY();
        }
        $this->outlines[] = [
            't' => $content,
            'l' => $level,
            'y' => ($this->h - $y) * $this->k,
            'p' => $this->PageNo()
        ];
        return $this;
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
     * Obtenir les valeurs RGB d'une couleur à partir de son code hexadécimal
     *
     * @param string $c Code hexadécimal d'une couleur (#000000)
     *
     * @return array
     * @throws \Exception
     */
    private function hexaToRgb($c)
    {
        $c = strtolower($c);
        if ($c[0] != '#' || strlen($c) != 7) {
            $this->Error('Incorrect color: ' . $c);
        }
        return [
            'r' => hexdec(substr($c, 1, 2))
            , 'g' => hexdec(substr($c, 3, 2))
            , 'b' => hexdec(substr($c, 5, 2))
        ];
    }
}
