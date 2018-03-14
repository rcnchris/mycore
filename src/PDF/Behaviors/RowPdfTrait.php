<?php
/**
 * Fichier RowPdfTrait.php du 15/02/2018
 * Description : Fichier de la classe RowPdfTrait
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
 * Trait RowPdfTrait
 * <ul>
 * <li>Définition de tableaux et écriture d'une ligne</li>
 * </ul>
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
trait RowPdfTrait
{
    /**
     * Hauteur de ligne
     *
     * @var int
     */
    private $heightLine = 5;

    /**
     * Tableau des largeurs de colonnes
     *
     * @var array[int]
     */
    private $colsWidth;

    /**
     * Tableau des alignements de colonnes
     *
     * @var array[int]
     */
    private $colsAlign;

    /**
     * Tableau des bodures de colonnes
     *
     * @var array
     */
    private $colsBorder;

    /**
     * Tableau des couleurs de texte de chaque colonne
     *
     * @var array
     */
    private $colsTextColors;

    /**
     * Tableau des couleurs de trait de chaque colonne
     *
     * @var array
     */
    private $colsDrawColors;

    /**
     * Tableau des couleurs de remplissage de chaque colonne
     *
     * @var array
     */
    private $colsFillColors;

    /**
     * Tableau des remplissage de colonnes
     *
     * @var array
     */
    private $colsFill;

    /**
     * Tableau des polices par colonne
     *
     * @var array
     */
    private $colsFont = [];

    /**
     * Tableau des tailles de polices de chaque colonne
     *
     * @var array
     */
    private $colsFontSize = [];

    /**
     * Définir le nombre et la largeur des colonnes en unité
     *
     * ### Exemple
     * - `$pdf->setColsWidth(50, 30, 60);`
     */
    public function setColsWidth()
    {
        foreach (func_get_args() as $k => $w) {
            $this->colsWidth[$k] = $w;
        }
    }

    /**
     * Définir le nombre et la largeur des colonnes en pourcentage de la largeur du corps
     */
    public function setColsWidthInPourc()
    {
        foreach (func_get_args() as $k => $w) {
            $this->colsWidth[$k] = $this->getBodySize('width') * ($w / 100);
        }
    }

    /**
     * Définir l'alignement des colonnes
     *
     * ### Exemple
     * - `$pdf->setColsAlign('L', 'C', 'R');`
     */
    public function setColsAlign()
    {
        foreach (func_get_args() as $k => $a) {
            $this->colsAlign[$k] = $a;
        }
    }

    /**
     * Définir les bordures des colonnes
     *
     * ### Exemple
     * - `$pdf->setColsBorder('B', 'B', 'B');`
     */
    public function setColsBorder()
    {
        foreach (func_get_args() as $k => $b) {
            $this->colsBorder[$k] = $b;
        }
    }

    /**
     * Définir les colonnes à remplir
     */
    public function setColsFill()
    {
        foreach (func_get_args() as $k => $f) {
            $this->colsFill[$k] = $f;
        }
    }

    /**
     * Définir la couleur du texte des colonnes
     */
    public function setColsTextColors()
    {
        foreach (func_get_args() as $k => $c) {
            $this->colsTextColors[$k] = is_null($c) ? 0 : $c;
        }
    }

    /**
     * Définir la couleur de remplissage de chaque colonne
     */
    public function setColsFillColors()
    {
        foreach (func_get_args() as $k => $c) {
            $this->colsFillColors[$k] = is_null($c) ? 0 : $c;
        }
    }

    /**
     * Définir la couleur du trait de chaque colonne
     */
    public function setColsDrawColors()
    {
        foreach (func_get_args() as $k => $c) {
            $this->colsDrawColors[$k] = is_null($c) ? 0 : $c;
        }
    }

    /**
     * Définir la police de chaque colonne
     */
    public function setColsFont()
    {
        foreach (func_get_args() as $k => $f) {
            $this->colsFont[$k] = is_null($f) ? 'courier' : $f;
        }
    }

    /**
     * Définir la taille de la police de chaque colonne
     */
    public function setColsFontSize()
    {
        foreach (func_get_args() as $k => $s) {
            $this->colsFontSize[$k] = is_null($s) ? $this->font->size : $s;
        }
    }

    /**
     * Imprime une ligne selon le colonnage définit avec setColsWidth
     *
     * ### Exemple :
     * - `$pdf->rowCols('Ola', 'les', 'gens');`
     */
    public function rowCols()
    {
        $data = func_get_args();
        // Calcule la hauteur de la ligne
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->nbLinesMultiCell($this->colsWidth[$i], $data[$i]));
        }
        $h = 5 * $nb;
        // Effectue un saut de page si nécessaire
        $this->checkPageBreak($h);
        // Dessine les cellules
        for ($i = 0; $i < count($data); $i++) {
            $width = $this->colsWidth[$i];

            $align = isset($this->colsAlign[$i]) ? $this->colsAlign[$i] : 'L';
            $border = isset($this->colsBorder[$i]) ? $this->colsBorder[$i] : 0;

            $textColor = isset($this->colsTextColors[$i]) ? $this->colsTextColors[$i] : 0;
            $this->setToolColor($textColor);

            $fillColor = isset($this->colsFillColors[$i]) ? $this->colsFillColors[$i] : 0;
            $this->setToolColor($fillColor, 'fill');

            $drawColor = isset($this->colsDrawColors[$i]) ? $this->colsDrawColors[$i] : 0;
            $this->setToolColor($drawColor, 'draw');

            $fill = isset($this->colsFill[$i]) ? $this->colsFill[$i] : false;

            // Sauve la position courante
            $x = $this->GetX();
            $y = $this->GetY();

            // Imprime le texte
            $this->MultiCell($width, $this->heightLine, utf8_decode($data[$i]), $border, $align, $fill);

            $this->SetTextColor(0);
            $this->SetFillColor(0);
            $this->SetDrawColor(0);
            // Repositionne à droite
            $this->SetXY($x + $width, $y);
        }

        // Va à la ligne
        $this->Ln($h);
        $this->addLine();
    }

    /**
     * Obtenir le nombre de colonnes
     *
     * @return int|void
     */
    public function getNbCols()
    {
        return count($this->colsWidth);
    }

    /**
     * Obtenir la largeur d'une colonne
     *
     * @param int $indice Indice de la colonne
     *
     * @return double|bool
     */
    public function getColWidth($indice)
    {
        if (array_key_exists($indice, $this->colsWidth)) {
            return $this->colsWidth[$indice];
        }
        return false;
    }

    /**
     * Obtenir toutes propriétés des colonnes ou celles de l'une d'entre elle
     *
     * @param int|null $indice Numéro de la colonne
     *
     * @return array
     */
    public function getColsProperties($indice = null)
    {
        $p = [];
        for ($i = 0; $i < $this->getNbCols(); $i++) {
            $p[$i] = [
                'width' => $this->getColWidth($i),
                'align' => isset($this->colsAlign[$i])
                    ? $this->colsAlign[$i]
                    : 'L',
                'border' => isset($this->colsBorder[$i])
                    ? $this->colsBorder[$i]
                    : 0,
                'fill' => isset($this->colsFill[$i])
                    ? $this->colsFill[$i]
                    : false,
                'fillColor' => isset($this->colsFillColors[$i])
                    ? $this->colsFillColors[$i]
                    : $this->getToolColor('fill'),
                'textColor' => isset($this->colsTextColors[$i])
                    ? $this->colsTextColors[$i]
                    : $this->getToolColor('text'),
                'drawColor' => isset($this->colsDrawColors[$i])
                    ? $this->colsDrawColors[$i]
                    : $this->getToolColor('draw'),
                'font' => isset($this->colsFont[$i])
                    ? $this->colsFont[$i]
                    : $this->getFontProperty('family'),
                'fontSize' => isset($this->colsFontSize[$i])
                    ? $this->colsFontSize[$i]
                    : $this->getFontProperty('size'),
            ];
        }
        return !is_null($indice) && array_key_exists($indice, $p)
            ? $p[$indice]
            : $p;
    }

    /**
     * Vérifie dans le cadre d'une ligne d'un tableau,
     * s'il est nécessaire de sauter de page
     *
     * @param double $h Hauteur du contenu
     */
    private function checkPageBreak($h)
    {
        // Si la hauteur h provoque un débordement, saut de page manuel
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    /**
     * Renvoie le nombre de lignes qu'occupe un MultiCell
     *
     * @param $w
     * @param $txt
     *
     * @return int
     */
    private function nbLinesMultiCell($w, $txt)
    {
        // Calcule le nombre de lignes qu'occupe un MultiCell de largeur w
        $cw =& $this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n") {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    /**
     * Définir la hauteur de ligne
     *
     * @param int $heightLine Hauteur des ligness
     */
    public function setHeightLine($heightLine)
    {
        $this->heightLine = $heightLine;
    }
}
