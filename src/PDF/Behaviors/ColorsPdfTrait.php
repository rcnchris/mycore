<?php
/**
 * Fichier ColorsPdfTrait.php du 15/02/2018
 * Description : Fichier de la classe ColorsPdfTrait
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
 * Trait ColorsPdfTrait
 * <ul>
 * <li>Utiliser une palette de couleurs standard ou personnalisée dans un document PDF</li>
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
trait ColorsPdfTrait
{
    /**
     * Liste des couleurs
     *
     * @var array
     */
    private $colors = [
        'aliceblue' => '#F0F8FF',
        'aloha' => '#1ABC9C',
        'antiquewhite' => '#FAEBD7',
        'aquamarine' => '#7FFFD4',
        'azure' => '#F0FFFF',
        'beige' => '#F5F5DC',
        'bisque' => '#FFE4C4',
        'black' => '#000000',
        'blanchedalmond' => '#FFEBCD',
        'blue' => '#0000FF',
        'blueamalficoast' => '#2980b9',
        'bluedayflower' => '#3498db',
        'blueviolet' => '#8A2BE2',
        'brown' => '#A52A2A',
        'burlywood' => '#DEB887',
        'cadetblue' => '#5F9EA0',
        'cadillaccoupe' => '#c0392b',
        'chartreuse' => '#7FFF00',
        'chocolate' => '#D2691E',
        'coral' => '#FF7F50',
        'cornflowerblue' => '#6495ED',
        'cornsilk' => '#FFF8DC',
        'crimson' => '#DC143C',
        'cyan' => '#00FFFF',
        'darkblue' => '#00008B',
        'darkcyan' => '#008B8B',
        'darkdenim' => '#34495e',
        'darkgoldenrod' => '#B8860B',
        'darkgray' => '#A9A9A9',
        'darkgreen' => '#006400',
        'darkkhaki' => '#BDB76B',
        'darkmagenta' => '#8B008B',
        'darkolivegreen' => '#556B2F',
        'darkorange' => '#FF8C00',
        'darkorchid' => '#9932CC',
        'darkred' => '#8B0000',
        'darksalmon' => '#E9967A',
        'darkseagreen' => '#8DBC8F',
        'darkslateblue' => '#483D8B',
        'darkslategray' => '#2F4F4F',
        'darksnaphot' => '#2c3e50',
        'darkturquoise' => '#00DED1',
        'darkviolet' => '#9400D3',
        'deeppink' => '#FF1493',
        'deepskyblue' => '#00BFFF',
        'dimgray' => '#696969',
        'dodgerblue' => '#1E90FF',
        'firebrick' => '#B22222',
        'floralwhite' => '#FFFAF0',
        'forestgreen' => '#228B22',
        'fuchsia' => '#FF00FF',
        'gainsboro' => '#DCDCDC',
        'ghostwhite' => '#F8F8FF',
        'gold' => '#FFD700',
        'goldenrod' => '#DAA520',
        'gray' => '#808080',
        'grayanon' => '#bdc3c7',
        'graydustysky' => '#95a5a6',
        'grayfrostedglass' => '#ecf0f1',
        'grayghost' => '#7f8c8d',
        'graylight' => '#CCCCCC',
        'green' => '#008000',
        'greenflamboyant' => '#16a085',
        'greenisland' => '#27ae60',
        'greenufo' => '#2ECC71',
        'greenyellow' => '#ADFF2F',
        'honeydew' => '#F0FFF0',
        'hotpink' => '#FF69B4',
        'indianred' => '#CD5C5C',
        'indigo' => '#4B0082',
        'ivory' => '#FFFFF0',
        'khaki' => '#F0E68C',
        'lavender' => '#E6E6FA',
        'lavenderblush' => '#FFF0F5',
        'lawngreen' => '#7CFC00',
        'lemonchiffon' => '#FFFACD',
        'lightblue' => '#ADD8E6',
        'lightcoral' => '#F08080',
        'lightcyan' => '#E0FFFF',
        'lightgoldenrodyellow' => '#FAFAD2',
        'lightgreen' => '#90EE90',
        'lightgrey' => '#D3D3D3',
        'lightpink' => '#FFB6C1',
        'lightsalmon' => '#FFA07A',
        'lightseagreen' => '#20B2AA',
        'lightskyblue' => '#87CEFA',
        'lightslategray' => '#778899',
        'lightsteelblue' => '#B0C4DE',
        'lightyellow' => '#FFFFE0',
        'lime' => '#00FF00',
        'limegreen' => '#32CD32',
        'linen' => '#FAF0E6',
        'maroon' => '#800000',
        'mediumaquamarine' => '#66CDAA',
        'mediumblue' => '#0000CD',
        'mediumorchid' => '#BA55D3',
        'mediumpurple' => '#9370DB',
        'mediumseagreen' => '#3CB371',
        'mediumslateblue' => '#7B68EE',
        'mediumspringgreen' => '#00FA9A',
        'mediumturquoise' => '#48D1CC',
        'mediumvioletred' => '#C71585',
        'midnightblue' => '#191970',
        'mintcream' => '#F5FFFA',
        'mistyrose' => '#FFE4E1',
        'moccasin' => '#FFE4B5',
        'navajowhite' => '#FFDEAD',
        'navy' => '#000080',
        'oldlace' => '#FDF5E6',
        'olive' => '#808000',
        'orange' => '#FFA500',
        'orangedarkcheddar' => '#e67e22',
        'orangedodgerollgold' => '#f39c12',
        'orangered' => '#FF4500',
        'orangetenne' => '#d35400',
        'orchid' => '#DA70D6',
        'palegoldenrod' => '#EEE8AA',
        'palegreen' => '#98FB98',
        'paleturquoise' => '#AFEEEE',
        'palevioletred' => '#DB7093',
        'papayawhip' => '#FFEFD5',
        'peachpuff' => '#FFDAB9',
        'peru' => '#CD853F',
        'pink' => '#FFC8CB',
        'plum' => '#DDA0DD',
        'powderblue' => '#B0E0E6',
        'purple' => '#800080',
        'purpledeeplilac' => '#9b59b6',
        'purplemoonshadow' => '#8e44ad',
        'red' => '#FF0000',
        'redcadillaccoupe' => '#c0392b',
        'redcarminepink' => '#e74c3c',
        'rosybrown' => '#BC8F8F',
        'royalblue' => '#4169E1',
        'saddlebrown' => '#8B4513',
        'salmon' => '#FA8072',
        'sandybrown' => '#F4A460',
        'seagreen' => '#2E8B57',
        'seashell' => '#FFF5EE',
        'sienna' => '#A0522D',
        'silver' => '#C0C0C0',
        'skyblue' => '#87CEEB',
        'slateblue' => '#6A5ACD',
        'snow' => '#FFFAFA',
        'springgreen' => '#00FF7F',
        'steelblue' => '#4682B4',
        'tan' => '#D2B48C',
        'teal' => '#008080',
        'thistle' => '#D8BFD8',
        'tomato' => '#FF6347',
        'turquoise' => '#40E0D0',
        'violet' => '#EE82EE',
        'wheat' => '#F5DEB3',
        'white' => '#FFFFFF',
        'whitesmoke' => '#F5F5F5',
        'yellow' => '#FFFF00',
        'yellowgreen' => '#9ACD32',
        'yellowtannedleather' => '#f1c40f'
    ];

    /**
     * Obtenir la liste des couleurs ou l'une d'entre elle
     * La recherche par code hexadécimal est gérée
     *
     * ### Exemple
     * - `$pdf->getColors();`
     * - `$pdf->getColors('gray');`
     * - `$pdf->getColors('#1ABC9C');`
     *
     * @param string|null $name  Nom ou code héxadécimal d'une couleur
     * @param bool        $toRgb Retourne les valeurs RGB si vrai
     *
     * @return array|string|bool
     * @throws \Exception
     */
    public function getColors($name = null, $toRgb = false)
    {
        $color = null;
        if (is_null($name)) {
            return $this->colors;
        }

        if ($name[0] === '#') {
            $color = array_search(strtoupper($name), $this->colors);
            if ($toRgb) {
                $color = $this->colorToRgb($this->getColors($color));
            }
        } elseif (array_key_exists($name, $this->colors)) {
            $color = $this->colors[$name];

            if ($toRgb) {
                $color = $this->colorToRgb($color);
            }
        }
        return $color;
    }

    /**
     * Obtenir les valeurs RGB d'une couleur au format héxadécimal
     *
     * ### Exemple
     * - `$pdf->colorToRgb('#CCCCCC');`
     *
     * @param string $color Code héxadécimal de 7 caractères ou nom d'une couleur de la palette
     *
     * @return array
     * @throws \Exception
     */
    public function colorToRgb($color)
    {
        $hexa = null;
        $hexa = is_string($color) && $color[0] != '#'
            ? $this->getColors($color)
            : $color;

        if (is_null($hexa) || !$hexa) {
            throw new \Exception("Couleur $color introuvable");
        } elseif (strlen($hexa) != 7) {
            throw new \Exception("Code héxadécimal : '$hexa' de mauvaise longueur");
        }
        return [
            'r' => hexdec(substr($hexa, 1, 2)),
            'g' => hexdec(substr($hexa, 3, 2)),
            'b' => hexdec(substr($hexa, 5, 2))
        ];
    }

    /**
     * Ajoute ou remplace une couleur
     *
     * ### Exemple
     * - `$colors->addColor('deeplilac', '#BD58JU');`
     *
     * @param string $name Nom de la couleur
     * @param string $hexa Code héxadécimal de la couleur
     *
     * @return $this
     */
    public function addColor($name, $hexa)
    {
        $this->colors[$name] = strtoupper($hexa);
        return $this;
    }

    /**
     * Définir de nouvelles couleurs
     *
     * ### Exemple
     * - `$pdf->setColors($new);`
     *
     * @param array $colors Tableau des couleurs
     *
     * @return $this
     */
    public function setColors(array $colors)
    {
        if (!empty($colors)) {
            $this->colors = $colors;
        }
        return $this;
    }

    /**
     * Définir la couleur du type d'outil
     *
     * ### Exemple
     * - `$pdf->setColor('gray');`
     * - `$pdf->setColor('#CCCCCC');`
     * - `$pdf->setColor('beige', 'fill');`
     *
     * @param string      $color Nom de la couleur
     * @param string|null $tool  Type d'outil à colorer (text, fill, draw)
     *
     * @return $this
     * @throws \Exception
     */
    protected function setColor($color, $tool = 'text')
    {
        $method = 'Set' . strtolower($tool) . 'Color';
        $rgb = $this->colorToRgb($color);
        $this->$method($rgb['r'], $rgb['g'], $rgb['b']);
        return $this;
    }

    /**
     * Vérifie la présence d'une couleur dans la palette
     *
     * @param string $name Nom ou code héxadécimal d'une couleur
     *
     * @return bool
     */
    public function hasColor($name)
    {
        if (!$this->getColors($name)) {
            return false;
        }
        return true;
    }

    /**
     * Imprime les informations du trait
     *
     * @throws \Exception
     */
