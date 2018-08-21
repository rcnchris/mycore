<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\Packages\AudioStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyAPIEntity;
use Rcnchris\Core\Apis\Synology\SynologyAPIPackage;

class SynologyAPIEntityTest extends SynologyBaseTestCase
{
    public function makeSynologyEntity($content = null, $package = null)
    {
        if (is_null($package)) {
            $package = new AudioStationPackage($this->makeSynoAPI());
        }
        if (is_null($content)) {
            $content = $package->searchSongs('u-turn')->get('songs')->first()->toArray();
        }
        return new SynologyAPIEntity($package, $content);
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Entities');
        $this->assertInstanceOf(SynologyAPIEntity::class, $this->makeSynologyEntity());
    }

    public function testGetPackage()
    {
        $this->assertInstanceOf(SynologyAPIPackage::class, $this->makeSynologyEntity()->getPackage());
    }

    public function testGetFields()
    {
        $this->assertNotEmpty($this->makeSynologyEntity()->getFields());
    }
}