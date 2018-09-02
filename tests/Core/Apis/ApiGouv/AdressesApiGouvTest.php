<?php
namespace Tests\Rcnchris\Core\Apis\ApiGouv;

use Rcnchris\Core\Apis\ApiGouv\AdressesApiGouv;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class AdressesApiGouvTest extends BaseTestCase
{

    /**
     * @return \Rcnchris\Core\Apis\ApiGouv\AdressesApiGouv
     */
    public function makeAdressesApiGouv()
    {
        return new AdressesApiGouv();
    }

    public function testInstance()
    {
        $this->ekoTitre('API - API Gouv Adresses');
        $this->assertInstanceOf(AdressesApiGouv::class, $this->makeAdressesApiGouv());
    }

    public function testGetRegions()
    {
        $this->ekoMessage("Régions");
        $regions = $this->makeAdressesApiGouv()->getRegions();
        $this->assertInstanceOf(Items::class, $regions);
    }

    public function testGetDepartements()
    {
        $this->ekoMessage("Départements");
        $departements = $this->makeAdressesApiGouv()->getDepartements();
        $this->assertInstanceOf(Items::class, $departements);
    }

    public function testGetCommunes()
    {
        $this->ekoMessage("Communes");
        $communes = $this->makeAdressesApiGouv()->getCommunes(83000);
        $this->assertInstanceOf(Items::class, $communes);
    }

    public function testGetFieldsCommunes()
    {
        $this->ekoMessage("Colonnes communes");
        $this->assertNotEmpty($this->makeAdressesApiGouv()->getFieldsCommmunes());
    }

    public function testGetFieldsDepartements()
    {
        $this->ekoMessage("Colonnes départements");
        $this->assertNotEmpty($this->makeAdressesApiGouv()->getFieldsDepartements());
    }

    public function testSetFieldsCommunes()
    {
        $this->ekoMessage("Définir les colonnes pour les communes");
        $api = $this->makeAdressesApiGouv();
        $api->setFieldsCommmunes('code,nom');
        $this->assertNotEmpty($this->makeAdressesApiGouv()->getFieldsCommmunes());
    }

    public function testSetFieldsDepartements()
    {
        $this->ekoMessage("Définir les colonnes pour les départements");
        $api = $this->makeAdressesApiGouv();
        $api->setFieldsDepartements('code,nom');
        $this->assertNotEmpty($this->makeAdressesApiGouv()->getFieldsDepartements());
    }
}