<?php
/**
 * Fichier Common.php du 28/10/2017
 * Description : Fichier de la classe Common
 *
 * PHP version 5
 *
 * @category Outils
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Tools;

/**
 * Class Common
 * <ul>
 * <li>Classe statique qui fournit des méthodes diverses.</li>
 * </ul>
 *
 * @category Outils
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.0.1>
 */
class Common
{
    /**
     * Couleurs HTML
     *
     * @var array
     */
    protected static $colors = [
        'aliceblue' => '#F0F8FF',
        'antiquewhite' => '#FAEBD7',
        'aqua' => '#00FFFF',
        'aquamarine' => '#7FFFD4',
        'azure' => '#F0FFFF',
        'beige' => '#F5F5DC',
        'bisque' => '#FFE4C4',
        'black' => '#000000',
        'blanchedalmond' => '#FFEBCD',
        'blue' => '#0000FF',
        'blueviolet' => '#8A2BE2',
        'brown' => '#A52A2A',
        'burlywood' => '#DEB887',
        'cadetblue' => '#5F9EA0',
        'chartreuse' => '#7FFF00',
        'chocolate' => '#D2691E',
        'coral' => '#FF7F50',
        'cornflowerblue' => '#6495ED',
        'cornsilk' => '#FFF8DC',
        'crimson' => '#DC143C',
        'cyan' => '#00FFFF',
        'darkblue' => '#00008B',
        'darkcyan' => '#008B8B',
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
        'graylight' => '#CCCCCC',
        'green' => '#008000',
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
        'magenta' => '#FF00FF',
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
        'orangered' => '#FF4500',
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
        'red' => '#FF0000',
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
        'yellowgreen' => '#9ACD32'
    ];

    /**
     * Retourne une taille en Bits pour une valeur donnée
     *
     * ### Exemple
     * - `$ext->bitsSize(123456)`
     * - `$ext->bitsSize(123456, 2)`
     * - `123456|bitsSize(2)`
     *
     * @param int      $value Valeur en octets
     * @param int|null $round Arrondi
     *
     * @return string
     */
    public static function bitsSize($value, $round = 0)
    {
        $sizes = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];
        for ($i = 0; $value > 1024 && $i < count($sizes) - 1; $i++) {
            $value /= 1024;
        }
        return round($value, $round) . $sizes[$i];
    }

    /**
     * Obtenir un tableau à partir d'une variable.
     *
     * @param object|mixed $var Objet à transformer
     *
     * @return array
     */
    public static function toArray($var)
    {
        if (is_array($var)) {
            return $var;
        }
        $ret = [];
        if (is_object($var)) {
            foreach ($var as $properties => $value) {
                $ret[$properties] = $value;
            }
        }
        return $ret;
    }

    /**
     * Retourne la quantité de mémoire allouée par PHP
     *
     * <code>$m = Common::getMemoryUse();</code>
     *
     * @param bool|null $peak  Mémoire max
     * @param bool|null $octet Retour en octets
     *
     * @return int|string
     */
    public static function getMemoryUse($peak = true, $octet = false)
    {
        $octets = $peak
            ? memory_get_peak_usage(true)
            : memory_get_usage(true);
        return $octet
            ? $octets
            : self::bitsSize($octets, 2);
    }

    /**
     * Obtenir le contenur d'un fichier json sous la forme d'un objet
     *
     * @param string    $path    Chemin du fichier json
     * @param bool|null $toArray Un tableau est retourné plutôt que un `stdClass`
     *
     * @return array|bool|\stdClass
     */
    public static function getJsonFileContent($path, $toArray = false)
    {
        if (file_exists($path)) {
            $content = json_decode(file_get_contents($path), true);
            if (is_array($content)) {
                if ($toArray) {
                    return $content;
                }
                $o = new \stdClass();
                foreach ($content as $key => $value) {
                    $o->$key = $value;
                }
                return $o;
            }
        }
        return false;
    }

    /**
     * Obtenir la liste des ports utilisés par les services ou l'en d'entre eux
     *
     * @param string|null $serviceName Nom du service
     * @param string|null $protocol    Nom du protocole (tcp ou udp)
     *
     * @return array|int
     */
    public static function getPortOfServices($serviceName = null, $protocol = 'tcp')
    {
        $services = ['http', 'ftp', 'ssh', 'telnet', 'imap', 'smtp', 'nicname', 'gopher', 'finger', 'pop3', 'www'];
        $protocoles = ['tcp', 'udp'];
        if (!is_null($protocol) && !in_array($protocol, $protocoles)) {
            $protocol = 'tcp';
        }
        if (is_null($serviceName)) {
            $items = [];
            foreach ($services as $service) {
                $items[$service] = getservbyname($service, $protocol);
            }
            return $items;
        } elseif (in_array($serviceName, $services)) {
            return getservbyname($serviceName, $protocol);
        }
        return false;
    }

    /**
     * Obtenir le service Internet qui correspond au port et protocole
     *
     * ### Exemple
     * - `Common::getServiceOfPort(21);`
     * - `Common::getServiceOfPort(80, 'udp');`
     *
     * @param int         $port     Numéro de port
     * @param string|null $protocol Protocole du service (tcp ou udp)
     *
     * @return bool|string
     */
    public static function getServiceOfPort($port = 0, $protocol = 'tcp')
    {
        if (in_array($protocol, ['tcp', 'udp'])) {
            return getservbyport($port, $protocol);
        }
        return false;
    }

    /**
     * Obtenir les parties d'une URL
     *
     * @param string      $url  URL
     * @param string|null $part Partie de l'URL à retourner
     *
     * @return string|bool
     */
    public static function getUrlParts($url, $part = null)
    {
        $partsKeys = ['scheme', 'host', 'path', 'query'];
        $parts = parse_url($url);
        if (is_null($part)) {
            return $parts;
        } elseif (in_array($part, $partsKeys)) {
            return $parts[$part];
        }
        return false;
    }
}
