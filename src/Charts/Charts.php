<?php
/**
 * Fichier Charts.php du 14/04/2018 
 * Description : Fichier de la classe Charts 
 *
 * PHP version 5
 *
 * @category Graphoiques
 *
 * @package Rcnchris\Core\Charts
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Charts;

/**
 * Class Charts
 *
 * @category Graphoiques
 *
 * @package Rcnchris\Core\Charts
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @version Release: <1.0.0>
 */
class Charts
{

    /**
     * Données du graphique
     *
     * @var mixed
     */
    private $data;

    public function __construct($data)
    {

        $this->data = $data;
    }
}