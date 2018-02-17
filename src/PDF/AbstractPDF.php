<?php
/**
 * Fichier AbstractPDF.php du 12/02/2018
 * Description : Fichier de la classe AbstractPDF
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

/**
 * Class AbstractPDF
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
class AbstractPDF extends \FPDF
{
    use ColorsPdfTrait, Psr7PdfTrait, BookmarkPdfTrait;

    /**
     * Options par défaut d'un document
     *
     * @var array
     */
    protected $defaultOptions = [
        'orientation' => 'P',
        'unit' => 'mm',
        'format' => 'A4',
        'heightLine' => 10,
        'marges' => [
            'top' => 10,
            'bottom' => 10,
            'left' => 10,
            'right' => 10
        ],
        'font' => [
            'family' => 'courier',
            'style' => '',
            'size' => 10,
            'color' => 'black'
        ],
        'fillColor' => 'graylight'
    ];

    /**
     * Types d'écriture
     *
     * @var array[string]
     */
    protected $tools = ['draw', 'fill', 'text'];

    /**
     * Liste des unités de mesures possibles
     *
     * @var array
     */
    protected $units = ['pt', 'mm', 'cm', 'in'];

    /**
     * Liste des formats possibles
     *
     * @var array
     */
    protected $formats = ['A3', 'A4', 'A5', 'Letter', 'Legal'];

    /**
     * Constructeur
     *
     * ### Exemple
     * - `$pdf = new AbstractPDF($options);`
     * - `$pdf = new AbstractPDF($options, $items);`
     * - `$pdf = new AbstractPDF(['format' => [150, 100]);`
     *
     * @param array|null $options
     */
    public function __construct(array $options = [])
    {
        $options = array_merge($this->defaultOptions, $options);
        parent::__construct($options['orientation'], $options['unit'], $options['format']);

        parent::AddPage($options['orientation'], $options['format']);
        parent::AliasNbPages();
        parent::SetFont(
            $options['font']['family'],
            $options['font']['style'],
            $options['font']['size']
        );
        //$this->pu
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
     * Obtenir le nombre total de pages
     *
     * @return int
     */
    public function getTotalPages()
    {
        return count($this->pages);
    }

    /**
     * Obtenir la taille du corps
     *
     * ### Exemple
     * - `$pdf->getBodySize();`
     * - `$pdf->getBodySize('width');`
     *
     * @param string|null $type (width ou height)
     *
     * @return array[int]|int
     */
    public function getBodySize($type = null)
    {
        $sizes = [
            'width' => intval(parent::GetPageWidth() - ($this->lMargin + $this->rMargin)),
            'height' => intval(parent::GetPageHeight() - ($this->tMargin + $this->bMargin))
        ];
        return is_null($type) ? $sizes : $sizes[$type];
    }

    /**
     * Obtenir une marge
     *
     * ### Exemple
     * - `$pdf->getMargin();`
     * - `$pdf->getMargin('r');`
     *
     * @param string|null $type (r, l, t, b)
     *
     * @return mixed
     */
    public function getMargin($type = null)
    {
        if (is_null($type)) {
            return [
                'top' => intval($this->tMargin),
                'bottom' => intval($this->bMargin),
                'right' => intval($this->rMargin),
                'left' => intval($this->lMargin),
                'cell' => intval($this->cMargin)
            ];
        }
        $property = strtolower($type) . 'Margin';
        return intval($this->$property);
    }

    /**
     * Définit la valeur d'une marge
     *
     * ### Exemple
     * - `$pdf->setMargin('top', 15);`
     * - `$pdf->setMargin('left', 15);`
     *
     * @param string $type  Type de marge (top, bottom, left, right)
     * @param double $value Valeur de la marge
     *
     * @return mixed
     */
    public function setMargin($type, $value)
    {
        $methodName = 'Set' . ucfirst($type) . 'Margin';
        $this->$methodName($value);
        return $this;
    }

    /**
     * Obtenir les coordonées du curseur
     *
     * ### Exemple
     * - `$pdf->getCursor();`
     * - `$pdf->getCursor('y');`
     *
     * @param string|null $type x ou y
     *
     * @return array|int
     */
    public function getCursor($type = null)
    {
        if (is_null($type)) {
            return [
                'x' => intval(parent::GetX()),
                'y' => intval(parent::GetY())
            ];
        } else {
            $method = 'Get' . strtoupper($type);
            return intval($this->$method());
        }
    }

    /**
     * Obtenir l'orientation courante
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->CurOrientation;
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
     * Se positionner dans le document
     *
     * ### Exemple
     * - `$pdf->setCursor(15);`
     * - `$pdf->setCursor(15, 25);`
     *
     * @param int      $x
     * @param int|null $y
     *
     * @return $this
     */
    public function setCursor($x, $y = null)
    {
        if (is_null($y)) {
            $y = intval(parent::GetY());
        }
        parent::SetXY($x, $y);
        return $this;
    }

    /**
     * Obtenir les informations sur la police courante
     *
     * ### Exemple
     * - `$pdf->getFont();`
     * - `$pdf->getFont('size');`
     *
     * @param bool|null   $all
     * @param string|null $key
     *
     * @return string|array
     */
    public function getFont($key = null, $all = false)
    {
        $font = [
            'family' => $this->FontFamily,
            'style' => $this->FontStyle,
            'size' => $this->FontSizePt,
            'sizeInUnit' => $this->FontSize,
            'color' => $this->TextColor,
            'isUnderline' => $this->underline
        ];
        if ($all) {
            return $font;
        }
        if (!is_null($key) && array_key_exists($key, $font)) {
            return $font[$key];
        }
        return $font['family'];
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
     * Vérifie la présence d'une police par son nom
     *
     * @param string| $fontName Nom de la police
     *
     * @return bool
     */
    public function hasFont($fontName)
    {
        return in_array(strtolower($fontName), $this->getFonts());
    }

    /**
     * Obtenir la couleur d'un outil
     *
     * ### Exemple
     * - `$pdf->getToolColor();`
     * - `$pdf->getToolColor('fill');`
     *
     * @param string|null $tool text, fill ou draw
     *
     * @return string|bool
     */
    public function getToolColor($tool = 'text')
    {
        if ($this->hasTool($tool)) {
            $property = ucfirst($tool) . 'Color';
            return $this->$property;
        }
        return false;
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
     * Enregistrer le document PDF sur le serveur
     *
     * @param string|null $dest Chemin et nom du fichier PDF (sans l'extension)
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
     * Imprime une ligne sur toute la largeur du corps
     *
     * @param int $ln Saut de ligne après la ligne
     *
     * @return $this
     */
    public function addLine($ln = 0)
    {
        $this->Line($this->GetX(), $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
        $this->Ln(intval($ln));
        return $this;
    }

    /**
     * Vérifie la validité d'un type d'outil
     *
     * @param string $name Type d'outil (text, fill, draw)
     *
     * @return bool
     */
    public function hasTool($name)
    {
        return in_array($name, $this->tools);
    }

    /**
     * Obtenir la liste des outils
     *
     * @return array
     */
    public function getTools()
    {
        return $this->tools;
    }

    /**
     * Vérifier la présence d'une unité de mesure
     *
     * @param string $name Nom de l'unité
     *
     * @return bool
     */
    public function hasUnit($name)
    {
        return in_array($name, $this->units);
    }

    /**
     * Obtenir la liste des unités de mesures
     *
     * @return array
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Vérifier la présence d'un format de document
     *
     * @param string $name Nom du format
     *
     * @return bool
     */
    public function hasFormat($name)
    {
        return in_array($name, $this->formats);
    }

    /**
     * Obtenir la liste des formats
     *
     * @return array
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * Définit la police
     *
     * @param string|null $family
     * @param string|null $style
     * @param int|null    $size
     * @param int|null    $color
     * @param bool|null   $underline
     * @param string|null $fillColor Couleur de remplissage
     *
     * @throws \Exception
     */
    public function SetFont(
        $family = null,
        $style = null,
        $size = null,
        $color = null,
        $underline = false,
        $fillColor = null
    ) {
        if (!$this->hasFont($family)) {
            $family = $this->defaultOptions['font']['family'];
        }
        if (is_null($style)) {
            $style = $this->defaultOptions['font']['style'];
        }
        if (is_null($size)) {
            $size = $this->defaultOptions['font']['size'];
        }

        parent::SetFont($family, $style, $size);

        if (is_null($color)) {
            $color = $this->defaultOptions['font']['color'];
        }
        if (!is_null($fillColor) && $this->hasColor($fillColor)) {
            $this->setColor($fillColor, 'fill');
        } else {
            $this->setColor($this->defaultOptions['fillColor'], 'fill');
        }

        if (!is_null($color)) {
            if (is_array($color)) {
                list($r, $g, $b) = $color;
                parent::SetTextColor(intval($r), intval($g), intval($b));
            } elseif (is_numeric($color)) {
                parent::SetTextColor(intval($color));
            } elseif (is_string($color) && $this->hasColor($color)) {
                $rgb = $this->getColors($color, true);
                parent::SetTextColor($rgb['r'], $rgb['g'], $rgb['b']);
            }
        }
        $this->underline = $underline;
    }
}
