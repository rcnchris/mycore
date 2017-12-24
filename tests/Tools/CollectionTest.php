<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Collection;
use Tests\Rcnchris\BaseTestCase;

class CollectionTest extends BaseTestCase {

    /**
     * Liste simple de valeurs
     *
     * @var Collection
     */
    private $list;

    public function setUp()
    {
        // Liste de valeurs
        $this->list = $this->makeCollection('ola,ole,oli', "Liste de valeurs dans une chaîne avec séparateur");
    }

    /**
     * Obtenir une instance de Collection
     *
     * @param mixed|null  $values
     * @param string|null $name
     *
     * @return Collection
     */
    public function makeCollection($values = null, $name = null)
    {
        return new Collection($values, $name);
    }

    public function testInstance()
    {
        $this->ekoTitre('Tools - Collection');
        $this->assertInstanceOf(Collection::class, $this->list);
    }
}
