<?php
/**
 * Fichier CommonTest.php du 29/10/2017
 * Description : Fichier de la classe CommonTest
 *
 * PHP version 5
 *
 * @category New
 *
 * @package  Tests\Core
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Common;
use Tests\Rcnchris\BaseTestCase;

class CommonTest extends BaseTestCase
{

    public function testObjectToArray()
    {
        $this->ekoTitre('Tools - Common');
        $o = new \stdClass();
        $o->name = 'Mathis';
        $o->birthday = date('d-m-Y');
        $a = Common::toArray($o);
        $this->assertEquals(['name' => 'Mathis', 'birthday' => date('d-m-Y')], $a);
    }

    public function testArrayToArray()
    {
        $a = ['name' => 'Mathis', 'birthday' => date('d-m-Y')];
        $a2 = Common::toArray($a);
        $this->assertEquals($a, $a2);
    }

    public function testGetMemory()
    {
        $m = Common::getMemoryUse();
        $this->assertInternalType('string', $m);

        $m = Common::getMemoryUse(true, true);
        $this->assertInternalType('int', $m);
    }
}
