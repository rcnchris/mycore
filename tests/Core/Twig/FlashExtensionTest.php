<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Session\ArraySession;
use Rcnchris\Core\Session\FlashService;
use Rcnchris\Core\Twig\FlashExtension;
use Tests\Rcnchris\BaseTestCase;

class FlashExtensionTest extends BaseTestCase {

    /**
     * @var FlashService
     */
    private $flashService;

    /**
     * @var FlashExtension
     */
    private $ext;

    public function setUp()
    {
        $session =new ArraySession();
        $this->flashService = new FlashService($session);
        $this->ext = new FlashExtension($this->flashService);
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Messages Flash');
        $this->assertNotEmpty($this->ext->getFunctions());
    }

    public function testGetFlash()
    {
        $this->flashService->success('Bravo');
        $this->assertEquals('Bravo', $this->ext->getFlash('success'));
        $this->assertNull($this->ext->getFlash('fake'));
    }
}
