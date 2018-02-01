<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Composer;
use Tests\Rcnchris\BaseTestCase;

class ComposerTest extends BaseTestCase {

    /**
     * @var Composer
     */
    private $composer;

    public function setUp()
    {
        $this->composer = $this->makeComposer();
    }

    /**
     * Obtenir l'instance à partir d'un nom de fichier
     *
     * @param string|null $fileName Nom du fichier
     *
     * @return \Rcnchris\Core\Tools\Composer
     */
    public function makeComposer($fileName = null)
    {
        if (is_null($fileName)) {
            $fileName = $this->rootPath() . DIRECTORY_SEPARATOR . 'composer.json';
        }
        return new Composer($fileName);
    }

    public function testInstance()
    {
        $this->ekoTitre('Tools - Composer');
        $this->assertInstanceOf(
            Composer::class
            , $this->composer
            , $this->getMessage("L'instance attendue est incorreecte")
        );
    }

    public function testInstanceWithWrongFile()
    {
        $this->expectException(\Exception::class);
        $this->makeComposer('path/to/fake/file/composer.json');
    }

    public function testGetKeys()
    {
        $this->assertNotEmpty(
            $this->composer->keys()
            , $this->getMessage("La liste des clés est vide")
        );
    }

    public function testCountKeys()
    {
        $this->assertEquals(
            count($this->composer->keys())
            , $this->composer->count()
            , $this->getMessage("Le compte de clés est incorrect")
        );
    }

    public function testHasKey()
    {
        $this->assertTrue(
            $this->composer->has('description')
            , $this->getMessage("La clé description est censée être présente")
        );

        $this->assertFalse(
            $this->composer->has('fake')
            , $this->getMessage("La clé fake est censée être absente")
        );
    }

    public function testGetKey()
    {
        $expect = 'library';
        $this->assertEquals(
            $expect
            , $this->composer->get('type')
            , $this->getMessage("La méthode get ne retourne pas la bonne valeur")
        );

        $this->assertFalse(
            $this->composer->get('fake')
            , $this->getMessage("La méthode get ne retourne pas la bonne valeur en cas de propriété non trouvée")
        );

        $this->assertEquals(
            $expect
            , $this->composer->type
            , $this->getMessage("La méthode __get ne retourne pas la bonne valeur")
        );

        $this->assertEquals(
            $expect
            , $this->composer['type']
            , $this->getMessage("La méthode ArrayAccess ne retourne pas la bonne valeur")
        );
    }

    public function testArrayAccess()
    {
        $this->assertArrayAccess(
            $this->composer
            , 'type'
            , 'library'
            , ['offsetExists', 'offsetGet']
        );
    }

    public function testArrayAccessSet()
    {
        $this->expectException(\Exception::class);
        $this->composer['require'] = 'fake';
    }

    public function testArrayAccessUnset()
    {
        $this->expectException(\Exception::class);
        unset($this->composer['require']);
    }

    public function testToArray()
    {
        $this->assertNotEmpty(
            $this->composer->toArray()
            , $this->getMessage("Le contenu est censé ne pas être vide")
        );
        $this->assertNotEmpty(
            $this->composer->toArray('require')
            , $this->getMessage("Le contenu est censé ne pas être vide")
        );
    }

    public function testGetSizeOf()
    {
        $this->assertInternalType(
            'string'
            , $this->composer->getSizeOf('intervention/image')
            , $this->getMessage("Le type attendu de la taille d'une librairie est incorrect")
        );

        $this->assertFalse(
            $this->composer->getSizeOf('fake/tofake')
            , $this->getMessage("Le retour de la méthode getSizeOf dans le cas d'une librairie inexistante est incorrect")
        );
    }

    public function testGetComposerOf()
    {
        $this->assertInstanceOf(
            Composer::class
            , $this->composer->getComposerOf('intervention/image')
            , $this->getMessage("L'instance attendue est incorrecte")
        );

        $this->assertFalse(
            $this->composer->getComposerOf('fake/tofake')
            , $this->getMessage("Le retour de la méthode getComposerOf est incorrect dans le cas où la librairie est inexistante")
        );
    }

    public function testToString()
    {
        $this->assertInternalType(
            'string'
            , (string)$this->composer
            , $this->getMessage("Le type retournée par __toString est incorrect")
        );
    }

    public function testGetRequires()
    {
        $this->assertArrayHasKey(
            'req'
            , $this->composer->getRequires()
            , $this->getMessage("La clé 'req' doit être dans le retour de getRequires sans paramètre")
        );
        $this->assertArrayHasKey(
            'dev'
            , $this->composer->getRequires()
            , $this->getMessage("La clé 'dev' doit être dans le retour de getRequires sans paramètre")
        );
        $this->assertArrayHasKey(
            'php'
            , $this->composer->getRequires('req')
            , $this->getMessage("La clé 'php' doit être dans le retour de getRequires avec le paramètre 'req'")
        );
        $this->assertArrayHasKey(
            'phpunit/phpunit'
            , $this->composer->getRequires('dev')
            , $this->getMessage("La clé 'phpunit/phpunit' doit être dans le retour de getRequires avec le paramètre 'dev'")
        );
        $this->assertFalse(
            $this->composer->getRequires('fake')
            , $this->getMessage("La clé 'fake' ne doit pas être dans le retour de getRequires avec le paramètre 'fake'")
        );
    }

    public function testCallMissingMethod()
    {
        $this->assertNotEmpty(
            $this->composer->require()
            , $this->getMessage("Le retour de '__call' sans paramètre est incorrect")
        );
        $this->assertEquals(
            '>=7.0'
            , $this->composer->require('php')
            , $this->getMessage("Le retour de '__call' avec paramètre valide est incorrect")
        );
        $this->assertEquals(
            ['php' => '>=7.0', 'intervention/image' => '^2.4']
            , $this->composer->require(['php', 'intervention/image'])
            , $this->getMessage("Le retour de '__call' avec plusieurs paramètres est incorrect")
        );
    }

    public function testCallWrongMethod()
    {
        $this->assertFalse(
            $this->composer->fake()
            , $this->getMessage("Le retour de '__call' lors d'une propriété inexistante est incorrect")
        );
        $this->assertFalse(
            $this->composer->fake('fake')
            , $this->getMessage("Le retour de '__call' lors d'une propriété inexistante et un paramètre inexistant est incorrect")
        );
    }
}
