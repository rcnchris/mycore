<?php
/**
 * Fichier DesignerPdfTrait.php du 24/04/2018
 * Description : Fichier de la classe DesignerPdfTrait
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
 * Class DesignerPdfTrait
 * <ul>
 * <li>Est chargé de dessiner des formes géométriques (rectangles, cercles, lignes...).</li>
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
trait DesignerPdfTrait
{

    /**
     * Options de construction par forme géométrique
     *
     * @var array
     */
    private $formsOptions = [
        'line' => [
            'methodName' => 'addLine',
            'params' => [
                'lnBefore' => 0,
                'lnAfter' => 0
            ]
        ],
        'rect' => [
            'methodName' => 'roundedRect',  // Nom de la méthode dans cette classe
            'params' => [
                'x' => null,                    // Coin supérieur gauche du rectangle
                'y' => null,                    // Coin supérieur gauche du rectangle
                'w' => 0,                       // Largeur
                'h' => 0,                       // Hauteur
                'r' => 0,                       // Rayon des coins
                'corners' => '',                // Liste des coins à arrondir
                'style' => ''                   // Style du dessin (D, F, FD), comme Rect()
            ]
        ],
        'circle' => [
            'methodName' => 'circle',  // Nom de la méthode dans cette classe
            'params' => [
                'x' => null,    // Abscisse du centre
                'y' => null,    // Ordonnée du centre
                'r' => 0,       // Rayon du cercle
                'style' => ''
            ],
        ],
        'ellipse' => [
            'methodName' => 'ellipse',
            'params' => [
                'x' => null,    // Abscisse du centre
                'y' => null,    // Ordonnée du centre
                'rx' => 0,       // Rayon horizontal
                'ry' => 0,       // Rayon vertical
                'style' => ''
            ]
        ],
        'grid' => [
            'methodName' => 'grid',
            'params' => [
                'spacing' => 5
            ]
        ]
    ];

    /**
     * Dessine une forme géométrique parmi celles connues
     *
     * @param string     $formName Nom de la forme
     * @param array|null $options  Options de construction de la forme
     *
     * @return $this
     * @throws \Exception
     */
    public function draw($formName, array $options = [])
    {
        if (!$this->hasForm($formName)) {
            throw new \Exception("Le nom de la forme géométrique : $formName est inconnu !");
        }
        $methodName = $this->formsOptions[$formName]['methodName'];
        $params = array_merge($this->formsOptions[$formName]['params'], $options);
        return call_user_func_array([$this, $methodName], $params);
    }

    /**
     * Imprime une ligne sur toute la largeur du corps
     *
     * @param int $lnBefore Nombre de lignes à sauter avant la ligne
     * @param int $lnAfter  Nombre de lignes à sauter après la ligne
     *
     * @return $this
     */
    private function addLine($lnBefore = 0, $lnAfter = 0)
    {
        if ($lnBefore != 0) {
            $this->Ln(intval($lnBefore));
        }
        $this->Line($this->GetX(), $this->GetY(), $this->GetPageWidth() - $this->rMargin, $this->GetY());
        if ($lnAfter != 0) {
            $this->Ln(intval($lnAfter));
        }
        return $this;
    }

    /**
     * Permet de tracer un rectangle avec certains bords arrondis (tous ou au choix)
     *
     * ### Exemple
     * - `$pdf->roundedRect(60, 30, 68, 46, 5, '13', 'DF');`
     *
     * @param double $x       Coin supérieur gauche du rectangle
     * @param double $y       Coin supérieur gauche du rectangle
     * @param double $w       Largeur
     * @param double $h       Hauteur
     * @param double $r       Rayon des coins
     * @param string $corners Numéro du ou des angles à arrondir : 1, 2, 3, 4 ou toute combinaison (1=haut gauche,
     *                        2=haut droite, 3=bas droite, 4=bas gauche)
     * @param string $style   Comme celui de Rect() :
     *                        <ul>
     *                        <li>D ou chaîne vide : contour (draw). C'est la valeur par défaut.</li>
     *                        <li>F : remplissage (fill)</li>
     *                        <li>DF ou FD : contour et remplissage</li>
     *                        </ul>
     *
     * @return $this
     */
    private function roundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' || $style == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        if (strpos($corners, '2') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $y) * $k));
        } else {
            $this->arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
        }

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        if (strpos($corners, '3') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
        } else {
            $this->arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
        }

        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        if (strpos($corners, '4') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - ($y + $h)) * $k));
        } else {
            $this->arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
        }

        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
        if (strpos($corners, '1') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $y) * $k));
            $this->_out(sprintf('%.2F %.2F l', ($x + $r) * $k, ($hp - $y) * $k));
        } else {
            $this->arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        }
        $this->_out($op);

        return $this;
    }

    /**
     * Adapte l'échelle de conversion entre les points et les unités
     *
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param $x3
     * @param $y3
     */
    private function arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(
            sprintf(
                '%.2F %.2F %.2F %.2F %.2F %.2F c ',
                $x1 * $this->k,
                ($h - $y1) * $this->k,
                $x2 * $this->k,
                ($h - $y2) * $this->k,
                $x3 * $this->k,
                ($h - $y3) * $this->k
            )
        );
    }

    /**
     * Dessiner un cercle
     *
     * ### Exemple
     * - `$pdf->circle$this->lMargin + 7, $this->GetY() + 7, 7, 'F');`
     *
     * @param double $x     Abscisse du centre
     * @param double $y     Ordonnée du centre
     * @param double $r     Rayon du cercle
     * @param string $style Style de dessin, comme pour Rect (D, F ou FD)
     *                      - D ou chaîne vide : contour (draw). C'est la valeur par défaut.
     *                      - F : remplissage (fill)
     *                      - DF ou FD : contour et remplissage
     *
     * @return $this
     */
    private function circle($x, $y, $r, $style = 'D')
    {
        $this->ellipse($x, $y, $r, $r, $style);
        return $this;
    }

    /**
     * Dessiner une ellipse
     *
     * ### Exemple
     * - `$pdf->ellipse($this->lMargin + 7, $this->GetY() + 7, 7, 10);`
     *
     * @param double $x     Abscisse du centre
     * @param double $y     Ordonnée du centre
     * @param double $rx    Rayon horizontal
     * @param double $ry    Rayon vertical
     * @param string $style Style de dessin, comme pour Rect (D, F ou FD)
     *                      - D ou chaîne vide : contour (draw). C'est la valeur par défaut.
     *                      - F : remplissage (fill)
     *                      - DF ou FD : contour et remplissage
     *
     * @return $this
     */
    private function ellipse($x, $y, $rx, $ry, $style = 'D')
    {
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' || $style == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;
        $k = $this->k;
        $h = $this->h;
        $this->_out(sprintf(
            '%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x + $rx) * $k,
            ($h - $y) * $k,
            ($x + $rx) * $k,
            ($h - ($y - $ly)) * $k,
            ($x + $lx) * $k,
            ($h - ($y - $ry)) * $k,
            $x * $k,
            ($h - ($y - $ry)) * $k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $lx) * $k,
            ($h - ($y - $ry)) * $k,
            ($x - $rx) * $k,
            ($h - ($y - $ly)) * $k,
            ($x - $rx) * $k,
            ($h - $y) * $k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $rx) * $k,
            ($h - ($y + $ly)) * $k,
            ($x - $lx) * $k,
            ($h - ($y + $ry)) * $k,
            $x * $k,
            ($h - ($y + $ry)) * $k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x + $lx) * $k,
            ($h - ($y + $ry)) * $k,
            ($x + $rx) * $k,
            ($h - ($y + $ly)) * $k,
            ($x + $rx) * $k,
            ($h - $y) * $k,
            $op
        ));
        $this->SetY($y + $ry);
        return $this;
    }

    /**
     * Dessine une grille
     *
     * @return $this
     */
    private function grid($spacing = 5)
    {
        $this->SetDrawColor(204, 255, 255);
        $this->SetLineWidth(0.35);
        for ($i = 0; $i < $this->w; $i += $spacing) {
            $this->Line($i, 0, $i, $this->h);
        }
        for ($i = 0; $i < $this->h; $i += $spacing) {
            $this->Line(0, $i, $this->w, $i);
        }
        $this->SetDrawColor(0, 0, 0);

        $x = $this->GetX();
        $y = $this->GetY();
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(204, 204, 204);
        for ($i = 20; $i < $this->h; $i += 20) {
            $this->SetXY(1, $i - 3);
            $this->Write(4, $i);
        }
        for ($i = 20; $i < (($this->w) - ($this->rMargin) - 10); $i += 20) {
            $this->SetXY($i - 1, 1);
            $this->Write(4, $i);
        }
        $this->SetXY($x, $y);
        return $this;
    }

    /**
     * Vérifie le nom de la forme géométrique
     *
     * @param string $formName Nom de la forme géométrique
     *
     * @return bool
     */
    private function hasForm($formName)
    {
        return in_array($formName, array_keys($this->formsOptions));
    }
}
