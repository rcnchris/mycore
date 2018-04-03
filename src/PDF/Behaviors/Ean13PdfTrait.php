<?php
/**
 * Fichier Ean13PdfTrait.php du 24/02/2018
 * Description : Fichier de la classe Ean13PdfTrait
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 */

namespace Rcnchris\Core\PDF\Behaviors;

/**
 * Trait Ean13PdfTrait
 * <ul>
 * <li>Permet d'imprimer des codes à barres selon la norme EAN13 et UPCA.</li>
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
trait Ean13PdfTrait
{

    /**
     * Imprime un code à barre de type EAN13
     *
     * @param double $x       Position de X
     * @param double $y       Position de Y
     * @param string $barcode Valeur du code à barres
     * @param int    $h       Hauteur du code à barres
     * @param float  $w       Epaisseur d'une barre
     */
    public function ean13($x, $y, $barcode, $h = 16, $w = .35)
    {
        $this->barcode($x, $y, $barcode, $h, $w, 13);
    }

    /**
     * Imprime un code à barre de type UPCA
     *
     * @param double $x       Position de X
     * @param double $y       Position de Y
     * @param string $barcode Valeur du code à barres
     * @param int    $h       Hauteur du code à barres
     * @param float  $w       Epaisseur d'une barre
     */
    public function upca($x, $y, $barcode, $h = 16, $w = .35)
    {
        $this->barcode($x, $y, $barcode, $h, $w, 12);
    }

    /**
     * Calcule le chiffre de contrôle
     *
     * @param string $barcode Valeur du code à barres
     *
     * @return int
     */
    private function getCheckDigit($barcode)
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
    private function testCheckDigit($barcode)
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
     * Imprime un code à barres
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
            $barcode .= $this->getCheckDigit($barcode);
        } elseif (!$this->testCheckDigit($barcode)) {
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
        //Imprime le texte sous le code-barres
        $this->SetFont('Arial', '', 12);
        $this->Text($x, $y + $h + 11 / $this->k, substr($barcode, -$len));
    }

    /**
     * Imprime les informations du trait
     */
    public function infosEan13PdfTrait()
    {
        $this->AddPage();
        $this->title('Codes à barres', 1);
        $this->alert("Permet d'imprimer des codes à barres selon la norme EAN13 et UPCA.");
        $this->printInfoClass(Ean13PdfTrait::class);

        $this->title('ean13', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode('Code à barres EAN13'));
        $this->codeBloc("\$pdf->ean13(100, 60, '123456789012', 5);");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Exemple :");
        $this->ean13(
            $this->lMargin,
            $this->GetY(),
            '123456789012',
            5
        );
        $this->Ln();

        $this->title('upca', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode('Code à barres UPCA.'));
        $this->codeBloc("\$pdf->upca(100, 60, '123456789012', 5);");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Exemple :");
        $this->upca(
            $this->lMargin,
            $this->GetY(),
            '123456789012',
            5
        );
    }
}