//    public function infosColorsPdfTrait()
//    {
//        $this->AddPage();
//
//        $this->title('Couleurs', 1);
//        $this->alert(
//            "Permet de disposer d'une palette de couleurs et de faire référence à des couleurs nommées." .
//            " Il est possible de définir une tablette personnalisée."
//        );
//
//        $this->printInfoClass(ColorsPdfTrait::class);
//
//        $this->title('getColors', 2);
//        $this->addLine();
//        $this->MultiCell(0, 10, utf8_decode("Obtenir toutes les couleurs"));
//        $this->codeBloc("\$pdf->getColors();");
//
//        $this->title('Couleurs disponibles', 3);
//        $ln = 0;
//        foreach ($this->getColors() as $name => $hexa) {
//            $this->setToolColor($hexa, 'fill');
//            $this->Cell(10, 5, '', 0, $ln, '', true);
//            if ($this->GetX() + 10 > 190) {
//                $ln = 1;
//            } else {
//                $ln = 0;
//            }
//        }
//        $this->Ln();
//
//        $this->MultiCell(0, 10, utf8_decode("Obtenir le code héxadécimal d'une couleur par son nom"));
//        $this->codeBloc("\$pdf->getColors('aloha');");
//        $this->SetFont(null, 'BI');
//        $this->MultiCell(0, 10, "Retourne :");
//        $this->codeBloc(serialize($this->getColors('aloha')));
//
//        $this->MultiCell(0, 10, utf8_decode("Obtenir le nom d'une couleur par son code héxadécimal"));
//        $this->codeBloc("\$pdf->getColors('#1ABC9C');");
//        $this->SetFont(null, 'BI');
//        $this->MultiCell(0, 10, "Retourne :");
//        $this->codeBloc(serialize($this->getColors('#1ABC9C')));
//
//        $this->MultiCell(0, 10, utf8_decode("Obtenir les valeurs RGB d'une couleur."));
//        $this->codeBloc("\$pdf->getColors('aloha', true);");
//        $this->SetFont(null, 'BI');
//        $this->MultiCell(0, 10, "Retourne :");
//        $this->codeBloc(serialize($this->getColors('aloha', true)));
//        $this->Ln();
//
//        $this->title('colorToRgb', 2);
//        $this->MultiCell(0, 10, utf8_decode("Obtenir les valeurs RGB d'une couleur au format héxadécimal."));
//        $this->codeBloc("\$pdf->colorToRgb('#CCCCCC');");
//        $this->SetFont(null, 'BI');
//        $this->MultiCell(0, 10, "Retourne :");
//        $this->codeBloc(serialize($this->colorToRgb('#CCCCCC')));
//        $this->Ln();
//
//        $this->title('hasColor', 2);
//        $this->addLine();
//        $this->MultiCell(
//            0,
//            10,
//            utf8_decode("Vérifier la présence d'une couleur. Accepte le nom ou le code héxadécimal.")
//        );
//        $this->codeBloc("\$pdf->hasColor('aloha');");
//        $this->SetFont(null, 'BI');
//        $this->MultiCell(0, 10, "Retourne :");
//        $this->codeBloc(serialize($this->hasColor('aloha')));
//        $this->Ln();
//
//        $this->title('addColor', 2);
//        $this->addLine();
//        $this->MultiCell(0, 10, utf8_decode("Ajouter une couleur."));
//        $this->codeBloc("\$pdf->addColor('pinkjigglypuff', '#ff9ff3');");
//        $this->addColor('pinkjigglypuff', '#ff9ff3');
//        $this->Ln();
//
//        $this->title('setColors', 2);
//        $this->addLine();
//        $this->MultiCell(0, 10, utf8_decode("Définir une palette personnalisée."));
//        $this->codeBloc("\$pdf->setColors(['pinkjigglypuff' => '#ff9ff3', 'yellowcasandora' => '#feca57');");
//        $this->Ln();
//    }
}
