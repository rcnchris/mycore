<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Text;
use Tests\Rcnchris\BaseTestCase;

class TextTest extends BaseTestCase
{

    /**
     * @var Text
     */
    private $text;

    public function setUp()
    {
        $this->text = new Text('Ola les gens');
    }

    public function testInstance()
    {
        $this->ekoTitre('Tools - Text');
        $this->assertInstanceOf(Text::class, $this->text);
        $this->assertInstanceOf(Text::class, $this->text->getInstance());
    }

    public function testSlug()
    {
        $this->assertEquals('le-slug-qui-va-bien', Text::slug('Le slug qui va bien !'));
        $this->assertEquals('le#slug#qui#va#bien', Text::slug('Le slug qui va bien !', '#'));
        $this->assertEquals('le-slug-qui-va-bien-!', Text::slug('Le slug qui va bien !', ['preserve' => '!']));
    }

    public function testComplement()
    {
        $this->assertEquals(
            '05',
            Text::compl(5, '0')
        );
    }

    public function testUniqId()
    {
        $this->assertEquals(23, strlen(Text::uuid()));
        $this->assertEquals(26, strlen(Text::uuid('ola')));
    }

    public function testSerialize()
    {
        $this->assertEquals(
            's:22:"Le texte qui va bien !";',
            Text::serialize('Le texte qui va bien !')
        );
    }

    public function testUnserialize()
    {
        $text = 's:22:"Le texte qui va bien !";';
        $this->assertEquals('Le texte qui va bien !', Text::unserialize($text));
    }

    public function testTruncateSimpleText()
    {
        $text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';
        $this->assertEquals(
            'Lorem i...',
            Text::truncate($text, 10)
        );
    }

    public function testTruncateSimpleTextWithEllipsis()
    {
        $text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';
        $this->assertEquals(
            'Lorem i###',
            Text::truncate($text, 10, ['ellipsis' => '###'])
        );
    }

    public function testTruncateWithTagHtml()
    {
        $text = '<div>Lorem ipsum dolor </div>sit amet, consectetur adipisicing elit. Atque eum ex facilis iure, sequi tempora vel veniam. Accusamus consequuntur dignissimos enim hic itaque labore nam natus nesciunt rerum similique. Tenetur.';
        $this->assertEquals(
            '<div>Lorem ipsum do…</div>',
            Text::truncate($text, 15, ['html' => true])
        );

        $this->assertEquals(
            '<div>Lorem i...',
            Text::truncate($text, 15, ['exact' => true])
        );

        $this->assertEquals(
            '<div>Lorem...',
            Text::truncate($text, 15, ['exact' => false])
        );

        $this->assertEquals(
            $text,
            Text::truncate($text, 1000, ['exact' => false])
        );
    }

    public function testTruncateWithMultipleTagsHtml()
    {
        $text = '<div>Lorem <p>ipsum</p> dolor </div>sit amet, consectetur adipisicing elit. Atque eum ex facilis iure, sequi tempora vel veniam. Accusamus consequuntur dignissimos enim hic itaque labore nam natus nesciunt rerum similique. Tenetur.';
        $this->assertEquals(
            '<div>Lorem <p>ipsum</p> do…</div>',
            Text::truncate($text, 15, ['html' => true])
        );

        $text = '<div>Lorem <p>ipsum</p> dolor </div>sit amet, consectetur adipisicing elit. Atque eum ex facilis iure, sequi tempora vel veniam. Accusamus consequuntur dignissimos enim hic itaque labore nam natus nesciunt rerum similique. Tenetur.';
        $this->assertEquals(
            $text,
            Text::truncate($text, 1000, ['html' => true])
        );
    }

    public function testTruncateWithoutEffect()
    {
        $text = 'Lorem';
        $this->assertEquals(
            $text,
            Text::truncate($text)
        );
        $this->assertEquals(
            $text,
            Text::truncate($text, ['exact' => true])
        );

        $this->assertEquals(
            $text,
            Text::truncate($text, ['exact' => false])
        );
    }

    public function testTruncateWithNegativeLength()
    {

        $text = 'ola les gens';
        $this->assertEquals($text, Text::truncate($text));
        $this->assertEquals('ola ...', Text::truncate($text, -5));
    }

    public function testTail()
    {
        $text = '<h1>Lorem ipsum</h1><p>dolor sit amet, consectetur adipisicing elit. Atque eum ex facilis iure, sequi tempora vel veniam. Accusamus consequuntur dignissimos enim hic itaque labore nam natus nesciunt rerum similique. Tenetur.</p>';
        $this->assertEquals(
            '...ur.</p>',
            Text::tail($text, 10)
        );
        $this->assertEquals(
            '...Tenetur.</p>',
            Text::tail($text, 20, ['exact' => false])
        );

        $text = 'Lorem';
        $this->assertEquals(
            'Lorem',
            Text::tail($text, 10)
        );
    }

    public function testIsMultibyte()
    {
        $this->assertEquals(false, Text::isMultibyte('ola les gens'));
        $this->assertEquals(true, Text::isMultibyte('ù'));
    }

    /**
     * Formater un nombre.
     */
    public function testFormatNumber()
    {
        $this->assertEquals('123 456', Text::formatNumber(123456));
        $this->assertEquals('123 456', Text::formatNumber('123456'));
    }

    /**
     * Obtenir le texte à gauche d'un caractère.
     */
    public function testBeforeString()
    {
        $this->assertEquals(
            "label",
            Text::getBefore('=', 'label=ola')
            , $this->getMessage('La partie avant le séparateur est incorrecte')
        );
        $this->assertEquals(
            null,
            Text::getBefore('x', 'label=ola')
            , $this->getMessage('Avec un séparateur absent de la chaîne')
        );
    }

    /**
     * Obtenir le texte à droite d'un caractère.
     */
    public function testAfterString()
    {
        $this->assertEquals(
            "ola",
            Text::getAfter('=', 'label=ola')
            , $this->getMessage('La partie après le séparateur est incorrecte')
        );
        $this->assertEquals(
            null,
            Text::getAfter('x', 'label=ola')
            , $this->getMessage('Avec un séparateur absent de la chaîne')
        );
    }

    public function testRemoveLastWord()
    {
        $phrase = 'ola les gens';
        $this->assertEquals('ola les', Text::removeLastWord($phrase), $this->getMessage('Le reste de la phrase, sans le dernier mot, est incorrecte'));
        $this->assertEquals('', Text::removeLastWord('olalesgens'), $this->getMessage('Un seul mot dans la chaîne'));
    }

    public function testSubstr()
    {
        $phrase = 'ola les gens';

        $this->assertEquals(
            'gens'
            , Text::substr($phrase, -4, 4)
            , $this->getMessage('Démarrer avec une valeur négative')
        );

        $this->assertEquals(
            '',
            Text::substr($phrase, 0, 0)
            , $this->getMessage('Longueur à zéro')
        );

        $this->assertEquals(
            'ola'
            , Text::substr($phrase, -12, 3)
            , $this->getMessage('Démarrer avec une position négative et une longueur inférieure')
        );

        $this->assertEquals(
            ''
            , Text::substr($phrase, 15, 3)
            , $this->getMessage('Démarrer à une position supérieure à la longueur demandée')
        );
    }
}
