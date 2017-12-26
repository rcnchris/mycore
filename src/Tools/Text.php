<?php
/**
 * Fichier Text.php du 03/11/2017
 * Description : Fichier de la classe Text
 *
 * PHP version 5
 *
 * @category Texte brut
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
 * Class Text<br/>
 * <ul>
 * <li>Classe statique de manipulation des chaînes de caractères</li>
 * </ul>
 *
 * @category Texte brut
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class Text
{
    /**
     * Transliterator par défaut
     *
     * @var string
     */
    protected static $defaultTransliteratorId = 'Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove';

    /**
     * Séparateurs
     *
     * @var array
     */
    private static $prefs = [
        'sepDec' => ',',
        'sepMil' => ' '
    ];

    /**
     * Compléter une chaîne de caractères
     *
     * @exemple Common::compl('1', '0', 3); --> '0001'
     *
     * @param string      $input  Chaîne à compléter
     * @param string      $compl  Complément
     * @param int|null    $lenght Longueur du complément
     * @param string|null $sens   Sens du complémént (left ou right)
     *
     * @return string
     */
    public static function compl($input, $compl, $lenght = 1, $sens = 'left')
    {
        $ret = '';
        for ($i = 1; $i <= $lenght; $i++) {
            $ret .= $compl;
        }
        return $sens === 'left'
            ? $ret . $input
            : $input . $ret;
    }

    /**
     * Obtenir un ID unique
     *
     * @exemple Text::uuid('ola'); --> '5997e8605bc0a7.42903584'
     *
     * @param string|null $prefixe Préfixe de l'ID à retourner
     *
     * @return string
     */
    public static function uuid($prefixe = null)
    {
        return uniqid($prefixe, true);
    }

    /**
     * Obtenir une chaîne de caractères sérialisée
     *
     * @exemple Text::serialize('ola'); --> 's:3:"ola";'
     *
     * @param string $value Valeur à sérialiser
     *
     * @return string
     */
    public static function serialize($value)
    {
        return serialize($value);
    }

    /**
     * Obtenir une variable à partir d'une chaîne sérialisée
     *
     * @exemple Text::unserialize('s:3:"ola";'); --> 'ola'
     *
     * @param string $value Valeur à désérialiser
     *
     * @return string
     */
    public static function unserialize($value)
    {
        return unserialize($value);
    }

    /**
     * Tronquer une chaîne de caractères en traitant les entités html
     *
     * @param string     $text    Rexte à tronquer
     * @param int        $length  Longueur
     * @param array|null $options Options de la demande
     *
     * @return string
     */
    public static function truncate($text, $length = 100, array $options = [])
    {
        $default = [
            'ellipsis' => '...',
            'exact' => true,
            'html' => false,
            'trimWidth' => false,
        ];
        if (!empty($options['html']) && strtolower(mb_internal_encoding()) === 'utf-8') {
            $default['ellipsis'] = "\xe2\x80\xa6";
        }
        $options += $default;

        $prefix = '';
        $suffix = $options['ellipsis'];

        if ($options['html']) {
            $ellipsisLength = self::strlen(strip_tags($options['ellipsis']), $options);

            $truncateLength = 0;
            $totalLength = 0;
            $openTags = [];
            $truncate = '';

            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                $contentLength = self::strlen($tag[3], $options);

                if ($truncate === '') {
                    if (!preg_match(
                        '/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/i',
                        $tag[2]
                    )
                    ) {
                        if (preg_match('/<[\w]+[^>]*>/', $tag[0])) {
                            array_unshift($openTags, $tag[2]);
                        } elseif (preg_match('/<\/([\w]+)[^>]*>/', $tag[0], $closeTag)) {
                            $pos = array_search($closeTag[1], $openTags);
                            if ($pos !== false) {
                                array_splice($openTags, $pos, 1);
                            }
                        }
                    }

                    $prefix .= $tag[1];

                    if ($totalLength + $contentLength + $ellipsisLength > $length) {
                        $truncate = $tag[3];
                        $truncateLength = $length - $totalLength;
                    } else {
                        $prefix .= $tag[3];
                    }
                }

                $totalLength += $contentLength;
                if ($totalLength > $length) {
                    break;
                }
            }

            if ($totalLength <= $length) {
                return $text;
            }

            $text = $truncate;
            $length = $truncateLength;

            foreach ($openTags as $tag) {
                $suffix .= '</' . $tag . '>';
            }
        } else {
            if (self::strlen($text, $options) <= $length) {
                return $text;
            }
            $ellipsisLength = self::strlen($options['ellipsis'], $options);
        }

        $result = self::substr($text, 0, $length - $ellipsisLength, $options);

        if (!$options['exact']) {
            if (self::substr($text, $length - $ellipsisLength, 1, $options) !== ' ') {
                $result = self::removeLastWord($result);
            }
            // Si le résultat est vide, nous n'avons pas besoin de compter l'ellipse dans la coupe.
            if (!strlen($result)) {
                $result = self::substr($text, 0, $length, $options);
            }
        }
        return $prefix . $result . $suffix;
    }

    /**
     * Tronquer une chaîne de caractères en commençant par la fin
     *
     * ### Options:
     * - `ellipsis` Préfixera la chaîne retournée
     * - `exact` Si faux, les mots ne sont pas coupés
     *
     * @param string     $text    Chaîne à tronquer.
     * @param int|null   $length  Longueur de la chaîne à retourner
     * @param array|null $options Options de la demande
     *
     * @return string
     */
    public static function tail($text, $length = 100, array $options = [])
    {
        $default = [
            'ellipsis' => '...',
            'exact' => true
        ];
        $options += $default;
        $exact = $ellipsis = null;
        extract($options);
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        $truncate = mb_substr($text, mb_strlen($text) - $length + mb_strlen($ellipsis));
        if (!$exact) {
            $spacepos = mb_strpos($truncate, ' ');
            $truncate = $spacepos === false
                ? ''
                : trim(mb_substr($truncate, $spacepos));
        }
        return $ellipsis . $truncate;
    }

    /**
     * Vérifier si la chaîne contient des caractères multibits
     *
     * @param string $string Chaîne à vérifier
     *
     * @return bool
     */
    public static function isMultibyte($string)
    {
        $length = strlen($string);
        for ($i = 0; $i < $length; $i++) {
            $value = ord($string[$i]);
            if ($value > 128) {
                return true;
            }
        }
        return false;
    }

    /**
     * Convertir une chaîne de caractères multi-octets en valeur décimale
     *
     * @exemple Text::utf8("ù\no"); --> [249, 10, 111]
     *
     * @param string $string Chaîne dont les caractères doivent être convertis
     *
     * @return array
     */
    public static function utf8($string)
    {
        $map = [];
        $values = [];
        $find = 1;
        $length = strlen($string);
        for ($i = 0; $i < $length; $i++) {
            $value = ord($string[$i]);

            if ($value < 128) {
                $map[] = $value;
            } else {
                if (empty($values)) {
                    $find = ($value < 224) ? 2 : 3;
                }
                $values[] = $value;
                if (count($values) === $find) {
                    if ($find == 3) {
                        $map[] = (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64);
                    } else {
                        $map[] = (($values[0] % 32) * 64) + ($values[1] % 64);
                    }
                    $values = [];
                    $find = 1;
                }
            }
        }
        return $map;
    }

    /**
     * Convertir la valeur décimale d'une chaîne de caractères multi-octets en chaîne
     *
     * @exemple Text::ascii([249, 10, 111]); --> 'ù o'
     *
     * @param array $array Tableau de caractères
     *
     * @return string
     */
    public static function ascii(array $array)
    {
        $ascii = '';
        foreach ($array as $utf8) {
            if ($utf8 < 128) {
                $ascii .= chr($utf8);
            } elseif ($utf8 < 2048) {
                $ascii .= chr(192 + (($utf8 - ($utf8 % 64)) / 64));
                $ascii .= chr(128 + ($utf8 % 64));
            } else {
                $ascii .= chr(224 + (($utf8 - ($utf8 % 4096)) / 4096));
                $ascii .= chr(128 + ((($utf8 % 4096) - ($utf8 % 64)) / 64));
                $ascii .= chr(128 + ($utf8 % 64));
            }
        }
        return $ascii;
    }

    /**
     * Obtenir le Transliterator
     *
     * @return string
     */
    public static function getTransliteratorId()
    {
        return static::$defaultTransliteratorId;
    }

    /**
     * Définir le Transliterator
     *
     * @param string $transliteratorId Transliterator id.
     *
     * @return void
     */
    public static function setTransliteratorId($transliteratorId)
    {
        static::$defaultTransliteratorId = $transliteratorId;
    }

    /**
     * Appliquer une transliteration
     *
     * @param string      $string           Chaîne à traiter
     * @param string|null $transliteratorId Id
     *
     * @return string
     */
    public static function transliterate($string, $transliteratorId = null)
    {
        $transliteratorId = $transliteratorId
            ?: static::$defaultTransliteratorId;
        return transliterator_transliterate($transliteratorId, $string);
    }

    /**
     * Obtenir le slug d'une chaîne
     *
     * @exemple Text::slug('ola les gens, ca va ?'); --> 'ola-les-gens-ca-va'
     *
     * @param string $string  Chaîne à traiter
     * @param array  $options Options de la demande
     *
     * @return string
     */
    public static function slug($string, $options = [])
    {
        if (is_string($options)) {
            $options = ['replacement' => $options];
        }
        $options += [
            'replacement' => '-',
            'transliteratorId' => null,
            'preserve' => null
        ];

        if ($options['transliteratorId'] !== false) {
            $string = static::transliterate($string, $options['transliteratorId']);
        }

        $regex = '^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}';
        if ($options['preserve']) {
            $regex .= '(' . preg_quote($options['preserve'], '/') . ')';
        }
        $quotedReplacement = preg_quote($options['replacement'], '/');
        $map = [
            '/[' . $regex . ']/mu' => ' ',
            '/[\s]+/mu' => $options['replacement'],
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        ];
        $string = preg_replace(array_keys($map), $map, $string);
        return strtolower($string);
    }

    /**
     * Obtenir un nombre formaté
     *
     * @param mixed       $value Valeur à formater
     * @param int|null    $nbDec Nombre de décimales
     * @param string|null $dec   Caractère de la décimale
     * @param string|null $sep   Séparateur de millier
     *
     * @return string
     */
    public static function formatNumber($value, $nbDec = 0, $dec = null, $sep = null)
    {
        if (gettype($value) === 'string') {
            $value = floatval($value);
        }
        $dec = is_null($dec) ? self::$prefs['sepDec'] : $dec;
        $sep = is_null($sep) ? self::$prefs['sepMil'] : $sep;
        return number_format($value, $nbDec, $dec, $sep);
    }

    /**
     * Obtenir la longueur d'une chaîne de caractères
     *
     * ### Options :
     * - `html` Si vrai, les entités HTML seront traitées comme des caractères décodés
     * - `trimWidth` Si vrai, la longueur est retournée
     *
     * @param string $text    Texte à compter
     * @param array  $options Options de la demande
     *
     * @return int
     */
    protected static function strlen($text, array $options)
    {
        $strlen = empty($options['trimWidth'])
            ? 'mb_strlen'
            : 'mb_strwidth';
        if (empty($options['html'])) {
            return $strlen($text);
        }
        $pattern = '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i';
        $replace = preg_replace_callback(
            $pattern,
            function ($match) use ($strlen) {
                $utf8 = html_entity_decode($match[0], ENT_HTML5 | ENT_QUOTES, 'UTF-8');
                return str_repeat(' ', $strlen($utf8, 'UTF-8'));
            },
            $text
        );
        return $strlen($replace);
    }

    /**
     * Obtenir une partie d'une chaîne
     *
     * @param string $text    Texte en entrée
     * @param int    $start   Position de départ dans la chaîne
     * @param int    $length  Longueur de la chaîne à extraire
     * @param array  $options Options de la demande
     *
     * @return string
     */
    protected static function substr($text, $start, $length, array $options)
    {
        $substr = empty($options['trimWidth'])
            ? 'mb_substr'
            : 'mb_strimwidth';

        $maxPosition = self::strlen($text, ['trimWidth' => false] + $options);
        if ($start < 0) {
            $start += $maxPosition;
            if ($start < 0) {
                $start = 0;
            }
        }
        if ($start >= $maxPosition) {
            return '';
        }

        if ($length === null) {
            $length = self::strlen($text, $options);
        }

        if ($length < 0) {
            $text = self::substr($text, $start, null, $options);
            $start = 0;
            $length += self::strlen($text, $options);
        }

        if ($length <= 0) {
            return '';
        }

        if (empty($options['html'])) {
            return (string)$substr($text, $start, $length);
        }

        $totalOffset = 0;
        $totalLength = 0;
        $result = '';

        $pattern = '/(&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};)/i';
        $parts = preg_split($pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        foreach ($parts as $part) {
            $offset = 0;

            if ($totalOffset < $start) {
                $len = self::strlen($part, ['trimWidth' => false] + $options);
                if ($totalOffset + $len <= $start) {
                    $totalOffset += $len;
                    continue;
                }

                $offset = $start - $totalOffset;
                $totalOffset = $start;
            }

            $len = self::strlen($part, $options);
            if ($offset !== 0 || $totalLength + $len > $length) {
                if (strpos($part, '&') === 0 && preg_match($pattern, $part)
                    && $part !== html_entity_decode($part, ENT_HTML5 | ENT_QUOTES, 'UTF-8')
                ) {
                    // Entities cannot be passed substr.
                    continue;
                }

                $part = $substr($part, $offset, $length - $totalLength);
                $len = self::strlen($part, $options);
            }

            $result .= $part;
            $totalLength += $len;
            if ($totalLength >= $length) {
                break;
            }
        }
        return $result;
    }

    /**
     * Supprimmer le dernier mot d'une chaîne
     *
     * @param string $text Chaîne en entrée
     *
     * @return string
     */
    public static function removeLastWord($text)
    {
        $spacepos = mb_strrpos($text, ' ');
        if ($spacepos !== false) {
            $lastWord = mb_strrpos($text, $spacepos);
            if (mb_strwidth($lastWord) === mb_strlen($lastWord)) {
                $text = mb_substr($text, 0, $spacepos);
            }
            return $text;
        }
        return '';
    }

    /**
     * Obtenir le texte à gauche d'un caractère.
     *
     * @param string $string Caractère séparateur
     * @param string $text   Texte à découper
     *
     * @return null|string
     */
    public static function getBefore($string, $text)
    {
        $pos = strpos($text, $string);
        if ($pos) {
            return trim(substr($text, 0, $pos));
        }
        return null;
    }

    /**
     * Obtenir le texte à droite d'un caractère.
     *
     * @param string $string Caractère séparateur
     * @param string $text   Texte à découper
     *
     * @return null|string
     */
    public static function getAfter($string, $text)
    {
        $pos = strpos($text, $string);
        if ($pos) {
            return trim(substr($text, $pos + 1, strlen($text)));
        }
        return null;
    }
}
