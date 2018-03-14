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
 * <ul>
 * <li>Classe parente de tous les documents PDF.</li>
 * <li>Pour créer nouveau document, créer une classe qui hérite de <code>AbstractPDF</code>
 * et lui associer les traits qui correspondent aux fonctionnalités souhaitées</li>
 * </ul>
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class AbstractPDF extends \FPDF
{
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
            'family' => 'helvetica',
            'style' => '',
            'size' => 10,
            'align' => 'L',
            'color' => '#000000'
        ],
        'fillColor' => '#CCCCCC',
        'drawColor' => '#CCCCCC'
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

        $font = $options['font'];
        parent::SetFont(
            $font['family'],
            $font['style'],
            $font['size']
        );
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
     * @return array|double
     */
    public function getBodySize($type = null)
    {
        $sizes = [
            'width' => parent::GetPageWidth() - ($this->lMargin + $this->rMargin),
            'height' => parent::GetPageHeight() - ($this->tMargin + $this->bMargin)
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
     * @return double|array
     */
    public function getMargin($type = null)
    {
        if (is_null($type)) {
            return [
                'top' => $this->tMargin,
                'bottom' => $this->bMargin,
                'right' => $this->rMargin,
                'left' => $this->lMargin,
                'cell' => $this->cMargin
            ];
        }
        $property = strtolower($type) . 'Margin';
        return $this->$property;
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
     * @return $this
     */
    public function setMargin($type, $value)
    {
        $methodName = 'Set' . ucfirst($type) . 'Margin';
        $this->$methodName($value);
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
     * @return array|float
     * @throws \Exception
     */
    public function getCursor($type = null)
    {
        if (is_null($type)) {
            return [
                'x' => parent::GetX(),
                'y' => parent::GetY()
            ];
        } else {
            $method = 'Get' . strtoupper($type);
            return $this->$method();
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
        return $this->PageBreakTrigger;
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
            $y = parent::GetY();
        }
        parent::SetXY($x, $y);
        return $this;
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
    public function getToolColor($tool = null)
    {
        if (is_null($tool)) {
            return [
                'draw' => $this->DrawColor,
                'fill' => $this->FillColor,
                'text' => $this->TextColor
            ];
        } elseif ($this->hasTool($tool)) {
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
     * Définir une meta-donnée
     *
     * @param string     $key   Nom de la clé ou tableau
     * @param mixed|null $value Valeur de la clé
     */
    public function setMetadata($key, $value = null)
    {
        if (is_string($key)) {
            $this->metadata[$key] = $value;
        } elseif (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->metadata[$k] = $v;
            }
        }
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
        return in_array(strtolower($name), $this->tools);
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
        return in_array(ucfirst($name), $this->formats);
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
     * @param string|null $family     Nom de la police
     * @param string|null $style      Styles de la police
     * @param int|null    $size       Taille de la police
     * @param array|null  $properties Autres propriétés de la police (couleur, couleur de remplissage, couleur du trait)
     *
     * @throws \Exception
     */
    public function SetFont($family = null, $style = null, $size = null, array $properties = null)
    {
        $font = $this->defaultOptions['font'];
        if (is_null($family) || !$this->hasFont($family)) {
            $family = $font['family'];
        }
        if (is_null($style)) {
            $style = $font['style'];
        }
        if (is_null($size)) {
            $size = $font['size'];
        }
        parent::SetFont($family, $style, $size);

        if (is_array($properties) && !empty($properties)) {
            // Couleur du texte
            if (array_key_exists('color', $properties)) {
                if (method_exists($this, 'setColor')) {
                    $this->setColor($properties['color']);
                } else {
                    $this->setToolColor($this->hexaToRgb($properties['color']));
                }
            }
            // Couleur du trait
            if (array_key_exists('drawColor', $properties)) {
                if (method_exists($this, 'setColor')) {
                    $this->setColor($properties['color'], 'draw');
                } else {
                    $this->setToolColor($this->hexaToRgb($properties['drawColor']), 'draw');
                }
            }
            // Couleur de remplissage
            if (array_key_exists('fillColor', $properties)) {
                if (method_exists($this, 'setColor')) {
                    $this->setColor($properties['fillColor'], 'fill');
                } else {
                    $this->setToolColor($this->hexaToRgb($properties['fillColor']), 'fill');
                }
            }
        }

        if (count(func_get_args()) === 0) {
            $this->setToolColor($this->hexaToRgb($font['color']));
            $this->setToolColor($this->hexaToRgb($this->defaultOptions['fillColor']), 'fill');
            $this->setToolColor($this->hexaToRgb($this->defaultOptions['drawColor']), 'draw');
        }
    }

    /**
     * Vérifie si le style courant est souligné
     *
     * @return bool
     */
    public function isUnderline()
    {
        return $this->underline;
    }

    /**
     * Obtenir toutes les propriétés de la police courante ou l'une d'entre elle
     *
     * @param string|null $property Nom de la propriété souhaitée
     *
     * @return array|string|bool
     */
    public function getFontProperty($property = null)
    {
        $properties = [
            'family' => $this->FontFamily,
            'style' => $this->FontStyle,
            'size' => $this->FontSizePt,
            'sizeInUnit' => $this->FontSize
        ];
        if (is_null($property)) {
            return $properties;
        } elseif (array_key_exists($property, $properties)) {
            return $properties[$property];
        }
        return false;
    }

    /**
     * Imprimer le contenu d'un fichier source dans un PDF
     *
     * @param string $file Nom du fichier source
     * @param string $dest Type de destination du PDF généré
     *
     * @return bool
     */
    public function fileToPdf($file, $dest = 'file')
    {
        $dests = ['file', 'view', 'download'];
        if (!file_exists($file) || !in_array($dest, $dests)) {
            return false;
        }

        $content = file_get_contents($file);
        if (is_null($content)) {
            return false;
        }

        switch ($dests) {
            case 'file':
                $this->Write(10, $content);
                $this->toFile();
                break;

            case 'view':
                break;

            case 'download':
                break;
        }

        return false;
    }

    /**
     * Obtenir les valeurs RGB à partir d'un code couleur au formar héxadécimal
     *
     * @param string $hexa Code héxadécimal d'une couleur
     *
     * @return array
     * @throws \Exception
     */
    public function hexaToRgb($hexa)
    {
        if ($hexa[0] != '#' || strlen($hexa) != 7) {
            throw new \Exception("Code héxadécimal : '$hexa' de mauvaise longueur ou erroné");
        }
        return [
            'r' => hexdec(substr($hexa, 1, 2)),
            'g' => hexdec(substr($hexa, 3, 2)),
            'b' => hexdec(substr($hexa, 5, 2))
        ];
    }

    /**
     * Définir la couleur d'un outil
     *
     * ### Exemple
     * - `$pdf->setToolColor(0, 'fill')`
     * - `$pdf->setToolColor('#3498db', 'fill')`
     * - `$pdf->setToolColor('graylight', 'fill')`
     *
     * @param string|array|int $color Code héxadécimal, valeur 'r' ou tableau rgb
     * @param string|null      $tool  Nom de l'outil à colorer, si null c'est le text qui est coloré par défaut
     *
     * @return void
     * @throws \Exception
     */
    public function setToolColor($color, $tool = 'text')
    {
        if (!$this->hasTool($tool)) {
            throw new \Exception("Impossible de définir la couleur de l'outil '$tool'");
        }
        $method = 'Set' . ucfirst($tool) . 'Color';
        $rgb = ['r' => 0, 'g' => 0, 'b' => 0];


        if (is_int($color) && ($color >= 0 && $color <= 255)) {
            $rgb['r'] = $color;
        }

        if (is_array($color) && array_keys($color) === array_keys($rgb)) {
            $rgb = $color;
        }

        if (is_string($color)) {
            if ($color[0] === '#' && strlen($color) === 7) {
                $rgb = $this->hexaToRgb($color);
            } elseif ($color[0] != '#') {
                if (method_exists($this, 'setColor')) {
                    $this->setColor($color, $tool);
                    return;
                } else {
                    throw new \Exception(
                        "Le trait 'ColorsPdfTrait' doit être implémenté pour utiliser les couleurs nommées"
                    );
                }
            }
        }
        $this->$method($rgb['r'], $rgb['g'], $rgb['b']);
    }
}
