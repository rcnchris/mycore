<?php
/**
 * Fichier AlloCine.php du 27/12/2017
 * Description : Fichier de la classe AlloCine
 *
 * PHP version 7
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

namespace Rcnchris\Core\Apis;

/**
 * Class AlloCine
 * <ul>
 * <li>Interrogation de l'API AlloCiné</li>
 * </ul>
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class AlloCine extends OneAPI
{
    /**
     * Clé du partenanire
     *
     * @const string
     */
    const PARTNER = '100043982026';

    /**
     * Clé secrète pour encodage sig
     *
     * @const string
     */
    const KEY = '29d185d98c984a359e6e6f26a0474269';

    /**
     * Navigateur fictif
     *
     * @const string
     */
    const USER_AGENT = 'Dalvik/1.6.0 (Linux; U; Android 4.2.2; Nexus 4 Build/JDQ39E)';

    /**
     * Format du retour de la requête
     *
     * @var string
     */
    private $format = 'json';

    /**
     * Méthodes disponibles sur AlloCine
     *
     * @var array
     */
    private $methods = [
        'search',               // Recherche générale (movie, theater, person, news, tvseries)
        'movie',                // Informations sur un film
        'reviewlist',           // Critiques sur un film (presse et public)
        'showtimelist',         // Horaires des cinémas
        'media',                // Informations sur une vidéo
        'person',               // Informations sur une personne
        'filmography',          // Filmographie d'une personne
        'movielist',            // Liste des films en salle
        'theaterlist',          // Liste des cinémas
        'tvseries',             // Informations sur une série TV
        'season',               // Informations sur la saison d'une série TV
        'episode',              // Informations sur l'épisode d'une série TV
    ];

    /**
     * Constructeur
     *
     * ### Exemple
     * - `$allo = new AlloCine();`
     *
     * Définit l'URL de base de l'API et les options de CURL
     */
    public function __construct()
    {
        $this->initialize('http://api.allocine.fr/rest/v3');
        $this->setCurlOptions($this->curlOptions);
        $this->setBrowser($this::USER_AGENT);
    }

    /**
     * Effectue une recherche sur AlloCine
     *
     * ### Exemple
     * - `$allo->search('Scarface');`
     *
     * @param string $term Terme à chercher (personne, film, série...)
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function search($term)
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'q' => $term
            ],
            $term
        );
    }

    /**
     * Obtenir les informations sur un film à partir de son code AlloCiné
     *
     * ### Exemple
     * - `$allo->movie(27022, 'large')->toArray();`
     *
     * ### Profile
     * - small, medium or large
     *
     * @param int    $codeMovie Code du film
     * @param string $profile   Type de profil retourné
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function movie($codeMovie, $profile = 'small')
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'code' => intval($codeMovie)
                , 'profile' => $profile
            ],
            $codeMovie
        );
    }

    /**
     * Obtenir les critiques d'un film par son code
     *
     * ### Exemple
     * - `$allo->reviewlist(27022, 'public');`
     *
     * ### Filter
     * - desk-press or public
     *
     * @param int         $codeMovie Code du film
     * @param string|null $filter    Type de critiques (desk-press ou public)
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function reviewlist($codeMovie, $filter = 'desk-press')
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'code' => intval($codeMovie)
                , 'type' => 'movie'
                , 'filter' => $filter
            ],
            $codeMovie
        );
    }

    /**
     * Obtenir les cinémas à partir d'un code postal
     * et dans un rayon donné
     *
     * ### Exemple
     * - `$allo->theaterlist('83190')->toArray();`
     *
     * @param string   $codeZip Code postal
     * @param int|null $radius  Rayon en nombre de kilomètres
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function theaterlist($codeZip, $radius = 50)
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'zip' => $codeZip
                , 'radius' => intval($radius)
            ],
            $codeZip
        );
    }

    /**
     * Obtenir les séances d'un cinéma pour un code postal,
     * un cinéma, un film
     *
     * @exemple $allo->showtimelist('83000', 'P0201', 240850);
     *
     * @param string      $codeZip     Code postal
     * @param string|null $codeTheater Code d'un cinéma
     * @param int|null    $codeMovie   Code d'un film
     * @param int|null    $radius      Rayon en kilomètres
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function showtimelist($codeZip, $codeTheater = null, $codeMovie = null, $radius = 25)
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'zip' => $codeZip
                , 'theater' => $codeTheater
                , 'movie' => $codeMovie
                , 'radius' => $radius
            ],
            $codeZip
        );
    }

    /**
     * Obtenir un media par son code
     *
     * @exemple $allo->media(18408293);
     *
     * @param int         $codeMedia Code du média
     * @param string|null $profile   Type de profil (small, medium ou large)
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function media($codeMedia, $profile = 'small')
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'code' => $codeMedia
                , 'profile' => $profile
            ],
            $codeMedia
        );
    }

    /**
     * Obtenir les informations sur une personne
     * à partir de son code
     *
     * @exemple $allo->person(1825, 'medium');
     *
     * @param int         $codePerson Code la personne
     * @param string|null $profile    Profile de la requête (small, medium ou large)
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function person($codePerson, $profile = 'small')
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'code' => $codePerson
                , 'profile' => $profile
            ],
            $codePerson
        );
    }

    /**
     * Obtenir la filmographie d'une personne
     *
     * @exemple $allo->filmography(1825, 'medium');
     *
     * @param int         $codePerson Code la personne
     * @param string|null $profile    Profile de la requête (small, medium ou large)
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     */
    public function filmography($codePerson, $profile = 'small')
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'code' => $codePerson
                , 'profile' => $profile
            ],
            $codePerson
        );
    }

    /**
     * Obtenir la liste des films en salle pour une personne
     *
     * @param int         $codePerson Code de la personne
     * @param string|null $profile    Profile de la requête (small, medium ou large)
     * @param bool|null   $comming    Si <code>true</code>, ce sont les films à venir qui sont retournés
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     */
    public function movielist($codePerson, $profile = 'small', $comming = false)
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'code' => $codePerson
                , 'profile' => $profile
                , 'filter' => $comming ? 'commingsoon' : 'nowshowing'
                , 'order' => 'datedesc'
            ],
            $codePerson
        );
    }

    /**
     * Obtenir les informations sur une série grâce à son code
     *
     * @exemple $allo->tvseries(4963, 'large');
     *
     * @param int         $codeSerie Code de la série
     * @param string|null $profile   Profile de la requête (small, medium ou large)
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     */
    public function tvseries($codeSerie, $profile = 'small')
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'code' => $codeSerie
                , 'profile' => $profile
            ],
            $codeSerie
        );
    }

    /**
     * Obtenir les informations sur une saison par son code
     *
     * @exemple $allo->season(9730, 'medium');
     *
     * @param int         $codeSeason Code de la série
     * @param string|null $profile    Profile de la requête (small, medium ou large)
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     */
    public function season($codeSeason, $profile = 'small')
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'code' => $codeSeason
                , 'profile' => $profile
            ],
            $codeSeason
        );
    }

    /**
     * Obtenir les informations sur un épisode
     * à partir de son code
     *
     * @param int         $codeEpisode Code de l'épisode
     * @param string|null $profile     Profile de la requête (small, medium ou large)
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     */
    public function episode($codeEpisode, $profile = 'small')
    {
        return $this->makeRequest(
            __FUNCTION__,
            [
                'code' => $codeEpisode
                , 'profile' => $profile
            ],
            $codeEpisode
        );
    }

    /**
     * Génère l'URL correspondant à la méthode
     *
     * @param string     $method Nom de la méthode à utiliser
     * @param array|null $params Paramètres de la requête
     *
     * @return string
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    private function makeUrl($method, array $params = [])
    {
//        if (!in_array($method, $this->methods)) {
//            throw new ApiException(
//                "Méthode $method introuvable ! Essayez plutôt avec une de celles-ci : "
//                  . implode(', ', $this->methods)
//            );
//        }
        $this->addUrlPart($method);
        $this->addParams($params, null, true);
        $this->addParams([
            'format' => $this->format
            , 'partner' => $this::PARTNER
        ]);
        $sed = date('Ymd');
        $sig = urlencode(
            base64_encode(
                sha1($this::KEY . str_replace('?', '', $this->getParams()) . '&sed=' . $sed, true)
            )
        );
        $url = $this->url(false) . $this->getParams() . '&sed=' . $sed . '&sig=' . $sig;
        return $url;
    }

    /**
     * Effectuer la requête auprès de l'API
     *
     * @param string $method   Nom de la méthode de l'API
     * @param array  $params   Paramètres de la requête
     * @param string $logTitle Titre de la requête dans le journal
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    private function makeRequest($method, array $params, $logTitle)
    {
        return $this->r(
            $this->makeUrl($method, $params),
            ucfirst($method) . ' : ' . $logTitle
        );
    }
}
