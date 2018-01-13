<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\TimeExtension;
use Tests\Rcnchris\BaseTestCase;

class TimeExtensionTest extends BaseTestCase {

    /**
     * @var TimeExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new TimeExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Time');
        $this->assertInstanceOf(TimeExtension::class, $this->ext);
        $this->assertNotEmpty($this->ext->getFilters());
        $this->assertNotEmpty($this->ext->getFunctions());
    }

    public function testAgo()
    {
        $date = new \DateTime();
        $this->assertEquals(
            '<span class="timeago" datetime="' . $date->format(\DateTime::ISO8601) . '">' . $date->format('d/m/Y H:i') . '</span>',
            $this->ext->ago($date)
        );
    }

    public function testAgoWithString()
    {
        $date = '15-10-1975';
        $oDate = new \DateTime($date);
        $this->assertEquals(
            '<span class="timeago" datetime="' . $oDate->format(\DateTime::ISO8601) . '">' . $oDate->format('d/m/Y H:i') . '</span>',
            $this->ext->ago($date)
        );
    }

    public function testNow()
    {
        $this->assertInternalType('float', $this->ext->now());
    }
}
