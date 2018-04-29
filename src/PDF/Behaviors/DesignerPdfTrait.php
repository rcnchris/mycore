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
 * <li>Est chargé de dessiner des formes</li>
 * <ul>
 * <li>Ligne</li>
 * <li>Rectangle (avec coins arrondis)</li>
 * <li>Cercle</li>
 * <li>Ellipse</li>
 * <li>Grille graduée</li>
 * <li>Code à barres EAN13 et UPC-A</li>
 * </ul>
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
        ],
        'ean' => [
            'methodName' => 'ean13',
            'params' => [
                'x' => 0,
                'y' => 0,
                'barcode' => '',
                'h' => 16,
                'w' => .35
            ]
        ],
        'upca' => [
            'methodName' => 'upca',
            'params' => [
                'x' => 0,
                'y' => 0,
                'barcode' => '',
                'h' => 16,
                'w' => .35
            ]
        ],
        'code39' => [
            'methodName' => 'code39',
            'params' => [
                'x' => 0,
                'y' => 0,
                'code' => '',
                'baseline' => .5,
                'h' => 5
            ]
        ],
        'tree' => [
            'methodName' => 'tree',
            'params' => [
                'data' => [],
                'x' => 0,
                'nodeFormat' => '+%k',
                'childFormat' => '-%k: %v',
                'w' => 20,
                'h' => 5,
                'border' => 1,
                'fill' => false,
                'align' => '',
                'indent' => 1,
                'vspacing' => 1,
                'drawlines' => true,
                'level' => 0,
                'hcell' => [],
                'treeHeight' => 0.00
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
        $params = array_merge($this->formsOptions[$formName]['params'], $options);
        return call_user_func_array([$this, $this->formsOptions[$formName]['methodName']], $params);
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
     * @param int $spacing Echelle de la grille
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
     * Imprime un code à barre de type EAN13
     *
     * ### Exemple
     * - `$pdf->ean13(10, 25, '1234567890123');`
     * - `$pdf->ean13(10, 25, '1234567890123', 16, .35);`
     *
     * @param double $x       Position de X
     * @param double $y       Position de Y
     * @param string $barcode Valeur du code à barres
     * @param int    $h       Hauteur du code à barres
     * @param float  $w       Epaisseur d'une barre
     *
     * @return $this
     */
    private function ean13($x, $y, $barcode, $h = 16, $w = .35)
    {
        $this->barcode($x, $y, $barcode, $h, $w, 13);
        return $this;
    }

    /**
     * Imprime un code à barre de type UPC-A
     *
     * @param double $x       Position de X
     * @param double $y       Position de Y
     * @param string $barcode Valeur du code à barres
     * @param int    $h       Hauteur du code à barres
     * @param float  $w       Epaisseur d'une barre
     *
     * @return $this
     */
    private function upca($x, $y, $barcode, $h = 16, $w = .35)
    {
        $this->barcode($x, $y, $barcode, $h, $w, 12);
        return $this;
    }

    /**
     * Dessine un code barres selon la norme Code 39.
     * Ce type de code-barres peut encoder les chaînes composées des caractères suivants :
     * - chiffres (0 à 9),
     * - lettres majuscules (A à Z) ainsi que 8 autres caractères (- . espace $ / + % *).
     *
     * @param double     $x        Abscisse du code barres
     * @param double     $y        Ordonnée du code barres
     * @param string     $code     Code
     * @param float|null $baseline Epaisseur
     * @param int|null   $height   Hauteur
     *
     * @return $this
     */
    private function code39($x, $y, $code, $baseline = 0.5, $height = 5)
    {
        $wide = $baseline;
        $narrow = $baseline / 3;
        $gap = $narrow;

        $barChar['0'] = 'nnnwwnwnn';
        $barChar['1'] = 'wnnwnnnnw';
        $barChar['2'] = 'nnwwnnnnw';
        $barChar['3'] = 'wnwwnnnnn';
        $barChar['4'] = 'nnnwwnnnw';
        $barChar['5'] = 'wnnwwnnnn';
        $barChar['6'] = 'nnwwwnnnn';
        $barChar['7'] = 'nnnwnnwnw';
        $barChar['8'] = 'wnnwnnwnn';
        $barChar['9'] = 'nnwwnnwnn';
        $barChar['A'] = 'wnnnnwnnw';
        $barChar['B'] = 'nnwnnwnnw';
        $barChar['C'] = 'wnwnnwnnn';
        $barChar['D'] = 'nnnnwwnnw';
        $barChar['E'] = 'wnnnwwnnn';
        $barChar['F'] = 'nnwnwwnnn';
        $barChar['G'] = 'nnnnnwwnw';
        $barChar['H'] = 'wnnnnwwnn';
        $barChar['I'] = 'nnwnnwwnn';
        $barChar['J'] = 'nnnnwwwnn';
        $barChar['K'] = 'wnnnnnnww';
        $barChar['L'] = 'nnwnnnnww';
        $barChar['M'] = 'wnwnnnnwn';
        $barChar['N'] = 'nnnnwnnww';
        $barChar['O'] = 'wnnnwnnwn';
        $barChar['P'] = 'nnwnwnnwn';
        $barChar['Q'] = 'nnnnnnwww';
        $barChar['R'] = 'wnnnnnwwn';
        $barChar['S'] = 'nnwnnnwwn';
        $barChar['T'] = 'nnnnwnwwn';
        $barChar['U'] = 'wwnnnnnnw';
        $barChar['V'] = 'nwwnnnnnw';
        $barChar['W'] = 'wwwnnnnnn';
        $barChar['X'] = 'nwnnwnnnw';
        $barChar['Y'] = 'wwnnwnnnn';
        $barChar['Z'] = 'nwwnwnnnn';
        $barChar['-'] = 'nwnnnnwnw';
        $barChar['.'] = 'wwnnnnwnn';
        $barChar[' '] = 'nwwnnnwnn';
        $barChar['*'] = 'nwnnwnwnn';
        $barChar['$'] = 'nwnwnwnnn';
        $barChar['/'] = 'nwnwnnnwn';
        $barChar['+'] = 'nwnnnwnwn';
        $barChar['%'] = 'nnnwnwnwn';

        $this->SetFont('Arial', '', 10);
        $this->Text($x, $y + $height + 4, $code);
        $this->SetFillColor(0);

        $code = '*' . strtoupper($code) . '*';
        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            if (!isset($barChar[$char])) {
                $this->Error('Invalid character in barcode: ' . $char);
            }
            $seq = $barChar[$char];
            for ($bar = 0; $bar < 9; $bar++) {
                if ($seq[$bar] == 'n') {
                    $lineWidth = $narrow;
                } else {
                    $lineWidth = $wide;
                }
                if ($bar % 2 == 0) {
                    $this->Rect($x, $y, $lineWidth, $height, 'F');
                }
                $x += $lineWidth;
            }
            $x += $gap;
        }
        return $this;
    }

    /**
     * Imprime un code à barres de type ean13 ou upca
     *
     * @param double $x       Position de X
     * @param double $y       Position de Y
     * @param string $barcode Valeur du code à barres
     * @param int    $h       Hauteur du code à barres
     * @param float  $w       Epaisseur d'une barre
     * @param int    $len     Longueur du code
     */
    private function barcode($x, $y, $barcode, $h, $w, $len)
    {
        //Ajoute des 0 si nécessaire
        $barcode = str_pad($barcode, $len - 1, '0', STR_PAD_LEFT);
        if ($len == 12) {
            $barcode = '0' . $barcode;
        }
        //Ajoute ou teste le chiffre de contrôle
        if (strlen($barcode) == 12) {
            $barcode .= $this->getBarcodeCheckDigit($barcode);
        } elseif (!$this->testBarcodeCheckDigit($barcode)) {
            $this->Error('Incorrect check digit');
        }
        //Convertit les chiffres en barres
        $codes = [
            'A' => [
                '0' => '0001101',
                '1' => '0011001',
                '2' => '0010011',
                '3' => '0111101',
                '4' => '0100011',
                '5' => '0110001',
                '6' => '0101111',
                '7' => '0111011',
                '8' => '0110111',
                '9' => '0001011'
            ],
            'B' => [
                '0' => '0100111',
                '1' => '0110011',
                '2' => '0011011',
                '3' => '0100001',
                '4' => '0011101',
                '5' => '0111001',
                '6' => '0000101',
                '7' => '0010001',
                '8' => '0001001',
                '9' => '0010111'
            ],
            'C' => [
                '0' => '1110010',
                '1' => '1100110',
                '2' => '1101100',
                '3' => '1000010',
                '4' => '1011100',
                '5' => '1001110',
                '6' => '1010000',
                '7' => '1000100',
                '8' => '1001000',
                '9' => '1110100'
            ]
        ];
        $parities = [
            '0' => ['A', 'A', 'A', 'A', 'A', 'A'],
            '1' => ['A', 'A', 'B', 'A', 'B', 'B'],
            '2' => ['A', 'A', 'B', 'B', 'A', 'B'],
            '3' => ['A', 'A', 'B', 'B', 'B', 'A'],
            '4' => ['A', 'B', 'A', 'A', 'B', 'B'],
            '5' => ['A', 'B', 'B', 'A', 'A', 'B'],
            '6' => ['A', 'B', 'B', 'B', 'A', 'A'],
            '7' => ['A', 'B', 'A', 'B', 'A', 'B'],
            '8' => ['A', 'B', 'A', 'B', 'B', 'A'],
            '9' => ['A', 'B', 'B', 'A', 'B', 'A']
        ];
        $code = '101';
        $p = $parities[$barcode[0]];
        for ($i = 1; $i <= 6; $i++) {
            $code .= $codes[$p[$i - 1]][$barcode[$i]];
        }
        $code .= '01010';
        for ($i = 7; $i <= 12; $i++) {
            $code .= $codes['C'][$barcode[$i]];
        }
        $code .= '101';
        //Dessine les barres
        for ($i = 0; $i < strlen($code); $i++) {
            if ($code[$i] == '1') {
                $this->Rect($x + $i * $w, $y, $w, $h, 'F');
            }
        }
        // Imprime le texte sous le code-barres
        $this->SetFont('Arial', '', 12);
        $this->Text($x, $y + $h + 11 / $this->k, substr($barcode, -$len));
    }

    /**
     * Calcule le chiffre de contrôle d'un code barre
     *
     * @param string $barcode Valeur du code à barres
     *
     * @return int
     */
    private function getBarcodeCheckDigit($barcode)
    {
        $sum = 0;
        for ($i = 1; $i <= 11; $i += 2) {
            $sum += 3 * $barcode[$i];
        }
        for ($i = 0; $i <= 10; $i += 2) {
            $sum += $barcode[$i];
        }
        $r = $sum % 10;
        if ($r > 0) {
            $r = 10 - $r;
        }
        return $r;
    }

    /**
     * Vérifie le chiffre de contrôle
     *
     * @param string $barcode Valeur du code à barres
     *
     * @return bool
     */
    private function testBarcodeCheckDigit($barcode)
    {
        $sum = 0;
        for ($i = 1; $i <= 11; $i += 2) {
            $sum += 3 * $barcode[$i];
        }
        for ($i = 0; $i <= 10; $i += 2) {
            $sum += $barcode[$i];
        }
        return ($sum + $barcode[12]) % 10 == 0;
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

    /**
     * Dessine un arbre à partir d'un tableau de contenu
     *
     * @param        $data
     * @param int    $x
     * @param string $nodeFormat
     * @param string $childFormat
     * @param int    $w
     * @param int    $h
     * @param int    $border
     * @param bool   $fill
     * @param string $align
     * @param int    $indent
     * @param int    $vspacing
     * @param bool   $drawlines
     * @param int    $level
     * @param array  $hcell
     * @param float  $treeHeight
     *
     * @return float
     */
    private function tree(
        $data,
        $x = 0,
        $nodeFormat = '+%k',
        $childFormat = '-%k: %v',
        $w = 20,
        $h = 5,
        $border = 1,
        $fill = false,
        $align = '',
        $indent = 1,
        $vspacing = 1,
        $drawlines = true,
        $level = 0,
        $hcell = [],
        $treeHeight = 0.00
    ) {
        if (is_array($data)) {
            $countData = count($data);
            $c = 0;
            $hcell[$level] = array();
            foreach ($data as $key => $value) {
                $this->SetXY($x + $this->lMargin + ($indent * $level), $this->GetY() + $vspacing);
                if (is_array($value)) {
                    $pStr = str_replace('%k', $key, $nodeFormat);
                } else {
                    $pStr = str_replace('%k', $key, $childFormat);
                    $pStr = str_replace('%v', $value, $pStr);
                }
                $pStr = str_replace("\r", '', $pStr);
                $pStr = str_replace("\t", '', $pStr);
                while (ord(substr($pStr, -1, 1)) == 10) {
                    $pStr = substr($pStr, 0, (strlen($pStr) - 1));
                }
                $line = explode("\n", $pStr);
                $rows = 0;
                $addLines = 0;
                foreach ($line as $l) {
                    $widthLine = $this->GetStringWidth($l);
                    $rows = $widthLine / $w;
                    if ($rows > 1) {
                        $addLines += ($widthLine % $w == 0) ? $rows - 1 : $rows;
                    }
                }
                $hcell[$level][$c] = intval(count($line) + $addLines) * $h;
                $this->MultiCell($w, $h, $pStr, $border, $align, $fill);
                $x1 = $x + $this->lMargin + ($indent * $level);
                $y1 = $this->GetY() - ($hcell[$level][$c] / 2);
                if ($drawlines) {
                    $this->Line($x1, $y1, $x1 - $indent, $y1);
                }
                if ($c == $countData - 1) {
                    $x1 = $x + $this->lMargin + ($indent * $level) - $indent;
                    $halfHeight = 0;
                    if (isset($hcell[$level - 1])) {
                        $lastKeys = array_keys($hcell[$level - 1]);
                        $lastKey = $lastKeys[count($lastKeys) - 1];
                        $halfHeight = $hcell[$level - 1][$lastKey] / 2;
                    }
                    $y2 = $y1 - $treeHeight - ($hcell[$level][$c] / 2) - $halfHeight - $vspacing;
                    if ($drawlines) {
                        $this->Line($x1, $this->GetY() - ($hcell[$level][$c] / 2), $x1, $y2);
                    }
                }
                if (is_array($value)) {
                    $treeHeight += $this->tree($value, $x, $nodeFormat, $childFormat, $w, $h, $border, $fill,
                        $align, $indent, $vspacing, $drawlines, $level + 1, $hcell);
                }
                $treeHeight += $hcell[$level][$c] + $vspacing;
                $c++;
            }
            return $treeHeight;
        }
    }
}
