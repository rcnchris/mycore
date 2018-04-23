<?php
/**
 * Fichier DataPdfTrait.php du 15/02/2018
 * Description : Fichier de la classe DataPdfTrait
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
 * Trait DataPdfTrait
 * <ul>
 * <li>Ajout de données dans un document PDF</li>
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
     * ### Exemple
     * - `$pdf->getData(['name' => 'Mathis', 'year' => 2007, 'genre' => 'male']);`
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
     * ### Exemple
     * - `$pdf->getData();`
     * - `$pdf->getData('name');`
     *
     * @param string|null $key Nom de la clé
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
        $exists = false;
        if ($this->isArray()) {
            $exists = array_key_exists($key, $this->data);
        } elseif ($this->isObject()) {
            $exists = property_exists($this->data, $key);
        }
        return $exists;
    }

    /**
     * Vérifier la présence d'une valeur dans les données
     *
     * @param mixed $value Valeur à chercher
     *
     * @return bool
     */
    public function hasValue($value)
    {
        $exists = false;
        if ($this->isArray()) {
            $exists = in_array($value, $this->data);
        } elseif ($this->isObject()) {
            $exists = in_array($value, get_object_vars($this->data));
        }
        return $exists;
    }

    /**
     * Vérifie si les données sont stockées dans un objet
     *
     * @return bool
     */
    private function isObject()
    {
        return is_object($this->data);
    }


    /**
     * Vérifie si les données sont stockées dans un tableau
     *
     * @return bool
     */
    private function isArray()
    {
        return is_array($this->data);
    }
}
