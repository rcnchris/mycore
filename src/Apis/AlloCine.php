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
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
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

    public function __construct()
    {
        $this->initialize('http://api.allocine.fr/rest/v3');
        $this->setCurlOptions([]);
    }

    public function search($term)
    {
        $url = $this->makeUrl(__FUNCTION__, ['q' => $term]);
        return $this->r($url, ucfirst(__FUNCTION__) . ' : ' . $term);
    }

    private function makeUrl($method, array $params = [])
    {
        $this->addParams($params, null, true);
        $this->addParams([
            'format' => $this->format
            , 'partner' => $this::PARTNER
        ]);
        $sed = date('Ymd');
        $sig = urlencode(base64_encode(sha1($this::KEY . $this->getParams() . '&sed=' . $sed, true)));
        $this->addPart($method);
        $url = $this->url(false) . $this->getParams() . '&sed=' . $sed . '&sig=' . $sig;
        return $url;
    }
}
