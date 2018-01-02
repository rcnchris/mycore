<?php
/**
 * Fichier SynologyPackage.php du 07/11/2017
 * Description : Fichier de la classe Package
 *
 * PHP version 7
 *
 * @category Synology
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
 * Class SynologyPackage<br/>
 * <ul>
 * <li>Classe parente de tous les packages d'un NAS Synology (AudioStation, FileStation...)</li>
 * </ul>
 *
 * @category Synology
 *
 * @package  Rcnchris\Core\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class SynologyPackage
{

    /**
     * Nom du package
     *
     * @var string
     */
    private $name;

    /**
     * Instance de AbstractAPI
     *
     * @var SynologyAbstract
     */
    private $abstract;

    /**
     * Constructeur
     *
     * @param string                                        $name     Nom du package (API, AudioStation...)
     * @param \Rcnchris\Core\Apis\Synology\SynologyAbstract $abstract Instance de l'abstraction Synology
     *
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function __construct($name, SynologyAbstract $abstract)
    {
        $this->abstract = $abstract;
        if (!$this->abstract->hasPackage($name)) {
            throw new SynologyException(
                "Le package $name est introuvable sur " . $this->abstract->getConfig('description')
            );
        }
        $this->name = $name;
    }

    /**
     * Obtenir le nom du package
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Obtenir la liste des APIs du package
     *
     * @return array
     */
    public function getApis()
    {
        $a = $this->abstract;
        $prefix = $a::PREFIXE_API . '.' . $this->getName() . '.';
        $keysForPackage = [];
        $apis = $this->abstract->getApis();
        foreach ($apis as $key) {
            if (preg_match("#$prefix#", $key)) {
                $keysForPackage[] = $key;
            }
        }
        return $keysForPackage;
    }

    /**
     * Obtenir la définition de l'API
     *
     * @param string $name Nom court de l'API (Genre, Movie...)
     *
     * @return mixed
     */
    public function getDefinition($name)
    {
        $a = $this->abstract;
        return $this->abstract->getApiDef($a::PREFIXE_API . '.' . $this->getName() . '.' . $name);
    }
}
