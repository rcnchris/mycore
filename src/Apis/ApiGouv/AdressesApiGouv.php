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
    private $fieldsCommmunes = 'nom,code,codesPostaux,centre,surface,codeDepartement,departement,codeRegion,region,population';

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
     *
     * - `$adr->getRegions()->toArray();`
     * - `$adr->getRegions(93)->toArray();`
     *
     * @param string|null $code Code de la région
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function getRegions($code = null)
    {
        return $this
            ->withParts("regions/$code")
            ->withParams(['format' => 'json'])
            ->exec("Régions")
            ->getResponse();
    }

    /**
     * Recherche de départements
     *
     * - `$adr->searchDepartements()->toArray();`
     * - `$adr->searchDepartements('code', 83)->toArray();`
     * - `$adr->searchDepartements('nom', 'var')->toArray();`
     * - `$adr->searchDepartements('codeRegion', 'var')->toArray();`
     *
     * @param string|null     $param  Nom du paramètre de recherche
     * @param string|int|null $value  Valeur du paramètre
     * @param string|null     $format Format du retour
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function searchDepartements($param = null, $value = null, $format = 'json')
    {
        $this->withParts('departements');
        $params = [
            $param => $value,
            'fields' => $this->fieldsDepartements,
            'format' => $format
        ];
        $this->withParams($params);
        $response = $this->exec("Départements")->getResponse();
        if ($response->count() === 1) {
            return $response->first();
        }
        return $response;
    }

    /**
     * Récupérer les informations concernant un département par son code
     *
     * - `$adr->getDepartement(83)->toArray();`
     *
     * @param string|int $code Code du département
     *
     * @return \Intervention\Image\Image|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function getDepartement($code)
    {
        $this->withParts("departements/$code");
        $params = [
            'fields' => $this->fieldsDepartements,
            'format' => 'json'
        ];
        $this->withParams($params);
        $response = $this->exec("Obtenir le département : $code")->getResponse();
        return $response;
    }

    /**
     * Rechercher des communes par leur code postal
     *
     * - `$adr->searchCommunes('nom', 'sanary')->toArray();`
     * - `$adr->searchCommunes('code', 83123)->toArray();`
     * - `$adr->searchCommunes('codePostal', 83110)->toArray();`
     * - `$adr->searchCommunes('codeDepartement', 83)->toArray();`
     *
     * @param string      $param  Nom du paramètre
     * @param string|int  $value  Valeur du paramètre
     * @param string|null $format Format du retour
     *
     * @return \Intervention\Image\Image|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function searchCommunes($param, $value, $format = 'json')
    {
        $this->withParts('communes');
        $params = [
            $param => $value,
            'fields' => $this->fieldsCommmunes,
            'format' => $format,
            'geometry' => 'centre'
        ];
        $this->withParams($params);
        $communes = $this
            ->exec("Recherche de communes par $param : $value")
            ->getResponse();
        if ($communes->count() === 1) {
            return $communes->first();
        }
        return $communes;
    }

    /**
     * Récupérer les informations concernant une commune
     *
     * @param string|int $codeInsee Code INSEE de la commune
     *
     * @return \Intervention\Image\Image|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function getCommune($codeInsee)
    {
        $this->withParts("communes/$codeInsee");
        $params = [
            'fields' => $this->fieldsCommmunes,
            'format' => 'json',
            'geometry' => 'centre'
        ];
        $this->withParams($params);
        $communes = $this
            ->exec("Obtenir une commune par son code INSEE : $codeInsee")
            ->getResponse();
        return $communes;
    }

    /**
     * Obtenir les communes d'un département par son code
     *
     * - `$api->getCommunesDuDepartement(83);`
     *
     * @param string|int  $departement Code du département
     * @param string|null $format      Format du retour
     *
     * @return \Intervention\Image\Image|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function getCommunesDuDepartement($departement, $format = 'json')
    {
        $this->withParts("departements/$departement/communes");
        $params = [
            'fields' => $this->fieldsCommmunes,
            'format' => $format,
            'geometry' => 'centre'
        ];
        $this->withParams($params);
        return $this->exec("Communes du département $departement")->getResponse();
    }

    /**
     * Obtenir les départements d'une région
     *
     * - `$adr->getCommunesDuDepartement(83)->toArray();`
     *
     * @param string|int $codeRegion Code de la région
     *
     * @return \Intervention\Image\Image|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function getDepartementsDeRegion($codeRegion)
    {
        $this->withParts("regions/$codeRegion/departements");
        $params = [
            'code' => $codeRegion,
            'fields' => $this->fieldsDepartements,
            'format' => 'json'
        ];
        $this->withParams($params);
        return $this->exec("Départements de la région $codeRegion")->getResponse();
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
