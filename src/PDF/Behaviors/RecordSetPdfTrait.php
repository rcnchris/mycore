<?php
/**
 * Fichier RecordSetPdfTrait.php du 30/03/2018
 * Description : Fichier de la classe RecordSetPdfTrait
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
 * Trait RecordSetPdfTrait
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
trait RecordSetPdfTrait
{
    /**
     * Paramètres du recordset
     *
     * @var array
     */
    private $defaultProperties = [
        'w' => [],
        'wInPourc' => [],
        'h' => 5,
        'headerNames' => [],
        'headerFont' => null,
        'headerFontSize' => null,
        'headerFontStyle' => null,
        'headerFill' => false,
        'headerBorder' => null,
        'headerAlign' => null,
        'itemProperties' => [
            'fontFamily' => 'helvetica',
            'fontStyle' => '',
            'fontSize' => 8,
            'align' => 'L',
            'fill' => false,
            'fontColor' => '#000000',
        ]
    ];

    /**
     * Propriétés du recordset
     *
     * @var array
     */
    private $properties = [];

    /**
     * Propriétés de chaque colonne
     *
     * @var array
     */
    private $propertiesByCol = [];

    /**
     * Définir le nombre de colonnes et le style du recordset
     *
     * @param array $params Paramètres du recordset
     *
     * @throws \Exception
     */
    public function setRs(array $params = [])
    {
        $this->properties = array_merge($this->defaultProperties, $params);
        $this->propertiesByCol = [];

        // Nombre et largeur des colonnes
        if (!empty($this->properties['w'])) {
            $colsLength = array_sum($this->properties['w']);
            if ($colsLength > $this->getBodySize('width')) {
                $dep = $colsLength - $this->getBodySize('width');
                throw new \Exception("La somme des longueurs des colonnes dépasse la largeur du corps de $dep");
            }
            foreach ($this->properties['w'] as $width) {
                $this->propertiesByCol[] = [
                    'w' => $width
                ];
            }
        } elseif (!empty($this->properties['wInPourc'])) {
            foreach ($this->properties['wInPourc'] as $width) {
                $this->propertiesByCol[] = [
                    'w' => $this->getBodySize('width') * ($width / 100)
                ];
            }
        }
        if (empty($this->propertiesByCol)) {
            throw new \Exception("Le nombre de colonnes doit être définit par l'option 'w' ou 'wInPourc !'");
        }

        if (!empty($this->properties['headerNames'])) {
            // Contrôle cohérence nombre de colonnes
            if (count($this->properties['headerNames']) != $this->getRsNbCols()) {
                throw new \Exception(
                    "Le nombre de colonne diffère entre la définition via 'w' ou 'wInPourc' et 'headerNames' !"
                );
            }

            // Pour chaque colonne nommée
            $i = 0;
            foreach ($this->properties['headerNames'] as $k => $headerName) {

                /**
                 * Nom et style des entêtes de colonnes
                 */
                $this->propertiesByCol[$i]['header']['name'] = $headerName;

                // Police
                if (!is_null($this->properties['headerFont']) && $this->hasFont($this->properties['headerFont'])) {
                    $this->propertiesByCol[$i]['header']['fontFamily'] = $this->properties['headerFont'];
                } else {
                    $this->propertiesByCol[$i]['header']['fontFamily'] = $this->getFontProperty('family');
                }

                // Taille de la police
                if (!is_null($this->properties['headerFontSize']) && is_numeric($this->properties['headerFontSize'])) {
                    $this->propertiesByCol[$i]['header']['fontSize'] = $this->properties['headerFontSize'];
                } else {
                    $this->propertiesByCol[$i]['header']['fontSize'] = $this->getFontProperty('size');
                }

                // Style
                if (!is_null($this->properties['headerFontStyle']) && is_string($this->properties['headerFontStyle'])) {
                    $this->propertiesByCol[$i]['header']['fontStyle'] = $this->properties['headerFontStyle'];
                } else {
                    $this->propertiesByCol[$i]['header']['fontStyle'] = $this->getFontProperty('style');
                }

                // Fill
                $this->propertiesByCol[$i]['header']['fill'] = $this->properties['headerFill'];

                // Alignement
                if (!is_null($this->properties['headerAlign']) && is_string($this->properties['headerAlign'])) {
                    $this->propertiesByCol[$i]['header']['align'] = $this->properties['headerAlign'];
                } else {
                    $this->propertiesByCol[$i]['header']['align'] = 'L';
                }

                // Check largeur du nom dans la colonne
                $this->propertiesByCol[$i]['header']['checkWidthName'] = $this->checkRsWidthStringInCol(
                    $i,
                    $headerName
                );

                /**
                 * Style des items
                 */
                if (!empty($this->properties['itemProperties'])) {
                    if (is_array(current($this->properties['itemProperties']))) {
                        // Définition du style par colonne
                        $this->propertiesByCol[$i]['item'] = $this->defaultProperties['itemProperties'];
                        if (isset($this->properties['itemProperties'][$i])) {
                            $this->propertiesByCol[$i]['item'] = array_merge(
                                $this->defaultProperties['itemProperties'],
                                $this->properties['itemProperties'][$i]
                            );
                        }
                    } else {
                        // Même style pour toutes colonnes
                        $this->propertiesByCol[$i]['item'] = array_merge(
                            $this->defaultProperties['itemProperties'],
                            $this->properties['itemProperties']
                        );
                    }
                }
                $i++;
            }
        }

        if (isset($this->properties['items'])) {
            $this->setData($this->properties['items']);
        }
    }

    /**
     * Obtenir toutes propriétés ou l'une d'entre elle
     *
     * @param string|null $key Nom de la clé de la propriété
     *
     * @return array
     */
    public function getRsProperties($key = null)
    {
        if (is_null($key)) {
            return $this->properties;
        } elseif (array_key_exists($key, $this->properties)) {
            return $this->properties[$key];
        }
        return false;
    }

    /**
     * Obtenir les propriétés de toutes colonnes ou l'une d'entre elle
     *
     * @param int|null $indice Indice de la colonne (commence à 0)
     *
     * @return array
     */
    public function getRsPropertiesByCol($indice = null)
    {
        if (is_null($indice)) {
            return $this->propertiesByCol;
        }
        return $this->propertiesByCol[$indice];
    }

    /**
     * Obtenir le nombre de colonnes du recorset
     *
     * @return int|void
     */
    public function getRsNbCols()
    {
        return count($this->getRsPropertiesByCol());
    }

    /**
     * Vérifie que la colonne est assez large pour une chaîne de caractères
     *
     * @param int    $indice Numéro de la colonne (commence à 0)
     * @param string $string Chaîne de caractères à checker
     *
     * @return bool
     */
    public function checkRsWidthStringInCol($indice, $string)
    {
        return $this->getRsPropertiesByCol($indice)['w'] >= $this->GetStringWidth($string);
    }

    /**
     * Imprime l'entête du recordset
     */
    public function printRsHeader()
    {
        $this->SetFont();
        foreach ($this->getRsPropertiesByCol() as $indice => $properties) {
            $this->Cell(
                $properties['w'],
                $this->defaultProperties['h'],
                utf8_decode($this->getRsPropertiesByCol($indice)['header']['name']),
                0,
                0,
                $this->getRsPropertiesByCol($indice)['header']['align'],
                $this->getRsPropertiesByCol($indice)['header']['fill']
            );
        }
    }

    public function printRsBody($items = null)
    {
        $this->SetFont();
        if (is_null($items)) {
            $items = $this->properties['items'];
        }

        foreach ($items as $item) {
            foreach ($this->getRsPropertiesByCol() as $indCol => $confCol) {
                $this->SetFont(
                    $confCol['item']['fontFamily'],
                    $confCol['item']['fontStyle'],
                    $confCol['item']['fontSize'],
                    [
                        'color' => $confCol['item']['fontColor']
                    ]
                );
                $this->Cell(
                    $confCol['w'],
                    $this->defaultProperties['h'],
                    utf8_decode($item[$confCol['header']['name']]),
                    0,
                    0,
                    $confCol['item']['align'],
                    $confCol['item']['fill']
                );
            }
            $this->Ln();
        }
        $this->SetFont();
    }

    /**
     * Obtenir les noms des colonnes
     *
     * @return array
     */
    public function getRsHeadersName()
    {
        return $this->getRsProperties('headerNames');
    }

    /**
     * Obtenir la position X de départ d'une colonne
     *
     * @param double $pos Colonne du RS
     *
     * @return double
     */
    public function getRsX($pos)
    {
        $x = $this->lMargin;
        for ($i = 0 ; $i < $pos ; $i++) {
            $x = $x + $this->getRsPropertiesByCol($i)['w'];
        }
        return $x;
    }

    /**
     * Imprime les informations du traits
     * @throws \Exception
     */
    public function infosRecordSetPdfTrait()
    {
        $this->AddPage();
        $this->title('RecordSet', 1);
        $this->alert("Permet de disposer d'un recorset et de l'imprimer.");
        $this->printInfoClass(RecordSetPdfTrait::class);

        $this->title('setRs', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Définir les propriétés du recorset."));
        $this->codeBloc(
            "\$pdf->setRs([\n\t'wInPourc' => [20, 15, 15, 15, 15, 20],\n"
            . "\t'headerNames' => ['#', 'username', 'email', 'birthday', 'phone', 'mobile'],\n"
            . "\t'headerFontSize' => 8,\n"
            . "\t'headerFontStyle' => 'B',\n"
            . "\t'itemProperties' => [\n"
            . "\t\t[\n"
            . "\t\t\t'fontFamily' => 'courier',\n"
            . "\t\t\t'fontSize' => 6,\n"
            . "\t\t\t'fontStyle' => ''\n"
            . "\t\t],\n"
            . "\t\t[\n"
            . "\t\t\t'fontSize' => 8,\n"
            . "\t\t\t'fontStyle' => 'B'\n"
            . "\t\t]\n"
            . "\t]\n);"
        );



        $this->title('getRsNbCols', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Obtenir le nombre de colonne."));
        $this->codeBloc("\$pdf->getRsNbCols();");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getRsNbCols()));
        $this->Ln();

        $this->title('getRsProperties', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Obtenir les propriétés des colonnes."));
        $this->codeBloc("\$pdf->getRsProperties();");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getRsProperties()));
        $this->Ln();

        $this->title('getRsPropertiesByCol', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Obtenir les propriétés d'une colonne."));
        $this->codeBloc("\$pdf->getRsPropertiesByCol(1);");
        $this->SetFont(null, 'BI', null, ['color' => 'black']);
        $this->MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getRsPropertiesByCol(1)));
        $this->Ln();

        $this->title('printRsHeader', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Imprimer l'entête."));
        $this->codeBloc("\$pdf->printRsHeader();");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->printRsHeader();
        $this->Ln();

        $this->title('printRsBody', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Imprimer le détail."));
        $this->codeBloc("\$pdf->printRsBody();");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->printRsBody($this->properties['items']);
        $this->Ln();
    }
}
