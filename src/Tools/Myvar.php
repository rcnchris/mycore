<?php
/**
 * Fichier Myvar.php du 15/01/2018
 * Description : Fichier de la classe Myvar
 *
 * PHP version 5
 *
 * @category Debug
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
 * Class Myvar
 * <ul>
 * <li>Permet de réprésenter n'importe quelle variable sous forme d'objet</li>
 * </ul>
 *
 * @category Debug
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.1.0>
 */
class Myvar
{
    /**
     * Variable
     *
     * @var mixed
     */
    private $var;

    /**
     * Constructeur
     *
     * @param mixed $var
     */
    public function __construct($var)
    {
        $this->var = $var;
    }

    /**
     * Obtenir la variable
     *
     * ### Exemple
     * - `$var->get();`
     * - `$var->get('name');`
     *
     * @param string|null $key Clé du tableau ou propriété de l'objet
     *
     * @return mixed
     */
    public function get($key = null)
    {
        if (is_null($key)) {
            return $this->var;
        } elseif (!is_null($key) && $this->isArray() && array_key_exists($key, $this->var)) {
            return $this->var[$key];
        } elseif ($this->isObject()) {
            if (property_exists($this->var, $key)) {
                return $this->var->$key;
            } elseif (method_exists($this->var, $key)) {
                return $this->var->$key();
            }
        }
        return false;
    }

    /**
     * Vérifier s'il s'agît d'un objet
     *
     * @return bool
     */
    public function isObject()
    {
        return is_object($this->var);
    }

    /**
     * Vérifier s'il s'agît d'une chaîne de caractères
     *
     * @return bool
     */
    public function isString()
    {
        return is_string($this->var);
    }

    /**
     * Vérifier s'il s'agît d'une valeur numérique
     *
     * @return bool
     */
    public function isNum()
    {
        return is_numeric($this->var);
    }

    /**
     * Vérifier s'il s'agît d'une valeur numérique entière
     *
     * @return bool
     */
    public function isInteger()
    {
        return is_integer($this->var);
    }

    /**
     * Vérifier s'il s'agît d'une valeur numérique entière
     *
     * @return bool
     */
    public function isDouble()
    {
        return is_double($this->var);
    }

    /**
     * Vérifier s'il s'agît d'une valeur numérique entière
     *
     * @return bool
     */
    public function isArray()
    {
        return is_array($this->var);
    }

    /**
     * Vérifier s'il s'agît d'une valeur numérique entière
     *
     * @return bool
     */
    public function isResource()
    {
        return is_resource($this->var);
    }

    /**
     * Obtenir le type interne à PHP
     *
     * @return string|void
     */
    public function getType()
    {
        return getType($this->var);
    }

    /**
     * Obtenir la longueur
     *
     * @return int
     */
    public function length()
    {
        if ($this->isArray()) {
            return count($this->var);
        } elseif ($this->isResource()) {
            return 0;
        } else {
            return mb_strlen($this->var);
        }
    }

    /**
     * Obtenir la valeur sous la forme d'une chaînes de caractères
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->isString()) {
            return $this->var;
        } elseif ($this->isArray()) {
            return json_encode($this->var);
        } elseif ($this->isObject() && method_exists($this->var, __FUNCTION__)) {
            return $this->var->__toString();
        } else {
            return (string)$this->var;
        }
    }

    /**
     * Obtenir le type d'une ressource
     *
     * @return bool|string
     */
    public function getResourceType()
    {
        if ($this->isResource()) {
            return get_resource_type($this->var);
        }
        return false;
    }

    /**
     * Obtenir la liste des méthodes publiques
     *
     * @param bool|null $withoutParentMethods Avec ou sans les méthodes du parent
     *
     * @return array|bool
     *
     */
    public function getMethods($withoutParentMethods = false)
    {
        if ($this->isObject()) {
            $allMethods = get_class_methods(get_class($this));
            if ($withoutParentMethods) {
                $parentMethods = get_class_methods(get_parent_class($this->var));
                $diffMethods = array_diff($allMethods, $parentMethods);
                return $diffMethods;
            }
            return $allMethods;
        }
        return false;
    }

    /**
     * Obtenir les propriétés publiques de l'objet
     *
     * @return array|bool
     */
    public function getProperties()
    {
        if ($this->isArray()) {
            return array_keys($this->var);
        } elseif ($this->isObject()) {
            return get_object_vars($this->var);
        }
        return false;
    }

    /**
     * Obtenir les classes qu'implémente l'objet
     *
     * @return array|bool
     */
    public function getImplements()
    {
        if ($this->isObject()) {
            return class_implements($this->var);
        }
        return false;
    }

    /**
     * Obtenir la classe parente
     *
     * @return bool|string
     */
    public function getParent()
    {
        if ($this->isObject()) {
            return get_parent_class(get_class($this->var));
        }
        return false;
    }

    /**
     * Obtenir la liste des traits utilisés
     *
     * @return array|bool
     */
    public function getTraits()
    {
        if ($this->isObject()) {
            return class_uses($this->var);
        }
        return false;
    }
}
