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

use Rcnchris\Core\Tools\Items;

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
     * Options par défaut du document
     *
     * @var array
     */
    protected $defaultOptions = [
        'orientation' => 'P',
        'unit' => 'mm',
        'format' => 'A4',
        'rotation' => 0,
        'marges' => [
            'top' => 10,
            'bottom' => 10,
            'left' => 10,
            'right' => 10
        ],
        'fontFamily' => 'helvetica',
        'fontStyle' => '',
        'fontSize' => 10,
        'underline' => false,
        'heightline' => 10,
        'border' => 0,
        'align' => 'L',
        'fill' => false,
        'ln' => 0,
        'textColor' => '#000000',
        'fillColor' => '#CCCCCC',
        'drawColor' => '#000000'
    ];

    /**
     * Types d'outil
     *
     * @var array[string]
     */
    protected $tools = ['draw', 'fill', 'text'];

    /**
     * Liste des unités de mesures possibles
     *
     * @var array[string]
     */
    protected $units = ['pt', 'mm', 'cm', 'in'];

    /**
     * Liste des formats possibles
     *
     * @var array[string]
     */
    protected $formats = ['A3', 'A4', 'A5', 'Letter', 'Legal'];

    /**
     * Niveaux de zoom
     *
     * @var array[string]
     */
    protected $zoomModes = ['default', 'fullpage', 'fullwidth', 'real'];

    /**
     * Dispositions des pages
     *
     * @var array[string]
     */
    protected $layoutModes = ['default', 'single', 'two', 'continuous'];

    /**
     * Options de construction du document
     *
     * @var Items
     */
    public $options;

    /**
     * Writer
     *
     * @var \Rcnchris\Core\PDF\Writer
     */
    private $writer;

    /**
     * Constructeur
     *
     * ### Exemple
     * - `$pdf = new AbstractPDF($options);`
     * - `$pdf = new AbstractPDF(['format' => [150, 100]);`
     *
     * @param array|null $options Options par défaut de construction du document
     */
    public function __construct(array $options = [])
    {
        $this->options = new Items(array_merge($this->defaultOptions, $options));
        parent::__construct(
            $this->options->get('orientation'),
            $this->options->get('unit'),
            $this->options->get('format')
        );
        $this->SetFont();
        $this->writer = new Writer($this);
    }

    /**
     * Ajoute une page
     *
     * ### Exemple
     * - `$pdf->AddPage();`
     * - `$pdf->AddPage('P', 'A4', 0);`
     *
     * @param string|null $orientation Orientation du document
     * @param string|null $size        Format du document
     * @param int|null    $rotation    Angle de rotation
     *
     * @return void
     */
    public function AddPage($orientation = '', $size = '', $rotation = 0)
    {
        if ($orientation === '') {
            $orientation = $this->options->get('orientation');
        }
        if ($size === '') {
            $size = $this->options->get('format');
        }
        if ($rotation === 0) {
            $rotation = $this->options->get('rotation');
        }
        parent::AddPage($orientation, $size, $rotation);
        if ($this->getTotalPages() === 1) {
            parent::AliasNbPages();
        }
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
     * Obtenir toutes les marges ou l'une d'entre elle
     *
     * ### Exemple
     * - `$pdf->getMargin();`
     * - `$pdf->getMargin('r');`
     *
     * @param string|null $type (r, l, t, b)
     *
     * @return array|double
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
        } elseif (strlen($type) > 1) {
            $type = $type[0];
        }
        $property = strtolower($type) . 'Margin';
        return $this->$property;
    }

    /**
     * Définir la valeur d'une marge
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
        if ($type != 'bottom') {
            $methodName = 'Set' . ucfirst($type) . 'Margin';
            $this->$methodName($value);
        } else {
            $this->bMargin = $value;
        }
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
        $this->SetXY($x, $y);
        return $this;
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
     * Obtenir la commande de la couleur courante d'un outil
     *
     * ### Exemple
     * - `$pdf->getToolColor();`
     * - `$pdf->getToolColor('fill');`
     *
     * @param string|null $tool text, fill ou draw
     *
     * @return array|string|bool
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
     *
     * @return void
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
     * ### Exemple
     * - `$pdf->toFile('patht/to/file/filename');`
     *
     * @param string|null $fileName Chemin et nom du fichier PDF (sans l'extension)
     *
     * @return string
     */
    public function toFile($fileName = null)
    {

        if (is_null($fileName)) {
            $fileName = get_class($this);
            $fileName = explode('\\', $fileName);
            $fileName = array_pop($fileName);
        }
        return $this->Output('F', trim($fileName) . '.pdf');
    }

    /**
     * Vérifie la validité d'un type d'outil
     *
     * @param string $name Type d'outil (text, fill, draw)
     *
     * @return bool
     */
    private function hasTool($name)
    {
        return in_array(strtolower($name), $this->tools);
    }

    /**
     * Définit la police
     *
     * ### Exemple
     * - `$pdf->SetFont();`
     * - `$pdf->SetFont('courier', 'BI', 12);`
     * - `$pdf->SetFont('courier', 'BI', 12, ['color' => '#e74c3c']);`
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
        if (count(func_get_args()) === 0) {
            parent::SetFont(
                $this->options->get('fontFamily'),
                $this->options->get('fontStyle'),
                $this->options->get('fontSize')
            );
        }
        if (is_null($family) || !$this->hasFont($family)) {
            $family = $this->options->get('fontFamily');
        }
        if (is_null($style)) {
            $style = $this->options->get('fontStyle');
        }
        if (is_null($size)) {
            $size = $this->options->get('fontSize');
        }
        parent::SetFont($family, $style, $size);

        if (count(func_get_args()) === 0) {
            // Couleurs
            $this->setToolColor($this->hexaToRgb($this->options->get('textColor'), 'text'));
            $this->setToolColor($this->hexaToRgb($this->options->get('fillColor'), 'fill'));
            $this->setToolColor($this->hexaToRgb($this->options->get('drawColor'), 'draw'));

            // Souligné ?
            $this->underline = $this->options->get('underline');
        } elseif (!empty($properties)) {
            // Couleur du texte
            if (array_key_exists('textColor', $properties)) {
                if (method_exists($this, 'setColor')) {
                    $this->setColor($properties['textColor']);
                } else {
                    $this->setToolColor($this->hexaToRgb($properties['textColor']), 'text');
                }
            }
            // Couleur du trait
            if (array_key_exists('drawColor', $properties)) {
                if (method_exists($this, 'setColor')) {
                    $this->setColor($properties['drawColor'], 'draw');
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

            // Souligné ?
            if (array_key_exists('underline', $properties) && is_bool($properties['underline'])) {
                $this->underline = $properties['underline'];
                $this->options->set('underline', $properties['underline']);
            }

            // Hauteur de ligne
            if (array_key_exists('heightline', $properties) && is_numeric($properties['heightline'])) {
                $this->options->set('heightline', $properties['heightline']);
            }

            // Bordure
            if (array_key_exists('border', $properties)) {
                $this->options->set('border', $properties['border']);
            }

            // Alignement
            if (array_key_exists('align', $properties)) {
                $this->options->set('align', $properties['align']);
            }
        }
    }

    /**
     * Obtenir toutes les propriétés de la police courante ou l'une d'entre elle
     *
     * ### Exemple
     * - `$pdf->getFontProperty();`
     * - `$pdf->getFontProperty('family');`
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
            'sizeInUnit' => $this->FontSize,
            'underline' => $this->underline,
            'align' => $this->options->get('align'),
            'fill' => false,
            'fillColor' => $this->getToolColor('fill'),
            'drawColor' => $this->getToolColor('draw'),
            'textColor' => $this->getToolColor('text')
        ];
        if (is_null($property)) {
            return $properties;
        } elseif (array_key_exists($property, $properties)) {
            return $properties[$property];
        }
        return false;
    }


    /**
     * Obtenir la liste des liens ou l'un d'entre eux
     *
     * @param int|null $id Identifiant d'un lien
     *
     * @return array
     */
    public function getLinks($id = null)
    {
        return !is_null($id) && array_key_exists($id, $this->links)
            ? $this->links[$id]
            : $this->links;
    }

    /**
     * Imprimer le contenu d'un fichier source dans un PDF
     *
     * @param string $file Nom du fichier source
     *
     * @return $this|bool
     */
    public function fileToPdf($file)
    {

        if (!file_exists($file)) {
            return false;
        }
        $content = file_get_contents($file);
        if (is_null($content) || $content === '') {
            return false;
        }
        if ($this->getTotalPages() === 0) {
            $this->AddPage();
        }
        $this->Write(10, $content);
        return $this;
    }

    /**
     * Obtenir les valeurs RGB à partir d'un code couleur au format héxadécimal
     *
     * ### Exemple
     * - `$pdf->hexaToRgb('#000000');`
     *
     * @param string $hexa Code héxadécimal d'une couleur
     *
     * @return array
     * @throws \Exception
     */
    protected function hexaToRgb($hexa)
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
     * @return $this
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
        } elseif (is_array($color) && array_keys($color) === array_keys($rgb)) {
            $rgb = $color;
        } elseif (is_string($color)) {
            if ($color[0] === '#') {
                $rgb = $this->hexaToRgb($color);
            } elseif ($color[0] != '#') {
                if (method_exists($this, 'setColor')) {
                    $this->setColor($color, $tool);
                    return $this;
                } else {
                    throw new \Exception(
                        "Le trait 'ColorsPdfTrait' doit être implémenté pour utiliser les couleurs nommées !"
                    );
                }
            }
        }
        $this->$method($rgb['r'], $rgb['g'], $rgb['b']);

        return $this;
    }

    /**
     * Définir un nouveau writer
     *
     * @param \Rcnchris\Core\PDF\AbstractPDF|null $pdf     Document PDF
     * @param array|null                          $options Options d'écritures du Writer
     *
     * @return $this
     */
    public function setWriter(AbstractPDF $pdf = null, array $options = [])
    {
        if (is_null($pdf)) {
            $pdf = $this;
        }
        $this->writer = new Writer($pdf, $options);
        return $this;
    }

    /**
     * Obtenir l'instance du Writer
     *
     * @return Writer
     */
    public function getWriter()
    {
        return $this->writer;
    }
}
