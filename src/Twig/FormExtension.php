<?php
/**
 * Fichier FormExtension.php du 06/01/2018
 * Description : Fichier de la classe FormExtension
 *
 * PHP version 5
 *
 * @category Twig
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Twig;

/**
 * Class FormExtension
 * <ul>
 * <li>Helper sur les formulaires</li>
 * </ul>
 *
 * @category Twig
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.1.0>
 */
class FormExtension extends \Twig_Extension
{
    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('field', [$this, 'field'], [
                'is_safe' => ['html'],
                'needs_context' => true
            ])
        ];
    }

    /**
     * Génère une balise <code>input</code>
     *
     * @param array  $context
     * @param string $key
     * @param mixed  $value
     * @param null   $label
     * @param array  $options
     *
     * @return string
     */
    public function field(array $context, $key, $value, $label = null, array $options = [])
    {
        $type = isset($options['type']) ? $options['type'] : 'text';
        $error = $this->getErrorHtml($context, $key);
        $class = 'form-group';
        $value = $this->convertValue($value);
        $classInput = isset($options['class']) ? $options['class'] : '';
        $attributes = [
            'class' => trim('form-control ' . $classInput),
            'name' => $key,
            'id' => $key,
            'type' => $type
        ];

        if ($error) {
            $class .= ' has-danger';
            $attributes['class'] .= ' is-invalid';
        }

        if ($type === 'textarea') {
            $input = $this->textarea($value, $attributes);
        } elseif ($type === 'file') {
            $input = $this->file($attributes);
        } elseif ($type === 'checkbox') {
            $input = $this->checkbox($value, $attributes);
        } elseif (array_key_exists('options', $options)) {
            $input = $this->select($value, $options['options'], $attributes);
        } else {
            $input = $this->input($value, $attributes);
        }
        return "<div class=\"{$class}\">
                <label for=\"name\">{$label}</label>
                {$input}
                {$error}
                </div>";
    }

    /**
     * Génère une balise <code>span</code> avec l'erreur en dessous du composant
     *
     * <code>$error = $this->getErrorHtml($context, $key);</code>
     *
     * @param array  $context
     * @param string $key Nom de la clé
     *
     * @return string
     */
    private function getErrorHtml($context, $key)
    {
        $error = isset($context['errors'][$key]) ? $context['errors'][$key] : false;
        if ($error) {
            return "<small class=\"form-text text-muted\">{$error}</small>";
        }
        return "";
    }

    /**
     * Génère un <input>
     *
     * @param null  $value
     * @param array $attributes
     *
     * @return string
     */
    private function input($value, array $attributes)
    {
        return "<input " . $this->getHtmlFromArray($attributes) . " value=\"{$value}\"/>";
    }

    /**
     * Génère un <input> de type file
     *
     * @param $attributes
     *
     * @return string
     */
    private function file($attributes)
    {
        return "<input " . $this->getHtmlFromArray($attributes) . "/>";
    }

    /**
     * Génère un <textarea>
     *
     * @param null  $value
     * @param array $attributes
     *
     * @return string
     */
    private function textarea($value, array $attributes)
    {
        return "<textarea " . $this->getHtmlFromArray($attributes) . ">{$value}</textarea>";
    }

    /**
     * Génère un <select>
     *
     * @param string|null $value
     * @param array       $options
     * @param array       $attributes
     *
     * @return string
     */
    private function select($value = null, array $options = [], array $attributes = [])
    {
        $htmlOptions = array_reduce(array_keys($options), function ($html, $key) use ($options, $value) {
            $params = ['value' => $key, 'selected' => $key === (int)$value];
            return $html . '<option ' . $this->getHtmlFromArray($params) . '>' . $options[$key] . '</option>';
        }, '');
        return "<select " . $this->getHtmlFromArray($attributes) . ">$htmlOptions</select>";
    }

    /**
     * Génère un <input> de type checkbox
     *
     * @param $value
     * @param $attributes
     *
     * @return string
     */
    private function checkbox($value, $attributes)
    {
        $html = '<input type="hidden" name="' . $attributes['name'] . '" value="0"/>';
        if ($value) {
            $attributes['checked'] = true;
        }
        return $html . "<input " . $this->getHtmlFromArray($attributes) . " value=\"1\"/>";
    }

    /**
     * Génère le code HTML des attributs
     *
     * @param array $attributes
     *
     * @return string
     */
    private function getHtmlFromArray(array $attributes)
    {
        $htmlParts = [];
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $htmlParts[] = (string)$key;
            } elseif ($value !== false) {
                $htmlParts[] = "$key=\"$value\"";
            }
        }
        return implode(' ', $htmlParts);
    }

    /**
     * Convertir une date en chaîne de caractères
     *
     * @param $value
     *
     * @return string
     */
    private function convertValue($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }
}
