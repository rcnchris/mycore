<?php
/**
 * Fichier Bootstrap4Extension.php du 06/01/2018
 * Description : Fichier de la classe Bootstrap4Extension
 *
 * PHP version 5
 *
 * @category Extension
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
 * Class Bootstrap4Extension
 *
 * @category Extension
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class Bootstrap4Extension extends \Twig_Extension
{
    /**
     * Contexte des composants
     *
     * @var array
     */
    private $contexts = [
        'primary'
        , 'secondary'
        , 'success'
        , 'danger'
        , 'warning'
        , 'info'
        , 'light'
        , 'dark'
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
            new \Twig_SimpleFilter('alert', [$this, 'alert'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('alertResult', [$this, 'alertResult'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('badge', [$this, 'badge'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('badgeBool', [$this, 'badgeBool'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('button', [$this, 'button'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Afficher une alerte de type résultat d'exécution de code
     *
     * @param string $value Valeur à afficher dans une alerte de type résultat
     *
     * @return null|string
     */
    public function alertResult($value)
    {
        try {
            $value = (string)$value;
        } catch (\Exception $e) {
            return null;
        }
        return '<div class="alert alert-secondary"><samp>' . $value . '</samp></div>';
    }

    /**
     * Filtre : Génère une alerte contextuelle
     *
     * <code>"Ola les gens"|alert('warning)</code>
     *
     * @param string $content
     * @param string $context
     * @param array  $options
     *
     * @return null|string
     */
    public function alert($content, $context = 'info', array $options = [])
    {
        if (!is_string($content) || !$this->hasContext($context)) {
            return null;
        }
        $html = null;
        $class = 'alert alert-' . $context;
        if (array_key_exists('dismissible', $options)) {
            $class .= ' alert-dismissible fade show';
            $content = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>' . $content;
        }
        if (array_key_exists('icon', $options)) {
            $content = $options['icon'] . ' ' . $content;
        }
        $html = '<div class="%s" role="alert">%s</div>';
        return sprintf($html, $class, $content);
    }

    /**
     * Filtre : Génère un badge selon un contexte souhaité
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
        if ($this->hasContext($context)) {
            $class .= ' badge-' . $context;
        }

        if (array_key_exists('link', $options)) {
            $html = '<a href="%s" class="%s">%s</a>';
            $html = sprintf($html, $options['link'], $class, $value);
        } else {
            $html = '<span class="%s">%s</span>';
            $html = sprintf($html, $class, $value);
        }
        return $html;
    }

    /**
     * Filtre : Génère une vignette vrai/faux
     *
     * @param $value
     *
     * @return null|string
     */
    public function badgeBool($value)
    {
        if ($value === true || $value === 1 || $value === 'yes') {
            return $this->badge('TRUE', 'success');
        } else {
            return $this->badge('FALSE', 'danger');
        }
    }

    /**
     * Filtre : Génère un bouton pour formulaire ou simple lien
     *
     * @param string $text
     * @param string $type Type du bouton ('button', 'link', 'input')
     * @param array  $options
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
        switch ($type) {
            case 'button':
                $html = '<button class="%s" type="submit">%s</button>';
                break;

            case 'input':
                $html = '<input class="%s" type="submit" value="%s">';
                break;

            case 'link':
                $link = isset($options['link']) ? $options['link'] : '#';
                $html = '<a class="%s" href="' . $link . '" role="button">%s</a>';
                break;
        }
        $class = 'btn btn-' . $options['context'];
        if (array_key_exists('size', $options) && in_array($options['size'], $this->sizes)) {
            $class .= ' btn-' . $options['size'];
        }
        return sprintf($html, $class, $text);
    }

    /**
     * Obtenir laliste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('progress', [$this, 'progress'], ['is_safe' => ['html']])
            , new \Twig_SimpleFunction('checkbox', [$this, 'checkbox'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Fonction : Génère une barre de progression
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
        $html = '<div class="progress">
                    <div class="' . $class
            . '" role="progressbar" style="width: '
            . $prc . '%" aria-valuenow="'
            . $value . '" aria-valuemin="'
            . $min . '" aria-valuemax="'
            . $max . '" title="'
            . $prc . '%">'
            . $value . '</div>
                </div>';
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
        $html = '
            <label class="custom-control custom-checkbox">
              <input type="hidden" name="' . $field . '" value="0">
              <input type="checkbox" class="custom-control-input" name="'
            . $field . '" value="' . $value . '" ' . $checked . '>
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description">' . $label . '</span>
            </label>';
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
