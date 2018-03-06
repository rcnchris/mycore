<?php
/**
 * Fichier FlashServiceTest.php du 05/03/2018
 * Description : Fichier de la classe FlashServiceTest
 *
 * PHP version 5
 *
 * @category New
 *
 * @package  Tests\Rcnchris\Core\Session
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\Session;

use Rcnchris\Core\Session\ArraySession;
use Rcnchris\Core\Session\FlashService;
use Tests\Rcnchris\BaseTestCase;

/**
 * Class FlashServiceTest
 *
 * @category New
 *
 * @package  Tests\Rcnchris\Core\Session
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class FlashServiceTest extends BaseTestCase
{

    /**
     * @var ArraySession
     */
    private $flashService;

    /**
     * @var FlashService
     */
    private $session;

    public function setUp()
    {
        $this->session = new ArraySession();
        $this->flashService = new FlashService($this->session);
    }

    public function testDeleteFlashAfterGettingIt()
    {
        $this->ekoTitre("Session - Messages Flash");
        $this->flashService->success('Bravo');
        $this->assertEquals('Bravo', $this->flashService->get('success'));
        $this->assertNull($this->session->get('flash'));
        $this->assertEquals('Bravo', $this->flashService->get('success'));
        $this->assertEquals('Bravo', $this->flashService->get('success'));
    }

    public function testGetWithWrongType()
    {
        $this->assertNull($this->flashService->get('fake'));
    }
}