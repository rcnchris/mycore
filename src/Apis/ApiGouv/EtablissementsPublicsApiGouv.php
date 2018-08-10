<?php
/**
 * Fichier EtablissementsPublicsApiGouv.php du 02/08/2018
 * Description : Fichier de la classe EtablissementsPublicsApiGouv
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

use Rcnchris\Core\Apis\ApiException;
use Rcnchris\Core\Apis\CurlAPI;
use Rcnchris\Core\Tools\Items;

/**
 * Class EtablissementsPublicsApiGouv
 * <ul>
 * <li>Annuaire des établissements publics de l’administration</li>
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
class EtablissementsPublicsApiGouv extends CurlAPI
{

    /**
     * Liste des types disponibles
     *
     * @var array
     */
    private $types = [
        'adil' => "Information sur le logement (agenceAgence départementale pour l'information sur le logement (ADIL) départementale, Adil)",
        'anah' => "Agence nationale de l'habitat (ANAH), réseau local",
        'ars' => "Agence régionale de santé (ARS)",
        'afpa' => "Association nationale pour la formation professionnelle des adultes (AFPA), réseau local",
        'apec' => "Association pour l'emploi des cadres (APEC)",
        'apecita' => "Association pour l'emploi des cadres, ingénieurs et techniciens de l'agriculture et de l'agroalimentaire (APECITA), réseau local",
        'banque_de_france' => "Banque de France, succursale",
        'gendarmerie' => "Brigade de gendarmerie",
        'bav' => "Bureau d'aide aux victimes",
        'bsn' => "Bureau ou centre du service national",
        'caf' => "Caisse d'allocations familiales (CAF)",
        'carsat' => "Caisse d'assurance retraite et de la santé au travail (CARSAT)",
        'cpam' => "Caisse primaire d'assurance maladie (CPAM)",
        'cicas' => "Centre d'information de conseil et d'accueil des salariés (CICAS)",
        'cio' => "Centre d'information et d'orientation (CIO)",
        'cidf' => "Centre d'information sur les droits des femmes et des familles (CIDFF)",
        'centre_detention' => "Centre de détention",
        'cdg' => "Centre de gestion de la fonction publique territoriale",
        'pmi' => "Centre de protection maternelle et infantile (PMI)",
        'crib' => "Centre de ressources et d'information des bénévoles (CRIB)",
        'csl' => "Centre de semi-liberté",
        'cddp' => "Centre départemental de documentation pédagogique",
        'centre_impots_fonciers' => "Centre des impôts foncier et cadastre",
        'cnra' => "Centre en route de la navigation aérienne",
        'cnfpt' => "Centre national de la fonction publique territoriale (CNFPT), réseau local",
        'crfpn' => "Centre ou délégation régionale de recrutement et de formation de la police nationale",
        'centre_penitentiaire' => "Centre pénitentiaire",
        'creps' => "Centre régional d'éducation populaire et de sports (CREPS)",
        'crdp' => "Centre régional de documentation pédagogique",
        'chambre_agriculture' => "Chambre d'agriculture",
        'cci' => "Chambre de commerce et d'industrie (CCI)",
        'chambre_metier' => "Chambre de métiers et de l'artisanat",
        'crc' => "Chambre régionale ou territoriale des comptes",
        'commissariat_police' => "Commissariat de police",
        'commission_conciliation' => "Commission départementale de conciliation",
        'civi' => "Commission d'indemnisation des victimes d'infraction",
        'conciliateur_fiscal' => "Conciliateur fiscal",
        'conseil_culture' => "Conseil de la culture, de l'éducation et de l'environnement",
        'prudhommes' => "Conseil de prud'hommes",
        'cesr' => "Conseil économique, social et environnemental régional",
        'cg' => "Conseil départemental",
        'cr' => "Conseil régional",
        'caa' => "Cour administrative d'appel",
        'cour_appel' => "Cour d'appel",
        'crous' => "CROUS et ses antennes",
        'defenseur_droits' => "Défenseur des droits",
        'dml' => "Délégation à la mer et au littoral",
        'drrt' => "Délégation régionale à la recherche et à la technologie",
        'dr_femmes' => "Délégation régionale aux droits des femmes et à l'égalité",
        'dr_insee' => "Délégation régionale de l'INSEE",
        'dronisep' => "Délégation régionale de l'ONISEP",
        'ars_antenne' => "Délégation territoriale de l'Agence régionale de santé",
        'dac' => "Direction de l'aviation civile",
        'ddcs' => "Direction départementale de la cohésion sociale (DDCS)",
        'ddcspp' => "Direction départementale de la cohésion sociale et de la protection des populations (DDCSPP)",
        'dd_fip' => "Direction départementale des finances publiques",
        'ddt' => "Direction départementale des territoires -et de la mer- (DDT)",
        'ddsp' => "Direction départementale ou service de la sécurité publique",
        'inspection_academique' => "Direction des services départementaux de l'Éducation nationale",
        'did_routes' => "Direction interdépartementale des routes",
        'drpjj' => "Direction interdépartementale ou régionale de la protection judiciaire de la jeunesse",
        'dir_mer' => "Direction interrégionale de la mer",
        'dir_pj' => "Direction interrégionale de la police judiciaire",
        'drsp' => "Direction interrégionale des services pénitentiaires",
        'drddi' => "Direction interrégionale et régionale des douanes",
        'dreal' => "Direction régionale de l'environnement, de l'aménagement et du logement (DREAL)",
        'dreal_ut' => "Unité territoriale - Direction régionale de l'environnement, de l'aménagement et du logement (DREAL)",
        'drjscs' => "Direction régionale de la jeunesse, des sports et de la cohésion sociale",
        'draf' => "Direction régionale de l'alimentation, de l'agriculture et de la forêt (DRAAF)",
        'onf' => "Direction régionale de l'Office national des forêts",
        'drac' => "Direction régionale des affaires culturelles (DRAC)",
        'direccte' => "Direction régionale des entreprises, de la concurrence, de la consommation, du travail et de l'emploi",
        'direccte_ut' => "Unité territoriale - Direction régionale des entreprises, de la concurrence, de la consommation, du travail et de l'emploi",
        'dr_fip' => "Direction régionale des finances publiques",
        'driee' => "Direction régionale et interdépartementale de l'environnement et de l'énergie (DRIEE)",
        'driee_ut' => "Unité territoriale - Direction régionale et interdépartementale de l'environnement et de l'énergie (DRIEE)",
        'driea' => "Direction régionale et interdépartementale de l'équipement et de l'aménagement (DRIEA)",
        'driea_ut' => "Unité territoriale - Direction régionale et interdépartementale de l'équipement et de l'aménagement (DRIEA)",
        'drihl' => "Direction régionale et interdépartementale de l'hébergement et du Hébergement et logement (direction logement (DRIHL) régionale et",
        'drihl_ut' => "Unité territoriale - Direction régionale et interdépartementale de l'hébergement et du logement (DRIHL)",
        'ddpjj' => "Direction territoriale de la protection judiciaire de la jeunesse",
        'dz_paf' => "Direction zonale de la police aux frontières",
        'dd_femmes' => "Droit des femmes et égalité, mission départementale",
        'esm' => "Etablissement spécialisé pour mineurs",
        'fdc' => "Fédération départementale des chasseurs",
        'fdapp' => "Fédération départementale pour la pêche et la protection du milieu aquatique",
        'fongecif' => "Fongecif",
        'prefecture_greffe_associations' => "Greffe des associations",
        'greta' => "Greta",
        'cij' => "Information jeunesse, réseau local",
        'epci' => "Intercommunalité",
        'mairie' => "Mairie",
        'paris_mairie' => "Mairie de Paris, Hôtel de Ville",
        'paris_mairie_arrondissement' => "Mairie de Paris, mairie d'arrondissement",
        'mairie_com' => "Mairie des collectivités d'outre-mer",
        'maison_centrale' => "Maison centrale",
        'maison_arret' => "Maison d'arrêt",
        'mjd' => "Maison de justice et du droit",
        'maison_handicapees' => "Maison départementale des personnes handicapées (MDPH)",
        'dir_meteo' => "Météo France, direction interrégionale",
        'maia' => "Mission d'accueil et d'information des associations (MAIA)",
        'mission_locale' => "Mission locale et Permanence d'accueil, d'information et d'orientation (PAIO)",
        'msa' => "Mutualité sociale agricole (MSA), réseau local",
        'ofii' => "Office français de l'immigration et de l'intégration (ex ANAEM), réseau local",
        'onac' => "Office national des anciens combattants (ONAC), réseau local",
        'permanence_juridique' => "Permanence juridique",
        'accompagnement_personnes_agees' => "Plateforme d'accompagnement et de répit pour les aidants de personnes âgées",
        'pif' => "Point info famille",
        'clic' => "Point d'information local dédié aux personnes âgées",
        'pole_emploi' => "Pôle emploi",
        'prefecture' => "Préfecture",
        'paris_ppp' => "Préfecture de police de Paris",
        'paris_ppp_gesvres' => "Préfecture de police de Paris - Site central de Gesvres",
        'paris_ppp_antenne' => "Préfecture de police de Paris, antenne d'arrondissement",
        'paris_ppp_certificat_immatriculation' => "Préfecture de police de Paris, certificat d'immatriculation",
        'paris_ppp_permis_conduire' => "Préfecture de police de Paris, permis de conduire",
        'pp_marseille' => "Préfecture de police des Bouches-du-Rhône",
        'prefecture_region' => "Préfecture de région",
        'ddpp' => "Protection des populations (direction départementale, DDPP)",
        'rectorat' => "Rectorat",
        'sgami' => "Secrétariat pour l'administration du ministère de l'Intérieur (SGAMI)",
        'service_navigation' => "Service de la navigation",
        'hypotheque' => "Service de publicité foncière ex-conservation des hypothèques",
        'sie' => "Service des impôts des entreprises du Centre des finances publiques",
        'sip' => "Service des impôts des particuliers du Centre des finances publiques",
        'spip' => "Service pénitentiaire d'insertion et de probation",
        'sdac' => "Service territorial de l'architecture et du patrimoine",
        'suio' => "Service universitaire d'information et d'orientation",
        'sous_pref' => "Sous-préfecture",
        'tresorerie' => "Trésorerie",
        'ta' => "Tribunal administratif",
        'ti' => "Tribunal d'instance",
        'tribunal_commerce' => "Tribunal de commerce",
        'tgi' => "Tribunal de grande instance",
        'te' => "Tribunal pour enfants",
        'urssaf' => "Urssaf"
    ];

    /**
     * Initialise l'URL de l'API
     */
    public function __construct()
    {
        parent::__construct('https://etablissements-publics.api.gouv.fr/v1/organismes');
    }

    /**
     * Obtenir la liste des organismes pour un département et un type
     * - `$api->getByDepartement(83, 'cpam')->extract('Nom', 'codeInsee')->toArray();`
     *
     * @param string|int $departement Code du département
     * @param string     $type        Type d'organismes
     *
     * @return \Rcnchris\Core\Tools\Items
     * @throws \Exception
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function getByDepartement($departement, $type)
    {
        if (!$this->getTypes()->has($type)) {
            throw new ApiException("le type $type n'existe pas");
        }
        $this->addUrlParts($departement . '/' . $type);
        return $this->exec()
            ->get('items')
            ->get('features')
            ->extract('properties');
    }

    /**
     * Obtenir la liste des types
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getTypes()
    {
        return new Items($this->types);
    }
}
