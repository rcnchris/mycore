<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\SynologyAPI;
use Rcnchris\Core\Apis\Synology\SynologyAPIPackage;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class SynologyBaseTestCase extends BaseTestCase
{
    /**
     * Obtenir l'instance de l'API Synology
     *
     * @param null $config Configuration de connexion
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPI
     */
    protected function makeSynoAPI($config = null)
    {
        return is_null($config)
            ? new SynologyAPI($this->getConfig('synology')['nas'])
            : new SynologyAPI($config);
    }

    /**
     * Obtenir l'instance d'un package de l'API Synology
     *
     * @param string $packageName nom du package (DownloadStation, AudioStation...)
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIPackage
     */
    protected function makeSynologyPackage($packageName)
    {
        return $this->makeSynoAPI()->getPackage($packageName);
    }

    /**
     * Vérifier l'obtention de lists d'une API Synology
     *
     * @param SynologyAPIPackage $package Instance du package
     * @param string             $method  Nom de la méthode du package
     * @param array              $params  Paramètres à vérifier
     */
    protected function assertSynologyList(SynologyAPIPackage $package, $method, array $params = [])
    {
        // Vérification des paramètres envoyés
        $this->assertArrayHasKeys(
            $params,
            'expectedResponseKeys,itemsKey,expectedItemKeys,extractKey,typeItemsKey'
        );
        //r($params);

        // Exécution de la méthode de l'API
        $items = isset($params['params'])
            ? $package->$method($params['params'])
            : $package->$method();

        // Vérification de l'instance de la réponse
        $this->assertInstanceOf(
            Items::class,
            $items,
            $this->getMessage("La réponse n'est pas une instance de Items"
            )
        );

        // Vérification du nom des clés dans la réponse
        $this->assertArrayHasKeys(
            $items->toArray(),
            $params['expectedResponseKeys'],
            $this->getMessage("Le nom des clés de la réponse est incorrect")
        );

        // Vérification du nombre d'items
        if (isset($params['params']['limit'])) {
            $this->assertTrue(
                $items->get($params['itemsKey'])->count() <= $params['params']['limit'],
                $this->getMessage("Le nombre d'items de la réponse est incorrect")
            );
        }

        // Vérification du nom des clés des items
        $this->assertArrayHasKeys(
            $items->get($params['itemsKey'])->first()->toArray(),
            $params['expectedItemKeys'],
            $this->getMessage("Le nom des clés des items est incorrect")
        );

        // Extraction
        $list = isset($params['params'])
            ? $package->$method($params['params'], $params['extractKey'])
            : $package->$method($params['extractKey']);

        // Vérification du type de la réponse
        $this->assertInternalType(
            'array',
            $list,
            $this->getMessage("L'extraction doit retourner un tableau et pas un " . gettype($list))
        );

        // Vérification du nom d'items retourné
        if (isset($params['params']['limit'])) {
            $this->assertTrue(
                count($list) <= isset($params['params']['limit']),
                $this->getMessage("Le nombre d'items de l'extraction est incorrect (3) : " . count($list)
                )
            );
        }

        // Vérification du type de la clé des items extraits
        $this->assertInternalType(
            $params['typeItemsKey'],
            current(array_keys($list)),
            $this->getMessage("Le type de clé de l'extraction n'est pas celui attendu ({$params['typeItemsKey']}) : " . gettype(current(array_keys($list)))
            )
        );
    }
}