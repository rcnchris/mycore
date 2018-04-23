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
     * ### Exemple
     * - `$pdf->setRs([
     * 'wInPourc' => [30, 20, 50],
     * 'headerNames' => [utf8_decode('Méthode'), 'Syntaxe', 'Description'],
     * 'headerFont' => 'courier',
     * 'headerFontSize' => 14,
     * 'headerFontStyle' => 'B',
     * 'headerFill' => true,
     * 'headerAlign' => 'L'
     * ]);`
     *
     * @param array $params Paramètres du recordset
     *
     * @return $this
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
                if (!is_null($this->properties['headerFont']) && in_array(
                    $this->properties['headerFont'],
                    $this->getFonts()
                )
                ) {
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

        return $this;
    }

    /**
     * Obtenir toutes propriétés ou l'une d'entre elle
     *
     * @param string|null $key Nom de la clé de la propriété
     *
     * @return array|bool
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
     * Obtenir les propriétés de toutes colonnes ou de l'une d'entre elle
     *
     * @param int|null $indice Indice de la colonne (commence à 0)
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getRsPropertiesByCol($indice = null)
    {
        if (is_null($indice)) {
            return $this->propertiesByCol;
        } elseif (array_key_exists($indice, $this->propertiesByCol)) {
            return $this->propertiesByCol[$indice];
        }
        throw new \Exception("Le numéro de colonne $indice est inconnu !");
    }

    /**
     * Obtenir le nombre de colonnes du recorset
     *
     * ### Exemple
     * - `$pdf->getRsNbCols();`
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
     *
     * @return $this
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
        return $this;
    }

    /**
     * Imprimer le corps du recordset
     *
     * @param mixed $items
     *
     * @return $this
     */
    public function printRsBody($items)
    {
        $this->SetFont();
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
        return $this;
    }

    /**
     * Obtenir les noms des colonnes du RecordSet
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
        for ($i = 0; $i < $pos; $i++) {
            $x = $x + $this->getRsPropertiesByCol($i)['w'];
        }
        return $x;
    }
}
