<?php
namespace Tests\Rcnchris\Core\Apis\ApiGouv;

use Rcnchris\Core\Apis\ApiException;
use Rcnchris\Core\Apis\ApiGouv\EtablissementsPublicsApiGouv;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class EtablissementsPublicsApiGouvTest extends BaseTestCase
{

    /**
     * @return \Rcnchris\Core\Apis\ApiGouv\EtablissementsPublicsApiGouv
     */
    private function makeEtablissementsApiGouv()
    {
        return new EtablissementsPublicsApiGouv();
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Api Gouv Organismes');
        $this->assertInstanceOf(EtablissementsPublicsApiGouv::class, $this->makeEtablissementsApiGouv());
    }

    public function testGetByDepartement()
    {
        $this->ekoMessage("Organisme d'un département");
        $this->assertInstanceOf(Items::class, $this->makeEtablissementsApiGouv()->getByDepartement(83, 'cpam'));
    }

    public function testGetByDepartementWithWrongType()
    {
        $this->ekoMessage("Organisme d'un département d'un mauvais type");
        $this->expectException(ApiException::class);
        $this->makeEtablissementsApiGouv()->getByDepartement(83, 'fake');
    }

    public function testGetTypes()
    {
        $this->ekoMessage("Types d'organismes disponibles");
        $this->assertInstanceOf(Items::class, $this->makeEtablissementsApiGouv()->getTypes());
    }
}
