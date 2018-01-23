<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Folder;
use Rcnchris\Core\Tools\Myvar;
use Slim\Collection;
use Tests\Rcnchris\BaseTestCase;
use Tests\Rcnchris\Core\ORM\OrmTestCase;

class MyvarTest extends BaseTestCase
{

    /**
     * Variables de test
     *
     * @var array
     */
    public $vars = [];

    /**
     * @var array
     */
    public $item;

    /**
     * @param $var
     *
     * @return \Rcnchris\Core\Tools\Myvar
     */
    private function makeVar($var)
    {
        return new Myvar($var);
    }

    public function setUp()
    {
        $this->vars = [
            'string' => 'ola les gens'
            , 'integer' => 12
            , 'double' => 12.56
            , 'array' => [
                ['name' => 'Mathis', 'year' => 2007, 'genre' => 'male']
                , ['name' => 'Raphaël', 'year' => 2007, 'genre' => 'male']
                , ['name' => 'Clara', 'year' => 2009, 'genre' => 'female']
            ]
            , 'object' => new Collection([
                ['name' => 'Mathis', 'year' => 2007, 'genre' => 'male']
                , ['name' => 'Raphaël', 'year' => 2007, 'genre' => 'male']
                , ['name' => 'Clara', 'year' => 2009, 'genre' => 'female']
            ])
            , 'resource' => curl_init('http://fake.com')
        ];
        $this->item = ['name' => 'Mathis', 'year' => 2007, 'genre' => 'male'];
    }

    public function testInstance()
    {
        $this->ekoTitre('Tools - MyVar');
        $this->assertInstanceOf(Myvar::class, $this->makeVar('ola'));
    }

    public function testGetType()
    {
        foreach ($this->vars as $type => $value) {
            $this->assertEquals(
                $type
                , $this->makeVar($value)->getType()
            );
        }
    }

    public function testIsType()
    {
        foreach ($this->vars as $type => $value) {
            $methodName = 'is' . ucfirst($type);
            $this->assertTrue(
                $this->makeVar($value)->$methodName()
                , $this->getMessage("Le type vérifié ne répond pas correctement ($methodName)")
            );
        }
    }

    public function testLength()
    {
        $this->assertEquals(
            12
            , $this->makeVar($this->vars['string'])->length()
            , $this->getMessage("La longueur de la chaîne de caractères est incorrecte")
        );

        $this->assertEquals(
            3
            , $this->makeVar($this->vars['array'])->length()
            , $this->getMessage("La longueur du tableau est incorrecte")
        );

        $this->assertEquals(
            2
            , $this->makeVar($this->vars['integer'])->length()
            , $this->getMessage("La longueur de l'entier est incorrecte")
        );

        $this->assertEquals(
            5
            , $this->makeVar($this->vars['double'])->length()
            , $this->getMessage("La longueur du double est incorrecte")
        );

        $this->assertEquals(
            0
            , $this->makeVar($this->vars['resource'])->length()
            , $this->getMessage("La longueur de la ressource est incorrecte")
        );
    }

    public function testGetResourceType()
    {
        $this->assertEquals(
            'curl'
            , $this->makeVar($this->vars['resource'])->getResourceType()
            , $this->getMessage("Une resource curl n'est pas considérée comme telle")
        );
        $this->assertFalse(
            $this->makeVar($this->vars['array'])->getResourceType()
            , $this->getMessage('Un tableau retourne un type de ressource')
        );
    }

    public function testIsNum()
    {
        $this->assertTrue(
            $this->makeVar($this->vars['double'])->isNum()
            , $this->getMessage("Une valeur double n'est pas considérée comme numérique")
        );

        $this->assertFalse(
            $this->makeVar($this->vars['string'])->isNum()
            , $this->getMessage("Une chaîne de caractères sans chiffre est considérée comme numérique")
        );
    }

    public function testToString()
    {
        $this->assertEquals(
            $this->vars['string']
            , (string)$this->makeVar($this->vars['string'])
            , $this->getMessage("Une chaîne de caractères n'est pas égale à elle même")
        );

        $this->assertEquals(
            json_encode($this->vars['array'])
            , (string)$this->makeVar($this->vars['array'])
            , $this->getMessage("L'export du tableau au format string est incorrect")
        );

        $this->assertEquals(
            json_encode(['ola', 'ole', 'oli'])
            , (string)$this->makeVar(new \Rcnchris\Core\Tools\Collection(['ola', 'ole', 'oli']))
            , $this->getMessage("L'export de l'objet au format string est incorrect")
        );

        $this->assertEquals(
            json_encode(12)
            , (string)$this->makeVar($this->vars['integer'])
            , $this->getMessage("L'export de l'entier au format string est incorrect")
        );
    }

