<?php
/**
 * Fichier SynologyAPIEntity.php du 11/08/2018
 * Description : Fichier de la classe SynologyAPIEntity
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis\Synology;

/**
 * Class SynologyAPIEntity
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class SynologyAPIEntity
{
    /**
     * Instance
     *
     * @var \Rcnchris\Core\Apis\Synology\SynologyAPIPackage
     */
    private $package;

    /**
     * Constructeur
     *
     * @param array                                           $content
     * @param \Rcnchris\Core\Apis\Synology\SynologyAPIPackage $package
     */
    public function __construct(SynologyAPIPackage $package, array $content = [])
    {
        $this->setPackage($package);
        $this->addFields($content);
    }

    /**
     * Obtenir le package de l'entité
     *
     * @return SynologyAPIPackage
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Définir le package de l'entité
     *
     * @param SynologyAPIPackage $package
     *
     * @return $this
     */
    public function setPackage($package)
    {
        $this->package = $package;
        return $this;
    }

    /**
     * Ajouter les clés du tableau comme propriétés de l'instance
     *
     * @param array $content Tableau de données de l'entité
     *
     * @return $this
     */
    private function addFields(array $content = [])
    {
        foreach ($content as $property => $value) {
            $this->$property = $value;
        }
        return $this;
    }
}
