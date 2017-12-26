<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\ApiTrait;
use Rcnchris\Core\Apis\OneApi;
use Tests\Rcnchris\BaseTestCase;

class ApiTraitTest extends BaseTestCase {

    /**
     * @var
     */
    private $trait;

    /**
     * @var OneApi
     */
    private $api;

    public function setUp()
    {
        if (!extension_loaded('curl')) {
            $this->markTestSkipped(
                "Le test n'est pas effectué car l'extension Curl n'est pas installée."
            );
        }

        $this->trait = $this->getMockForTrait(
            APITrait::class,
            ['https://randomuser.me/api']
        );

        $this->api = new OneApi('https://randomuser.me/api');
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Trait', true);
        $this->assertTrue(true);
    }

    /**
     * Vérifier la présence de curl.
     */
    public function testCurlResource()
    {
        $this->assertAttributeInternalType('resource', 'curl', $this->trait);
        $this->assertInternalType('resource', $this->api->getCurl());
    }

    public function testGetParams()
    {
        $this->assertInternalType('array', $this->trait->getParams());
        $this->assertInternalType('string', $this->trait->getParams(true));
    }
}