    public function testGet()
    {
        foreach ($this->vars as $type => $value) {
            $this->assertEquals(
                $this->vars[$type]
                , $this->makeVar($value)->get()
                , $this->getMessage("La valeur obtenue est différente de la valeur initiale")
            );
        }

        $this->assertEquals(
            'Mathis'
            , $this->makeVar(['name' => 'Mathis', 'year' => 2007, 'genre' => 'male'])->get('name')
            , $this->getMessage("La valeur de la clé demandée est incorrecte")
        );

        $o = new \stdClass();
        $o->name = 'Mathis';
        $o->year = 2007;
        $o->genre = 'male';
        $this->assertEquals(
            'Mathis'
            , $this->makeVar($o)->get('name')
            , $this->getMessage("La valeur de la clé demandée à l'objet est incorrecte")
        );

        $o = new \Rcnchris\Core\Tools\Collection($o);
        $this->assertEquals(
            'Mathis'
            , $this->makeVar($o)->get('name')
            , $this->getMessage("La valeur de la clé demandée à l'objet est incorrecte")
        );

        $this->assertEquals(
            $o->toArray()
            , $this->makeVar($o)->get('toArray')
            , $this->getMessage("L'appel d'une méthode de l'objet est incorrect")
        );

        $this->assertFalse(
            $this->makeVar($o)->get('fake')
            , $this->getMessage("L'appel d'une clé, méthode ou propriété sur l'objet est incorrect")
        );
    }

    public function testGetMethods()
    {
        $this->assertNotEmpty(
            $this->makeVar(new Collection(['ola', 'ole', 'oli']))->getMethods()
            , $this->getMessage("La liste des méthodes de l'objet est incorrecte")
        );

        $this->assertFalse(
            $this->makeVar($this->vars['string'])->getMethods()
            , $this->getMessage("La liste des méthodes de l'objet est incorrecte")
        );

        $this->assertNotEmpty(
            $this->makeVar($this)->getMethods(true)
            , $this->getMessage("La liste des méthodes de l'objet est incorrecte")
        );

    }

    public function testGetParent()
    {
        $this->assertEquals(
            BaseTestCase::class
            , $this->makeVar($this)->getParent()
            , $this->getMessage("La classe parente de l'objet ne devrait pas être vide")
        );

        $this->assertFalse(
            $this->makeVar($this->vars['string'])->getParent()
            , $this->getMessage("La classe parente d'une chaîne de caractères ne devrait pas exister")
        );
    }

    public function testGetImplements()
    {
        $this->assertNotEmpty(
            $this->makeVar(new Collection(['ola', 'ole', 'oli']))->getImplements()
            , $this->getMessage("La liste des implémentations de l'objet ne devrait pas être vide")
        );

        $this->assertFalse(
            $this->makeVar($this->vars['string'])->getImplements()
            , $this->getMessage("La liste des implémentations de l'objet devrait être vide")
        );
    }

    public function testGetTraits()
    {
        $this->assertEmpty(
            $this->makeVar($this)->getTraits()
            , $this->getMessage("Cet objet ne devrait pas avoir de traits")
        );

        $this->assertFalse(
            $this->makeVar('ola')->getTraits()
            , $this->getMessage("Une chaîne de caractères ne peut pas avoir de trait")
        );
    }

    public function testGetProperties()
    {
        $this->assertFalse(
            $this->makeVar($this->vars['string'])->getProperties()
            , $this->getMessage("Une chaîne de caractères ne peut pas retourner des propriétés")
        );

        $this->assertEquals(
            ['path' => null]
            , $this->makeVar(new Folder(__DIR__))->getProperties()
            , $this->getMessage("Les propriétés retournées par un objet sont incorrectes")
        );

        $this->assertNotEmpty(
            $this->makeVar($this)->getProperties()
            , $this->getMessage("La liste des propriétés de l'objet est incorrecte")
        );

        $this->assertEquals(
            ['vars' => [], 'item' => null]
            , $this->makeVar($this)->getProperties()
            , $this->getMessage("La liste des propriétés de l'objet est incorrecte")
        );

        $this->assertEquals(
            ['name', 'year', 'genre']
            , $this->makeVar($this->item)->getProperties()
            , $this->getMessage("La liste des propriétés du tableau ne correspond pas au noms de clés du tableau")
        );
    }
}
