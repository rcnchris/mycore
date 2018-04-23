<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\SourcesManager;
use Tests\Rcnchris\BaseTestCase;

class SourcesManagerTest extends BaseTestCase {

    public function makeSourceManager(array $sources = [])
    {
        if (empty($sources)) {
            $config = require $this->rootPath() . '/tests/config.php';
            $sources = $config['datasources'];
        }
        return new SourcesManager($sources);
    }

    public function testInstance()
    {
        $this->ekoTitre('ORM - Sources Manager');
        $this->assertInstanceOf(SourcesManager::class, $this->makeSourceManager());
    }

    public function testGetSources()
    {
        $m = $this->makeSourceManager();
        $this->assertArrayHasKey('test', $m->getSources());
        $this->assertArrayHasKey('host', $m->getSources('test'));
        $this->assertFalse($m->getSources('fake'));
    }
}
