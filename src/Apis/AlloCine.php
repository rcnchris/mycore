<?php
/**
 * Fichier AlloCine.php du 08/08/2018
 * Description : Fichier de la classe AlloCine
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

namespace Rcnchris\Core\Apis;

/**
 * Class AlloCine
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <2.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class AlloCine extends Curl
{

    /**
     * Code partenaire pour AlloCine
     *
     * @var string
     */
    private $partner = '100043982026';

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
     * Clé de l'API
     *
     * @var string
     */
    private $api_key;

    /**
     * Constructeur
     * Définit l'URL de base, la clé de l'API et le navigateur à utiliser
     */
    public function __construct()
    {
        parent::__construct('http://api.allocine.fr/rest/v3');
        $this->setApiKey('29d185d98c984a359e6e6f26a0474269');
        $this->withUserAgent('Dalvik/1.6.0 (Linux; U; Android 4.2.2; Nexus 4 Build/JDQ39E)');
    }

    /**
     * Définir la clé de l'API
     *
     * @param string $api_key Clé de l'API
     *
     * @return $this
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    /**
     * Effectuer une recherche d'un terme sur AlloCiné
     * - `$allo->search('Le Parrain')->get('movie')->toArray();`
     *
     * @param string $text Terme à chercher sur AlloCiné
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function search($text)
    {
        return $this
            ->withParts(__FUNCTION__)
            ->withParams(['q' => $text])
            ->makeUrl()
            ->exec("Recherche $text")
            ->getResponse();
    }

    /**
     * Obtenir les information d'un fil à partir de son code sur AlloCiné
     *
     * @param int         $codeMovie Code du film sur AlloCiné
     * @param string|null $profile   Profil de retour de la réponse (small, medium ou large)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function movie($codeMovie, $profile = 'small')
    {
        return $this
            ->withParts(__FUNCTION__)
            ->withParams([
                'code' => intval($codeMovie),
                'profile' => $profile
            ])
            ->makeUrl()
            ->exec("Film $codeMovie")
            ->getResponse();
    }

    /**
     * Obtenir les critiques d'un film à partir de son code sur AlloCiné
     *
     * @param int         $codeMovie Code du film sur AlloCiné
     * @param string|null $filter    Type de critiques (desk-press ou public)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function reviewsOfMovie($codeMovie, $filter = 'desk-press')
    {
        return $this
            ->withParts('reviewlist')
            ->withParams([
                'code' => intval($codeMovie),
                'type' => 'movie',
                'filter' => $filter
            ])
            ->makeUrl()
            ->exec("Critiques du film $codeMovie")
            ->getResponse();
    }

    /**
     * Obtenir la liste des cinémas dans un rayon de kilomètres donné
     *
     * @param int|string $codeZip Code postal d'une ville
     * @param int|null   $radius  Rayon en kilomètres de la recherche
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function theaters($codeZip, $radius = 50)
    {
        return $this
            ->withParts('theaterlist')
            ->withParams([
                'zip' => $codeZip,
                'radius' => intval($radius)
            ])
            ->makeUrl()
            ->exec("Cinémas dans $codeZip")
            ->getResponse();
    }

    /**
     * Obtenir les séances disponibles
     *
     * @param int|string      $codeZip     Code postal de la recherche
     * @param string|null     $codeTheater Code du cinéma sur AlloCiné
     * @param int|string|null $codeMovie   Code du film sur AlloCiné
     * @param int|null        $radius      Rayon de la recherche en kilomomères
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function showTimes($codeZip, $codeTheater = null, $codeMovie = null, $radius = 50)
    {
        return $this
            ->withParts('showtimelist')
            ->withParams([
                'zip' => $codeZip,
                'theater' => $codeTheater,
                'movie' => $codeMovie,
                'radius' => intval($radius)
            ])
            ->makeUrl()
            ->exec("Séances dans $codeZip")
            ->getResponse();
    }

    /**
     * Obtenir un média par son code
     *
     * @param int         $codeMedia Code du média sur AlloCiné
     * @param string|null $profile   Profil du retour de la réponse (small, medium ou large)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function media($codeMedia, $profile = 'small')
    {
        return $this
            ->withParts('media')
            ->withParams([
                'code' => $codeMedia,
                'profile' => $profile
            ])
            ->makeUrl()
            ->exec("Média $codeMedia")
            ->getResponse();
    }

    /**
     * Obtenir les informations d'une personne par son code sur AlloCinné
     *
     * @param int         $codePerson Code de la personne sur AlloCinné
     * @param string|null $profile    Profil du retour de la réponse (small, medium ou large)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function person($codePerson, $profile = 'small')
    {
        return $this
            ->withParts(__FUNCTION__)
            ->withParams([
                'code' => $codePerson,
                'profile' => $profile
            ])
            ->makeUrl()
            ->exec("Personne $codePerson")
            ->getResponse();
    }

    /**
     * Obtenir la filmographie d'une personne par son code sur AlloCiné
     *
     * @param int         $codePerson Code de la personne sur AlloCiné
     * @param string|null $profile    Profil du retour de la réponse (small, medium ou large)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function filmographyOfPerson($codePerson, $profile = 'small')
    {
        return $this
            ->withParts('filmography')
            ->withParams([
                'code' => $codePerson,
                'profile' => $profile
            ])
            ->makeUrl()
            ->exec("Filmographie de $codePerson")
            ->getResponse();
    }

    /**
     * Obtenir la liste des films en salles pour une person par son code sur AlloCiné
     *
     * @param int         $codePerson Code de la personne sur AlloCiné
     * @param string|null $profile    Profil du retour de la réponse (small, medium ou large)
     * @param bool|null   $comming    Si vrai, ce sont les films à venir qui sont retournés
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function currentMoviesOfPerson($codePerson, $profile = 'small', $comming = false)
    {
        return $this
            ->withParts('movielist')
            ->withParams([
                'code' => $codePerson,
                'profile' => $profile,
                'filter' => $comming ? 'commingsoon' : 'nowshowing',
                'order' => 'datedesc'
            ])
            ->makeUrl()
            ->exec("Films en cours de $codePerson")
            ->getResponse();
    }

    /**
     * Obtenir une série par son code sur AlloCiné
     * - `$allo->tvseries(4963, 'large')->toArray();`
     *
     * @param int         $codeSerie Code de la série sur AlloCiné
     * @param string|null $profile   Profil du retour de la réponse (small, medium ou large)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function tvseries($codeSerie, $profile = 'small')
    {
        return $this
            ->withParts(__FUNCTION__)
            ->withParams([
                'code' => $codeSerie,
                'profile' => $profile
            ])
            ->makeUrl()
            ->exec("Série $codeSerie")
            ->getResponse();
    }

    /**
     * Obtenir une saison d'une série par son code sur AlloCiné
     * - `$allo->season(4963, 'large')->toArray();`
     *
     * @param int         $codeSeason Code de la saison sur AlloCiné
     * @param string|null $profile    Profil du retour de la réponse (small, medium ou large)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function season($codeSeason, $profile = 'small')
    {
        return $this
            ->withParts(__FUNCTION__)
            ->withParams([
                'code' => $codeSeason,
                'profile' => $profile
            ])
            ->makeUrl()
            ->exec("Saison $codeSeason")
            ->getResponse();
    }

    /**
     * Obtenir un épisode d'une saison par son code sur AlloCiné
     * - `$allo->episode(230135, 'large')->toArray();`
     *
     * @param int         $codeEpisode Code de la saison sur AlloCiné
     * @param string|null $profile     Profil du retour de la réponse (small, medium ou large)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function episode($codeEpisode, $profile = 'small')
    {
        return $this
            ->withParts(__FUNCTION__)
            ->withParams([
                'code' => $codeEpisode,
                'profile' => $profile
            ])
            ->makeUrl()
            ->exec("Episode $codeEpisode")
            ->getResponse();
    }

    /**
     * Obtenir la liste des méthodes de l'API
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Obtenir la clé de l'API
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Fabrique et définit l'URL conforme à la structure de l'API
     *
     * @return $this
     */
    private function makeUrl()
    {
        $this->withParams([
            'format' => 'json',
            'partner' => $this->partner
        ]);

        $sed = date('Ymd');
        $sig = urlencode(
            base64_encode(
                sha1($this->getApiKey() . $this->getParams(true) . '&sed=' . $sed, true)
            )
        );
        $url = $this->getUrl(true, false) . '?' . $this->getParams(true) . '&sed=' . $sed . '&sig=' . $sig;
        $this->setUrl($url);
        return $this;
    }
}
