<?php
namespace Tests\App;
use Tests\Rcnchris\BaseTestCase;

class PagesTest extends BaseTestCase
{
    public function testGetHomepage()
    {
        $this->ekoTitre('Application - Routing');
        $response = $this->runApp('GET', '/_lab/mycore/');
        $this->assertEquals(
            200
            , $response->getStatusCode()
            , $this->getMessage("Le statut de la réponse de la page d'accueil est incorrect")
        );
    }

    public function testGetOnePage()
    {
        $response = $this->runApp('GET', '/_lab/mycore/tools/common');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetWrongUrl()
    {
        $response = $this->runApp('GET', '/_lab/mycore/fake');
        $this->assertEquals(404, $response->getStatusCode());
    }
}