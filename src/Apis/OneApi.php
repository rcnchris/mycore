<?php
/**
 * Fichier OneApi.php du 10/10/2017
 * Description : Fichier de la classe OneApi
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis;

use Rcnchris\Core\Tools\Collection;

/**
 * Class OneApi<br/>
 * <ul>
 * <li>Utilisation de n'importe quelle API à partir de son URL</li>
 * </ul>
 *
 * @category API
 *
 * @package  Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class OneApi
{
    use ApiTrait;

    /**
     * Constructeur
     *
     * @exemple $api = new OneApi('https://randomuser.me/api');
     *
     * @param string $url URL de base de l'API
     *
     * @throws \Rcnchris\Core\Exceptions\ApiException
     */
    public function __construct($url)
    {
        $this->initialize($url);
    }

    /**
     * Effectuer une requête
     *
     * @exemple $api->addParams(['results' => 3])->request('Get 3 users')->get('results')->toArray();
     *
     * @param string $title Titre de la requête, utilisé dans les log
     *
     * @return array|mixed|\Rcnchris\Core\Tools\Collection
     * @throws \Rcnchris\Core\Exceptions\ApiException
     */
    public function request($title = 'Unknow title')
    {
        $response = $this->_request($this->makeUrl(), $title);
        return is_array($response) && !empty($response)
            ? new Collection($response, $title)
            : $response;
    }

    /**
     * Obtenir le journal des requêtes exécutées
     *
     * @exemple $api->getLog()->toArray();
     *
     * @param bool|null $full Si faux, retourne uniquement le titre et la durée de chaque requête
     *
     * @return array|Collection
     */
    public function getLog($full = true)
    {
        $log = new Collection(
            $this->log(),
            "Journal des requêtes exécutées"
        );
        return $full
            ? $log
            : $log->extract('name', 'detail.total_time')->toArray();
    }
}
