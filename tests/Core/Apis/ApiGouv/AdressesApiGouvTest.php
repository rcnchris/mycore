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
        $regions = $this->makeAdressesApiGouv()->getRegions();
        $this->assertInstanceOf(Items::class, $regions);
    }

    public function testGetDepartements()
    {
        $departements = $this->makeAdressesApiGouv()->getDepartements();
        $this->assertInstanceOf(Items::class, $departements);
    }

    public function testGetCommunes()
    {
        $communes = $this->makeAdressesApiGouv()->getCommunes(83000);
        $this->assertInstanceOf(Items::class, $communes);
    }

    public function testGetFieldsCommunes()
    {
        $this->assertNotEmpty($this->makeAdressesApiGouv()->getFieldsCommmunes());
    }

    public function testGetFieldsDepartements()
    {
        $this->assertNotEmpty($this->makeAdressesApiGouv()->getFieldsDepartements());
    }

    public function testSetFieldsCommunes()
    {
        $api = $this->makeAdressesApiGouv();
        $api->setFieldsCommmunes('code,nom');
        $this->assertNotEmpty($this->makeAdressesApiGouv()->getFieldsCommmunes());
    }

    public function testSetFieldsDepartements()
    {
        $api = $this->makeAdressesApiGouv();
        $api->setFieldsDepartements('code,nom');
        $this->assertNotEmpty($this->makeAdressesApiGouv()->getFieldsDepartements());
    }
}