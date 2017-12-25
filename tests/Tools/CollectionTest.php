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

    /**
     * @var Collection
     */
    private $notes;

    /**
     * @var Collection
     */
    private $user;

    /**
     * @var Collection
     */
    private $users;

    /**
     * @var Collection
     */
    private $cdn;

    /**
     * Construction des différentes collections
     */
    public function setUp()
    {
        // Liste de valeurs
        $this->list = $this->makeCollection('ola,ole,oli', "Liste de valeurs dans une chaîne avec séparateur");

        // Liste de valeurs numériques
        $this->notes = $this->makeCollection([12, 7, 14], "Liste de valeurs numériques");

        // entity
        $this->user = $this->makeCollection([
            'id' => rand(1, 99)
            , 'username' => 'rcn'
            , 'email' => 'rcn.chris@gmail.com'
            , 'name' => [
                'firstname' => 'Raoul'
                , 'lastname' => 'CHRISMANN'
            ]
            , 'phone' => '0498976447'
            , 'mobile' => '0612131415'
            , 'created' => new \DateTime()
            , 'modified' => new \DateTime()
        ], "Entité");

        // items
        $this->users = $this->makeCollection([
            [
                [
                    'id' => rand(1, 99)
                    , 'username' => 'rcn'
                    , 'email' => 'rcn.chris@gmail.com'
                    , 'name' => [
                    'firstname' => 'Raoul'
                    , 'lastname' => 'CHRISMANN'
                ]
                    , 'phone' => '0498976447'
                    , 'mobile' => '0612131415'
                    , 'created' => new \DateTime()
                    , 'modified' => new \DateTime()
                ]
            ]
            , [
                [
                    'id' => rand(1, 99)
                    , 'username' => 'sra'
                    , 'email' => 'sandrine@gmail.com'
                    , 'name' => [
                    'firstname' => 'Sandrine'
                    , 'lastname' => 'ROSSA'
                ]
                    , 'phone' => '0478251467'
                    , 'mobile' => '0646971585'
                    , 'created' => new \DateTime()
                    , 'modified' => new \DateTime()
                ]
            ]
        ], "Liste d'enregistrements de même nature");

        // cdn
        $this->cdn = $this->makeCollection([
            'jquery' => [
                'name' => 'jQuery',
                'path' => '/components/jquery',
                'require' => true,
                'package' => '/package.json',
                'composer' => '/composer.json',
                'readme' => '/README.md',
                'link' => 'https://jquery.com',
                'core' => [
                    'js' => [
                        'min' => '/jquery.min.js',
                        'src' => '/jquery.js',
                    ],
                ],
            ],

            'foundation' => [
                'name' => 'Foundation',
                'path' => '/zurb/foundation',
                'require' => true,
                'favicon' => '/docs/assets/img/logos/foundation-sites-nuget-icon-128x128.jpg',
                'package' => '/package.json',
                'composer' => '/composer.json',
                'readme' => '/README.md',
                'link' => 'http://foundation.zurb.com',
                'exemples' => [],
                'core' => [
                    'css' => [
                        'min' => '/dist/css/foundation.min.css',
                        'src' => '/dist/css/foundation.css'
                    ],
                    'js' => [
                        'min' => '/dist/js/foundation.min.js',
                        'src' => '/dist/js/foundation.js',
                    ],
                ],
            ],
        ]);

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

    /**
     * Créer une collection sans paramètre
     */
    public function testInstanceWithoutParam()
    {
        $this->ekoTitre('Core - Collection');
        $c = $this->makeCollection();
        $this->assertInstanceOf(Collection::class, $c);
        $this->assertEquals(0, $c->count());
        $this->assertEmpty($c->toArray());
    }

    /**
     * Créer une collection avec une chaîne de caractères avec séparateur
     */
    public function testInstanceWithStringList()
    {
        $c = $this->makeCollection('ola,ole,oli', "Liste de valeurs dans une chaîne avec séparateur");
        $this->assertInstanceOf(Collection::class, $c);
        $this->assertCount(3, $c->toArray());
        $this->assertEquals(['ola', 'ole', 'oli'], $c->toArray());
        $this->assertEquals("Liste de valeurs dans une chaîne avec séparateur", $c->name());
    }

    /**
     * Créer une collection avec une chaîne au format json
     */
    public function testInstanceWithJsonString()
    {
        $json = file_get_contents($this->rootPath() . '/composer.json');
        $c = $this->makeCollection($json);
        $this->assertArrayHasKey('require', $c->toArray());
    }

    /**
     * Créer une collection avec un tableau
     */
    public function testInstanceWithArray()
    {
        $c = $this->makeCollection(['Mathis', 'Raphaël', 'Clara']);
        $this->assertInstanceOf(Collection::class, $c);
        $this->assertEquals(3, $c->count());
        $this->assertNotEmpty($c->toArray());
        $this->assertContains('Clara', $c->toArray());
    }

    /**
     * Créer une collection avec une instance de collection
     */
    public function testInstanceWithCollection()
    {
        $c = $this->makeCollection($this->list);

        $this->assertInstanceOf(Collection::class, $c);
        $this->assertEquals(3, $c->count());
        $this->assertNotEmpty($c->toArray());
        $this->assertContains('ole', $c->toArray());
    }

    /**
     * Créer une collection avec un objet
     */
    public function testInstanceWithObject()
    {
        $o = new \stdClass();
        $o->name = 'rcn';
        $o->birthday = date('d-m-Y');
        $c = $this->makeCollection($o);

        $this->assertInstanceOf(Collection::class, $c);
        $this->assertEquals(2, $c->count());
        $this->assertNotEmpty($c->toArray());
        $this->assertContains('rcn', $c->toArray());
    }

    /**
     * Créer une collection avec une chaîne sans séparateur
     */
    public function testInstanceWithStringWithoutSeparator()
    {
        $c = $this->makeCollection('ola les gens');
        $this->assertInstanceOf(Collection::class, $c);
        $this->assertEquals(1, $c->count());
    }

    /**
     * Créer une collection avec une liste de tabelau
     */
    public function testInstanceWithArrayList()
    {
        $c = $this->makeCollection([
            ['id' => 12, 'username' => 'rcn', 'sport' => 'foot']
            , ['id' => 14, 'username' => 'mcn', 'sport' => 'boxe']
        ]);
        $this->assertEquals(2, $c->count());
        $this->assertEquals('items', $c->type());

        $c = $this->makeCollection([
            ['id' => 12, 'username' => 'rcn', 'sport' => 'foot']
            , ['id' => 14, 'username' => 'sra', 'taf' => 'camoin']
        ]);
        $this->assertCount(2, $c);
        $this->assertEquals('list', $c->type());
    }

    /**
     * Créer une collection avec un tableau associatif
     */
    public function testInstanceWithAssoTab()
    {
        $c = $this->makeCollection([
            'rcn' => ['id' => 12, 'username' => 'rcn', 'sport' => 'foot']
            , 'mcn' => ['id' => 14, 'username' => 'mcn', 'sport' => 'boxe']
        ], "testInstanceWithAssoTab");
        $this->assertCount(2, $c);
        $this->assertEquals('items', $c->type());
        $this->assertEquals('boxe', $c->get('mcn.sport'));
    }

    /**
     * Définir le nom de la collection
     */
    public function testSetName()
    {
        $this->list->name('fake');
        $this->assertEquals('fake', $this->list->name());
        $this->list->name("Liste de valeurs dans une chaîne avec séparateur");
    }

    /**
     * Obtenir le contenu d'une clé avec la syntaxe tableau $c['key']
     */
    public function testArrayAccessInterface()
    {
        // Get
        $this->assertEquals(
            'ola',
            $this->list[0]
        );

        $c = $this->makeCollection(['id' => 12, 'username' => 'rcn']);
        $this->assertEquals(
            'rcn',
            $c['username']
        );

        // Exist
        $this->assertTrue(isset($c['username']));

        // Set
        $c['password'] = 'secret';
        $this->assertEquals('secret', $c['password']);

        // Unset
        unset($c['password']);
        $this->assertArrayNotHasKey('password', $c->toArray());
    }

    /**
     * Itérer sur la collection
     */
    public function testIteratorAggregateInterface()
    {
        $ret = [];
        foreach ($this->user as $key => $value) {
            $ret[$key] = $value;
        }
        $this->assertEquals($ret, $this->user->toArray());
    }

    /**
     * Sérialiser et désérialiser
     */
    public function testSerializeInterface()
    {
        $this->assertEquals(
            'a:2:{i:0;s:3:"ola";i:1;s:3:"ole";}',
            $this->makeCollection('ola,ole')->serialize()
        );

        $c = $this->makeCollection('ola,ole');
        $this->assertEquals(null, $c->unserialize('a:2:{i:0;s:3:"ola";i:1;s:3:"ole";}"'));
    }

    /**
     * Obtenir le contenu d'une clé avec la syntaxe objet $c->key
     */
    public function testGetKeyLikeObject()
    {
        $c = $this->makeCollection(['id' => 12, 'username' => 'rcn']);
        $this->assertEquals('rcn', $c->username);
    }

    /**
     * Demander une clé qui n'existe pas
     */
    public function testGetNotExistKey()
    {
        $c = $this->makeCollection(['id' => 12, 'username' => 'rcn']);
        $this->assertNull($c->fake);
        $this->assertNull($c['fake']);
        $this->assertNull($c->get('fake'));
    }

    public function testGetWithComposedKey()
    {
        $c = $this->makeCollection([
            'id' => 12
            , 'name' => [
                'title' => 'mr'
                , 'first' => 'john'
                , 'last' => 'doe'
            ]
        ]);
        $this->assertEquals('john', $c->get('name.first'));
        $this->assertNull($c->get('name.fake'));
        $this->assertInstanceOf(Collection::class, $c->get('name'));
    }

    /**
     * Vérifier la présence d'une clé dans la collection
     */
    public function testHas()
    {
        $this->assertFalse($this->list->has('ola'));
        $this->assertTrue($this->user->has('email'));
        $this->assertFalse($this->users->has('username'));
        $this->assertTrue($this->user->has('name.firstname'));
        $this->assertFalse($this->user->has('name.fake'));
        $this->assertTrue($this->cdn->has('jquery.name'));
    }

    /**
     * Obtenir si elle existe la valeur d'une clé
     */
    public function testHasGet()
    {
        $c = $this->makeCollection([
            'id' => 12
            , 'name' => [
                'title' => 'mr'
                , 'first' => 'john'
                , 'last' => 'doe'
            ]
        ]);
        $this->assertEquals(
            [
                'title' => 'mr'
                , 'first' => 'john'
                , 'last' => 'doe'
            ],
            $c->hasGet('name')->toArray()
        );
        $this->assertFalse($c->hasGet('fake'));
    }

    /**
     * Obtenir le premier élément de la collection
     */
    public function testGetFirstItem()
    {
        $this->assertNull($this->makeCollection()->first());
        $this->assertEquals('ola', $this->list->first());
        $this->assertInstanceOf(Collection::class, $this->user->first());
        $this->assertInstanceOf(Collection::class, $this->users->first());
        $this->assertInstanceOf(Collection::class, $this->cdn->first());
    }

    /**
     * Obtenir le dernier élément de la collection
     */
    public function testGetLastItem()
    {
        $this->assertNull($this->makeCollection()->last());
        $this->assertEquals('oli', $this->list->last());
        $this->assertEquals('rcn', $this->makeCollection(['id' => 12, 'username' => 'rcn'])->last());
        $this->assertInstanceOf(Collection::class, $this->users->last());
    }

    public function testGetKeys()
    {
        $this->assertNull($this->makeCollection()->keys());
        $this->assertEquals([0, 1, 2], $this->list->keys()->toArray());
        $this->assertEquals(
            ['jquery', 'foundation'],
            $this->cdn->keys()->toArray()
        );

        $c = $this->makeCollection([
            ['id' => 5, 'username' => 'rcn']
            , ['id' => 7, 'username' => 'sra']
        ]);
        $this->assertEquals(['id', 'username'], $c->keys()->toArray());
    }

    /**
     * Vérifier la présence d'une valeur dans le tableau
     */
    public function testInArray()
    {
        $this->assertTrue($this->list->inArray('ola'));
        $this->assertFalse($this->list->inArray('fake'));
        $c = $this->makeCollection([
            ['id' => 5, 'username' => 'rcn']
            , ['id' => 7, 'username' => 'sra']
        ]);
        $this->assertTrue($c->inArray('rcn'));
    }

    /**
     * Définir une valeur dans une liste.
     */
    public function testSetItemInList()
    {
        $c = $this->makeCollection('ola,ole');
        $c->set(null, 'oli');
        $this->assertContains('oli', $c->toArray());
    }

    /**
     * Définir une clé et sa valeur dans une collection avec des clés nommées.
     */
    public function testSetItemInAsso()
    {
        $c = $this->makeCollection(
            [
                'Mathis' => 2007
                , 'Raphaël' => 2007
            ]
        );
        $c->set('Clara', 2009);
        $this->assertArrayHasKey('Clara', $c->toArray());
        $this->assertContains(2009, $c->toArray());
        $this->assertEquals(false, $c->set());
    }

    /**
     * Obtenir un contenu au format json
     */
    public function testToJson()
    {
        $this->assertEquals(
            json_encode(['ola', 'ole', 'oli']),
            $this->list->toJson()
        );
    }

    /**
     * Afficher la collection sous la forme d'une chaîne de caractères
     */
    public function testToString()
    {
        $tab = [
            'Mathis' => 2007
            , 'Raphaël' => 2007
        ];
        $c = $this->makeCollection($tab);
        $result = json_encode($tab);
        $this->assertEquals($result, (string)$c);
    }

    /**
     * Obtenir le type de la collection (list, entity, items)
     */
    public function testGetTypeCollection()
    {
        $this->assertEquals('list', $this->list->type());
        $this->assertEquals('entity', $this->user->type());
        $this->assertEquals('items', $this->users->type());
        $this->assertEquals('items', $this->cdn->type());
    }

    /**
     * Obtenir le type de la valeur d'une clé
     */
    public function testGetTypeOfValue()
    {
        $this->assertEquals('string', $this->user->typeOf('email'));
        $this->assertEquals('array', $this->user->typeOf('name'));
        $this->assertFalse($this->user->typeOf('fake'));
    }

    /**
     * Vérifier si la valeur d'une clé est un tableau
     */
    public function testIsArray()
    {
        $this->assertTrue($this->user->isArray('name'));
        $this->assertFalse($this->user->isArray('email'));
    }

    /**
     * Obtenir une liste de valeur avec séparateur
     */
    public function testJoin()
    {
        $this->assertEquals('ola, ole, oli', $this->list->join());
        $this->assertEquals('ola;ole;oli', $this->list->join(';'));
        $this->assertFalse($this->user->join());
        $this->assertFalse($this->users->join());
    }

    /**
     * Obtenir une extraction des données à partir d'un nom de clé
     */
    public function testExtract()
    {
        $this->assertEquals([], $this->list->extract('ola')->toArray());
        $this->assertEquals(['jQuery', 'Foundation'], $this->cdn->extract('name')->toArray());
        $this->assertEquals(
            [
                '/components/jquery' => 'jQuery'
                , '/zurb/foundation' => 'Foundation'
            ],
            $this->cdn->extract('name', 'path')->toArray()
        );
    }

    /**
     * Obtenir la plus petite valeur de la collection
     */
    public function testGetMin()
    {
        $this->assertEquals('ola', $this->list->min());
    }

    /**
     * Obtenir la plus grande valeur de la collection
     */
    public function testGetMax()
    {
        $this->assertEquals('oli', $this->list->max());
    }
}
