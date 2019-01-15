<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Text;
use Tests\Rcnchris\BaseTestCase;

class TextTest extends BaseTestCase
{
    public function testInstance()
    {
        $this->ekoTitre('Tools - Text');
        $this->assertInstanceOf(Text::class, Text::getInstance());
    }

    public function testHasHelp()
    {
        $this->assertHasHelp(Text::getInstance());
    }

    public function testGetBefore()
    {
        $texte = 'ola,oli';
        $this->assertEquals('ola', Text::getBefore(',', $texte));
    }

    public function testGetAfter()
    {
        $texte = 'ola,oli';
        $this->assertEquals('oli', Text::getAfter(',', $texte));
    }

    public function testSerialize()
    {
        $texte = 'ola,oli';
        $this->assertEquals('s:7:"ola,oli";', Text::serialize($texte));
    }

    public function testSerializeJson()
    {
        $texte = 'ola,oli';
        $this->assertEquals('"ola,oli"', Text::serialize($texte, 'json'));
    }
}
