<?php
/**
 * Fichier DataPdfTrait.php du 15/02/2018
 * Description : Fichier de la classe DataPdfTrait
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF;

trait DataPdfTrait
{

    /**
     * Données du document
     *
     * @var mixed
     */
    private $data;


    /**
     * Définir les données du document
     *
     * @param mixed $data Données du document
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Obtenir les données du document ou l'une d'entre elle
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getData($key = null)
    {
        if (is_null($key)) {
            return $this->data;
        }
        if (is_array($this->data) && array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        if (is_object($this->data) && property_exists($this->data, $key)) {
            return $this->data->$key;
        }
        return false;
    }

    /**
     * Vérifie la présence d'une clé
     *
     * @param string|int $key Nom ou indice de la clé cherchée
     *
     * @return bool
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Vérifier la présence d'une valeur$
     *
     * @param mixed $value Valeur à chercher
     *
     * @return bool
     */
    public function hasValue($value)
    {
        return in_array($value, $this->data);
    }
}
