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

use Psr\Http\Message\ResponseInterface;
use Rcnchris\Core\Tools\Myvar;

require dirname(__DIR__) . '/Ext/fpdf/fpdf.php';

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
     * Liste des orientations possibles
     *
     * @var array
     */
    private $orientations = ['P', 'L'];

    /**
     * Liste des unités de mesures possibles
     *
     * @var array
     */
    private $units = ['pt', 'mm', 'cm', 'in'];

    /**
     * Types d'écriture
     *
     * @var array
     */
    private $writeTypes = ['text', 'fill', 'draw'];

    /**
     * Liste des formats possibles
     *
     * @var array
     */
    private $formats = ['A3', 'A4', 'A5', 'Letter', 'Legal'];

    /**
     * Options du document
     *
     * @var array
     */
    private $options = [];

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
        $this->parseOptions(array_merge($this->defaultOptions, $options));
        parent::__construct($this->options['orientation'], $this->options['unit'], $this->options['format']);

        $this->AddPage($this->getOrientation());
        $this->AliasNbPages();

        $this->setMargin($this->options['marges']);
        $this->SetFont(current($this->getFonts()));
    }

    /**
     * Obtenir le document au format string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->Output('S');
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
     * - `$pdf->getMargin();`
     * - `$pdf->getMargin('top');`
     *
     * ### Type
     * - top
     * - left
     * - right
     * - bottom
     * - cell
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
     * Obtenir les corrdonées du curseur
     *
     * ### Exemple
     * - `$pdf->getCursor();`
     * - `$pdf->getCursor('y');`
     *
     * @param string|null $type x ou y
     *
     * @return array|float
     */
    public function getCursor($type = null)
    {
        if (is_null($type)) {
            return [
                'x' => $this->GetX()
                ,
                'y' => $this->GetY()
            ];
        } else {
            $method = 'Get' . strtoupper($type);
            return $this->$method();
        }
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
     * ### Exemple
     * - `$pdf->setColor();`
     * - `$pdf->setColor('#123456');`
     * - `$pdf->setColor('#123456', 'draw');`
     *
     * @param string|null $hexaColor Code héxadécimal d'une couleur
     * @param string|null $type      (text, fill ou draw)
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

    /**
     * Obtenir la couleur d'un type d'écriture
     *
     * @param string|null $type text, fill ou draw
     *
     * @return string|bool
     */
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
     * @param int|null $ln Sauts de ligne après la ligne
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
     * Obtenir la liste des bookmarks
     *
     * @return array
     */
    public function getBookmarks()
    {
        return $this->outlines;
    }

    /**
     * Ajoute un favoris au document
     *
     * @param string   $content Label du favoris
     * @param int|null $level   Niveau
     * @param int|null $y       Position dans le document
     *
     * @return void
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
    }

    /**
     * Obtenir les options du document
     *
     * ### Exemple
     * - `$pdf->getOptions();`
     * - `$pdf->getOptions('unit');`
     *
     * @param string|null $key Nom de l'option à retourner
     *
     * @return array|bool
     */
    public function getOptions($key = null)
    {
        if (is_null($key)) {
            return $this->options;
        } elseif ($this->hasOption($key)) {
            return $this->options[$key];
        }
        return false;
    }

    /**
     * Vérifie la présence d'une option dans la liste
     *
     * @param string $name Nom de l'option
     *
     * @return bool
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * Définir une option ou un ensemble d'options
     *
     * @param string|array $key
     * @param mixed|null   $value
     */
    public function setOptions($key, $value = null)
    {
        if (is_string($key)) {
            $this->options[$key] = $value;
        } elseif (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->options[$k] = $v;
            }
        }
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
            'r' => hexdec(substr($c, 1, 2)),
            'g' => hexdec(substr($c, 3, 2)),
            'b' => hexdec(substr($c, 5, 2))
        ];
    }

    /**
     * Imprime dans le document les informations de debug
     *
     * @return self
     */
    public function addDebug()
    {
        // Ajout de page si c'est la première
        !$this->getTotalPages() == 0 ?: $this->AddPage();

        // Titre
        $this->addBookmark('Page ' . $this->PageNo(), 0, -1);
        $this->SetFont($this->getFont(), 'B', 16);
        $this->getMetadata('Title') ?: $this->SetTitle('Debug');
        $this->Cell(0, 10, $this->getMetadata('Title'), 1, 1, 'C');

        // Date
        $this->Ln();
        $this->addBookmark('Date', 1, -1);
        $this->SetFont($this->getFont(), 'B', 14);
        $this->Cell(50, 5, 'Le ' . date('d-m-Y H:i'), 0, 1, 'L');

        // Tailles
        $this->Ln();
        $this->addBookmark('Tailles', 1, -1);
        $this->SetFont($this->getFont(), 'B', 14);
        $this->Cell(50, 5, 'Tailles du document', 0, 1, 'LU');

        $this->SetFont($this->getFont(), '', 10);
        $this->Cell(50, 5, 'Largeur de la page : ' . $this->getDocSize('width'), 0, 1, 'L');
        $this->Cell(50, 5, 'Longueur de la page : ' . $this->getDocSize('height'), 0, 1, 'L');
        $this->Cell(50, 5, 'Largeur du corps : ' . $this->getBodySize('width'), 0, 1, 'L');
        $this->Cell(50, 5, 'Longueur du corps : ' . $this->getBodySize('height'), 0, 1, 'L');

        // Pagination
        $this->Ln();
        $this->addBookmark('Pagination', 1, -1);
        $this->SetFont($this->getFont(), 'B', 14);
        $this->Cell(50, 5, 'Pagination', 0, 1, 'LU');

        $this->SetFont($this->getFont(), '', 10);
        $this->Cell(50, 5, 'Page courante : ' . $this->PageNo(), 0, 1, 'L');
        $this->Cell(50, 5, 'Nombre total de page(s) : ' . $this->getTotalPages(), 0, 1, 'L');
        $this->Cell(50, 5, utf8_decode('Saut de page à : ') . $this->getPageBreak(), 0, 1, 'L');

        // Colonnes
        $this->Ln();
        $this->addBookmark('Colonnes', 1, -1);
        $this->SetFont($this->getFont(), 'B', 14);
        $this->Cell(50, 5, 'Colonnes', 0, 1, 'LU');

        $this->SetFont($this->getFont(), '', 10);
        $this->Cell(0, 5, 'Nombre de colonnes : ' . $this->colNb, 0, 1, 'L');
        $this->Cell(0, 5, 'Largeur d\'une colonne : ' . $this->colWidth, 0, 1, 'L');
        $this->Cell(0, 5, 'Colonne courante : ' . $this->col, 0, 1, 'L');

        // Metadonnées
        $this->Ln();
        $this->addBookmark('Meta données', 1, -1);
        $this->SetFont($this->getFont(), 'B', 14);
        $this->Cell(50, 5, utf8_decode('Meta données'), 0, 1, 'LU');
        $this->SetFont($this->getFont(), '', 10);
        foreach ($this->getMetadata() as $name => $meta) {
            $this->Cell(50, 5, $name . ' : ' . $meta, 0, 1, 'L');
        }

        // Options
        $this->Ln();
        $this->addBookmark('Options du document', 1, -1);
        $this->SetFont($this->getFont(), 'B', 14);
        $this->Cell(50, 5, utf8_decode('Options du document'), 0, 1, 'LU');
        $this->SetFont($this->getFont(), '', 10);
        foreach ($this->getOptions() as $name => $opt) {
            if (!is_array($opt)) {
                $this->Cell(50, 5, $name . ' : ' . $opt, 0, 1, 'L');
            }
        }

        // Objet
        $this->Ln();
        $this->addBookmark('Objet', 1, -1);
        $this->SetFont($this->getFont(), 'B', 14);
        $this->Cell(50, 5, utf8_decode('Objet'), 0, 1, 'LU');
        $this->SetFont($this->getFont(), '', 10);
        $o = new Myvar($this);
        $this->Cell(0, 5, 'Classe : ' . get_class($this), 0, 1, 'L');
        $this->Cell(0, 5, 'Parent : ' . $o->getParent(), 0, 1, 'L');
        $this->Cell(0, 5, 'Interfaces : ' . count($o->getImplements()), 0, 1, 'L');
        $this->Cell(0, 5, 'Traits : ' . count($o->getTraits()), 0, 1, 'L');
        $this->Cell(0, 5, 'Méthodes : ' . count($o->getMethods()), 0, 1, 'L');
        $this->Cell(0, 5, 'Propriétés publiques : ' . count($o->getProperties()), 0, 1, 'L');

        $this->Ln();
        $this->addLine();

        return $this;
    }

    /**
     * Sauvegarder le document PDF dans un fichier
     *
     * ### Exemple
     * - `$pdf->toFile();`
     * - `$pdf->toFile('/path/to/file');`
     *
     * @param string|null $dest Emplacement et nom du fichier sans l'extension
     *
     * @return string
     */
    public function toFile($dest = null)
    {
        if (is_null($dest)) {
            $dest = 'MyCore_doc_' . date('Y-m-d-H-i');
        }
        return $this->Output('F', trim($dest) . '.pdf');
    }

    /**
     * Télécharger le document PDF via le navigateur
     *
     * ### Exemple
     * - `$pdf->toDownload($response, 'ola');`
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string|null                         $fileName Nom du fichier sans l'extension
     *
     * @return string
     */
    public function toDownload(ResponseInterface $response, $fileName = 'doc')
    {
        $fileName = trim($fileName) . '.pdf';
        $body = $response->getBody();
        $body->write((string)$this);
        $newResponse = $response
            ->withStatus(200)
            ->withHeader('Content-type', 'application/x-download')
            ->withAddedHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->withAddedHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->withAddedHeader('Pragma', 'public')
            ->withBody($body);
        return $newResponse;
    }

    /**
     * Voir le document dans le navigateur
     *
     * ### Exemple
     * - `$pdf->toView($response, 'ola');`
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string|null                         $fileName Nom du fichier PDF à générer
     * @param bool|null                           $isUtf8   Le contenu est en utf-8
     *
     * @return string|static
     */
    public function toView(ResponseInterface $response, $fileName = 'doc', $isUtf8 = true)
    {
        $fileName = trim($fileName) . '.pdf';
        $body = $response->getBody();
        $body->write((string)$this);
        $newResponse = $response
            ->withStatus(200)
            ->withHeader('Content-type', 'application/pdf')
            ->withAddedHeader('Content-Disposition', 'inline; filename="' . $fileName . '"')
            ->withAddedHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->withAddedHeader('Pragma', 'public')
            ->withBody($body);
        return $newResponse;
    }

    /**
     * Vérifie les options du document
     *
     * @param array $options Options du document
     *
     * @return bool
     * @throws \Exception
     */
    private function parseOptions(array $options)
    {
        foreach ($options as $key => $value) {
            // Orientation
            if ($key === 'orientation' && !in_array(strtoupper($value), $this->orientations)) {
                throw new \Exception(
                    "L'orientation du document PDF est incorrecte : $value. Essayez-plutôt une de celles-ci : "
                    . implode(', ', $this->orientations)
                );
            }

            // Unité
            if ($key === 'unit' && !in_array(strtolower($value), $this->units)) {
                throw new \Exception(
                    "L'unité de mesure du document PDF est incorrecte : $value. Essayez-plutôt une de celles-ci : "
                    . implode(', ', $this->units)
                );
            }

            // Format
            if ($key === 'format' && !in_array($value, $this->formats)) {
                throw new \Exception(
                    "Le format du document PDF est incorrecte : $value. Essayez-plutôt un de ceux-ci : "
                    . implode(', ', $this->formats)
                );
            }
        }
        $this->options = $options;
        return true;
    }

    /**
     * Se positionne au début de la colonne souhaitée
     *
     * ### Exemple
     * - `$pdf->setCol(1);`
     * - `$pdf->setCol(2, 4);`
     *
     * @param int|null $col   Numéro de la colonne souhaitée
     * @param int|null $colNb Nombre de colonnes souhaitées
     *
     * @return self
     */
    public function setCol($col = 0, $colNb = null)
    {
        // Colonne courante
        $this->col = $col;
        if (!is_null($colNb)) {
            $this->colWidth = intval($this->getBodySize('width') / $colNb);
            $this->colNb = $colNb;
        } else {
            $this->colWidth = $this->getBodySize('width');
        }
        // Définition de la position
        $x = $this->getMargin('left') + ($this->col * $this->colWidth);
        $this->setMargin('left', $x);
        $this->SetX($x);
        return $this;
    }

    /**
     * Définit le pied du document
     */
    public function Footer()
    {
        // Pour addDebug
        // Réecrire dans la classe du nouveau document
        $this->SetY($this->getMargin('bottom') * -1);
        $this->addLine();
        $this->SetFont($this->getFont(), 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' sur ' . '{nb}', 0, 0, 'C');
    }
}
