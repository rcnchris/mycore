<?php
/**
 * Fichier Bootstrap4Extension.php du 06/01/2018
 * Description : Fichier de la classe Bootstrap4Extension
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

use Rcnchris\Core\Html\Html;

/**
 * Class Bootstrap4Extension
 * <ul>
 * <li>Helper sur Bootstrap 4</li>
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
class Bootstrap4Extension extends \Twig_Extension
{
    /**
     * Contexte des composants
     *
     * @var array
     */
    private $contexts = [
        'primary',
        'secondary',
        'success',
        'danger',
        'warning',
        'info',
        'light',
        'dark'
    ];

    /**
     * Tailles des composants
     *
     * @var array
     */
    private $sizes = ['sm', 'lg'];

    /**
     * Obtenir la liste des filtres
     *
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('alert', [$this, 'alert'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('alertResult', [$this, 'alertResult'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('badge', [$this, 'badge'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('badgeBool', [$this, 'badgeBool'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('button', [$this, 'button'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Génère une alerte contextuelle
     * - Filtre
     *
     * - `"{{ Ola les gens"|alert('warning) }}`
     *
     * @param string      $content Contenu de l'alerte
     * @param string|null $context Couleur de l'alerte
     * @param array|null  $options Options de l'alerte
     *
     * @return null|string
     */
    public function alert($content, $context = 'info', array $options = [])
    {
        if (!$this->hasContext($context)) {
            return null;
        }
        $class = 'alert alert-' . $context;
        if (array_key_exists('dismissible', $options)) {
            $class .= ' alert-dismissible fade show';
            $content = '<button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>' . $content;
        }
        if (array_key_exists('icon', $options)) {
            $content = $options['icon'] . ' ' . $content;
        }
        return Html::surround($content, 'div', ['class' => $class, 'role' => 'alert']);
    }

    /**
     * Afficher une alerte de type résultat d'exécution de code
     * - Filtre
     *
     * @param string $value Valeur à afficher dans une alerte de type résultat
     *
     * @return string
     */
    public function alertResult($value)
    {
        return Html::surround(
            Html::surround($value, 'samp'),
            'div',
            ['class' => 'alert alert-secondary']
        );
    }

    /**
     * Génère un badge selon un contexte souhaité
     * - Filtre
     *
     * @param string $value
     * @param string $context
     * @param array  $options
     *
     * @return null|string
     */
    public function badge($value, $context = 'secondary', array $options = [])
    {
        if (!is_string($value)) {
            $value = (string)$value;
        }

        $html = null;
        $class = 'badge';
        $pill = null;
        $link = null;

        if (array_key_exists('pill', $options)) {
            $class = $class . ' badge-pill';
        }
        $class = $this->hasContext($context)
            ? $class . ' badge-' . $context
            : $class . ' badge-secondary';

        return array_key_exists('link', $options)
            ? Html::surround($value, 'a', ['class' => $class, 'href' => $options['link']])
            : Html::surround($value, 'span', ['class' => $class]);
    }

    /**
     * Génère un badge vrai/faux
     * - Filtre
     *
     * @param mixed      $value   Valeur booléenne de différents types
     * @param array|null $options du badge
     *
     * @return null|string
     */
    public function badgeBool($value, array $options = [])
    {
        $valuesOk = [true, 1, '1', 'yes', 'on'];
        return in_array($value, $valuesOk)
            ? $this->badge('TRUE', 'success', $options)
            : $this->badge('FALSE', 'danger', $options);
    }

    /**
     * Génère un bouton pour formulaire ou simple lien
     * - Filtre
     *
     * @param string      $text    Label du bouton
     * @param string|null $type    Type du bouton ('button', 'link', 'input')
     * @param array|null  $options Options du bouton
     *
     * @return null|string
     */
    public function button($text, $type = 'button', array $options = ['context' => 'primary', 'link' => null])
    {
        if (!in_array($type, ['button', 'link', 'input'])) {
            return null;
        }
        if (!isset($options['context'])) {
            $options['context'] = 'primary';
        }
        $html = null;
        $class = 'btn btn-' . $options['context'];
        if (array_key_exists('size', $options) && in_array($options['size'], $this->sizes)) {
            $class .= ' btn-' . $options['size'];
        }
        switch ($type) {
            case 'button':
                $html = Html::surround($text, 'button', ['class' => $class, 'type' => 'submit']);
                break;

            case 'input':
                $html = Html::input(['class' => $class, 'type' => 'submit', 'value' => $text]);
                break;

            case 'link':
                $link = isset($options['link']) ? $options['link'] : '#';
                $html = Html::link($link, $text, ['class' => $class, 'role' => 'button']);
                break;
        }
        return $html;
    }

    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('progress', [$this, 'progress'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('checkbox', [$this, 'checkbox'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Génère une barre de progression
     * - Fonction
     *
     * @param       $value
     * @param int   $max
     * @param array $options
     *
     * @return string
     */
    public function progress($value, $max = 100, array $options = ['context' => 'info', 'min' => 0])
    {
        $class = 'progress-bar';
        $min = 0;
        if (isset($options['context']) && $this->hasContext($options['context'])) {
            $class .= ' bg-' . $options['context'];
        }
        if (isset($options['min'])) {
            $min = $options['min'];
        }
        $prc = (string)round(($value / $max) * 100, 2);

        $html = Html::surround($value, 'div', [
            'class' => $class,
            'role' => 'progressbar',
            'style' => 'width: ' . $prc . '%',
            'aria-valuenow' => $value,
            'aria-valuemin' => $min,
            'aria-valuemax' => $max,
            'title' => $prc . '%'
        ]);
        $html = Html::surround($html, 'div', ['class' => 'progress']);
        return $html;
    }

    /**
     * Fonction : Génère un checkbox
     *
     * @param string      $field Nom du champ
     * @param mixed       $value Valeur de la case à cocher
     * @param string|null $label Valeur du label
     *
     * @return string
     */
    public function checkbox($field, $value, $label = null)
    {
        $value = boolval($value);
        $checked = null;
        if ($value) {
            $checked = 'checked';
        }
        $hiddenInput = Html::input([
            'name' => $field,
            'type' => 'hidden',
            'value' => 0
        ]);
        $input = Html::input([
            'class' => 'custom-control-input',
            'name' => $field,
            'type' => 'checkbox',
            'value' => $value,
            'checked' => $checked
        ]);
        $spanIndicator = Html::surround('', 'span', ['class' => 'custom-control-indicator']);
        $spanDescription = Html::surround($label, 'span', ['class' => 'custom-control-description']);

        $html = Html::surround(
            $hiddenInput . $input . $spanIndicator . $spanDescription,
            'label',
            ['class' => 'custom-control custom-checkbox']
        );

        return $html;
    }

    /**
     * Vérifie la présence d'un contexte pour un composant donné
     *
     * @param string $contextName Nom du contexte (success, warning...)
     *
     * @return bool
     */
    private function hasContext($contextName)
    {
        return in_array($contextName, $this->contexts);
    }
}
