<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\Synology;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class SynologyTest extends BaseTestCase
{

    /**
     * @var Synology
     */
    private $syno;

    public function setUp()
    {
        $this->syno = $this->makeSyno();
    }

    /**
     * @param string $server
     *
     * @return \Rcnchris\Core\Apis\Synology\Synology
     */
    public function makeSyno($server = 'nas')
    {
        return new Synology($this->getConfig('synology')[$server]);
    }

    /**
     * Obtenir une instance
     */
    public function testInstance()
    {
        $this->ekoTitre('API - Synology');
        $this->assertInstanceOf(Synology::class, $this->syno);
    }

    /**
     * Effectuer une requête sur une API
     */
    public function testRequest()
    {
        $videos = $this->syno->request('VideoStation.Movie', 'list', ['limit' => 3]);
        $this->assertInstanceOf(Items::class, $videos);
    }

    /**
     * Effectuer une requête sur une API et se déconnecter
     */
    public function testRequestAndLogout()
    {
        $videos = $this->syno->request('VideoStation.Movie', 'list', ['limit' => 3]);
        $this->assertInstanceOf(Items::class, $videos);
        $this->assertTrue($this->syno->logout('VideoStation.Movie'));
    }

    /**
     * Effectuer une requête sur une API en spécifiant la version et se déconnecter
     */
    public function testRequestWithVersion()
    {
        $videos = $this->syno->request('VideoStation.Movie', 'list', ['version' => 2, 'limit' => 3]);
        $this->assertInstanceOf(Items::class, $videos);
        $this->assertTrue($this->syno->logout('VideoStation.Movie'));
    }

    /**
     * Effectuer une requête avec une mauvaise API
     */
    public function testRequestWithWrongApi()
    {
        $videos = $this->syno->request('VideoStation.Movies', 'list', ['limit' => 3]);
        $this->assertInstanceOf(Items::class, $videos);
        $this->assertArrayHasKeys($videos->toArray(), 'nas,api,method,params,code,message,data,cURL,config,logs');
    }

    /**
     * Effectuer une requête avec une mauvaise méthode
     */
    public function testRequestWithWrongMethod()
    {
        $videos = $this->syno->request('VideoStation.Movie', 'liste', ['limit' => 3]);
        $this->assertInstanceOf(Items::class, $videos);
        $this->assertArrayHasKeys($videos->toArray(), 'nas,api,method,params,code,message,data,cURL,config,logs');
        $this->assertTrue($this->syno->logout('VideoStation.Movie'));
    }

    /**
     * Effectuer une requête sur une API et ne se connecter qu'une seule fois
     * La demande de définition de l'API ne doit ête faite qu'une seule fois aussi
     */
    public function testLoginOnceTime()
    {
        $this->syno->request('VideoStation.Movie', 'list', ['limit' => 3]);
        $this->syno->request('VideoStation.Movie', 'list', ['limit' => 5]);
        $this->assertTrue($this->syno->logout('VideoStation.Movie'));
        $this->assertCount(5, $this->syno->getLog());
    }

    /**
     * Effectuer une requête sur une API et ne se connecter qu'une seule fois
     * La demande de définition de l'API ne doit ête faite qu'une seule fois aussi
     */
    public function testLoginTwoTime()
    {
        $this->syno->request(
            'VideoStation.Movie',
            'list',
            ['limit' => 3]
        );
        $this->syno->request(
            'VideoStation.Movie',
            'list',
            ['limit' => 5, 'account' => 'phpunit', 'passwd' => 'mycoretest']
        );
        $this->assertTrue($this->syno->logout('VideoStation.Movie'));
        // Definition API
        // Login VideoStation user1
        // List Movie
        // Login VideoStation user2
        // List Movie
        // Logout VideoStation
        $this->assertCount(6, $this->syno->getLog());
    }

    /**
     * Obtenir le logo Synology
     */
    public function testLogo()
    {
        $this->assertInternalType('string', $this->syno->logo());
        $this->assertInternalType('string', $this->syno->logo(['class' => 'img-tumbnail']));
    }

    /**
     * Obtenir tous les messages d'erreurs Synology
     */
    public function testGetErrorMessages()
    {
        $messages = $this->syno->getErrorsMessages();
        $this->assertInstanceOf(Items::class, $messages);
        $this->assertArrayHasKeys($messages->toArray(), 'AudioStation,FileStation,100,400');
    }

    /**
     * Obtenir les messages d'erreurs Synology pour une API
     */
    public function testGetErrorMessagesWithOnlyApiName()
    {
        $messages = $this->syno->getErrorsMessages('DownloadStation');
        $this->assertInstanceOf(Items::class, $messages);
        $this->assertArrayHasKeys($messages->toArray(), '400,401,405');
    }

    /**
     * Obtenir le message d'erreur Synology pour une API et un code
     */
    public function testGetErrorMessagesWithApiNameAndCode()
    {
        $this->assertInternalType(
            'string',
            $this->syno->getErrorsMessages('DownloadStation', 405)
        );
    }

    /**
     * Obtebir les sid de connexion aux API
     */
    public function testGetSids()
    {
        $this->syno->request('VideoStation.Movie', 'list', ['limit' => 3]);
        $this->syno->request('AudioStation.Genre', 'list', ['limit' => 5]);
        $this->syno->request('AudioStation.Genre', 'list', ['limit' => 5, 'account' => 'phpunit', 'passwd' => 'mycoretest']);

        $sids = $this->syno->getSids();
        $this->assertCount(2, $sids);
        $this->assertArrayHasKeys($sids, 'SYNO.VideoStation.Movie,SYNO.AudioStation.Genre');
        $this->assertArrayHasKeys($sids['SYNO.VideoStation.Movie'], 'rcn');
        $this->assertArrayHasKeys($sids['SYNO.AudioStation.Genre'], 'rcn,phpunit');
        $this->assertInternalType('string', $this->syno->getSids('SYNO.VideoStation.Movie'));

        $this->assertTrue($this->syno->logout('VideoStation.Movie'));

        $sids = $this->syno->getSids();
        $this->assertCount(1, $sids);
        $this->assertArrayHasKeys($sids, 'SYNO.AudioStation.Genre');
        $this->assertArrayHasKeys($sids['SYNO.AudioStation.Genre'], 'rcn,phpunit');
        $this->assertTrue($this->syno->logout('AudioStation.Genre'));

        $this->assertFalse($this->syno->getSids());

        $this->assertCount(10, $this->syno->getLog());
    }

    /**
     * Obtenir la définition d'une API depuis le fichier JSON qui lui est dédié
     */
    public function testGetJsonDefinition()
    {
        $this->assertInstanceOf(Items::class, $this->syno->getJsonDefinition('AudioStation'));
        $this->assertFalse($this->syno->getJsonDefinition('FakeStation'));
    }

    /**
     * Obtenir la définition de plusieurs API ou toutes
     */
    public function testGetMultipleApiDefinitions()
    {
        $this->syno->getApiDefinitions(['VideoStation.Movie', 'AudioStation.Genre']);
        $this->syno->request('VideoStation.Movie', 'list', ['limit' => 3]);
        $this->syno->request('AudioStation.Genre', 'list', ['limit' => 3]);
        $this->assertTrue($this->syno->logout('VideoStation.Movie'));
        $this->assertTrue($this->syno->logout('AudioStation.Genre'));
        $this->assertCount(7, $this->syno->getLog());
    }
}
