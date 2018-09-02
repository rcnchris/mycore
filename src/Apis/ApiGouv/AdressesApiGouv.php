<?php
/**
 * Fichier AdressesApiGouv.php du 02/08/2018
 * Description : Fichier de la classe AdressesApiGouv
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis\ApiGouv;

use Rcnchris\Core\Apis\Curl;
use Rcnchris\Core\Tools\Items;

/**
 * Class AdressesApiGouv
 * <ul>
 * <li>Utilise l'API du gouvernement français geo.api.gouv.fr</li>
 * </ul>
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class AdressesApiGouv extends Curl
{
    /**
     * Liste des départements
     *
     * @var Items
     */
    public $departements = null;

    /**
     * Liste des régions
     *
     * @var Items
     */
    public $regions;

    /**
     * Liste de tous les champs d'un commune
     *
     * @var string
     */
    private $fieldsCommmunes = 'nom,code,codesPostaux,centre,surface,contour,codeDepartement,departement,codeRegion,region,population';

    /**
     * Liste de tous les champs d'un département
     *
     * @var string
     */
    private $fieldsDepartements = 'nom,code,codeRegion,region';

    /**
     * Initialise l'URl de l'API
     */
    public function __construct()
    {
        parent::__construct('https://geo.api.gouv.fr');
    }

    /**
     * Obtenir la liste des régions ou l'une d'entre elles
     * - `$adr->getRegions()->extract('nom', 'code')->toArray();`
     *
     * @param string|null $code   Code de la région
     * @param string|null $format Format de la réponse
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function getRegions($code = null, $format = 'json')
    {
        $this->withParts('regions');
        $this->withParams(compact('code', 'format'));
        return $this->exec("Régions")->getResponse();
    }

    /**
     * Liste les départements ou l'un d'entre eux
     * - `$adr->getDepartements()->extract('nom', 'code')->toArray();`
     *
     * @param string|null $code   Code du département
     * @param string|null $format Format de la réponse
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function getDepartements($code = null, $format = 'json')
    {
        $this->withParts('departements');
        $params = [
            'code' => $code,
            'fields' => $this->fieldsDepartements,
            'format' => $format
        ];
        $this->withParams($params);
        return $this->exec("Départements")->getResponse();
    }

    /**
     * Liste les communes ou l'une d'entre elles
     * - `$adr->getCommunes(83000)->toArray();`
     *
     * @param string      $departement Code du département
     * @param string|null $format      Format de la réponse
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function getCommunes($departement, $format = 'json')
    {
        $this->withParts('communes');
        $params = [
            'codePostal' => $departement,
            'fields' => $this->fieldsCommmunes,
            'format' => $format
        ];
        $this->withParams($params);
        return $this->exec("Communes")->getResponse();
    }

    /**
     * Obtenir la liste des champs d'une commune dans un tableau
     *
     * @return array
     */
    public function getFieldsCommmunes()
    {
        return explode(',', $this->fieldsCommmunes);
    }

    /**
     * Définir la liste des champs d'une commune
     *
     * @param string $fieldsCommmunes
     */
    public function setFieldsCommmunes($fieldsCommmunes)
    {
        $this->fieldsCommmunes = $fieldsCommmunes;
    }

    /**
     * Obtenir la liste de tous les champs d'un département
     *
     * @return string
     */
    public function getFieldsDepartements()
    {
        return explode(',', $this->fieldsDepartements);
    }

    /**
     * Définir la liste des champs d'un département
     *
     * @param string $fieldsDepartements
     */
    public function setFieldsDepartements($fieldsDepartements)
    {
        $this->fieldsDepartements = $fieldsDepartements;
    }
}
