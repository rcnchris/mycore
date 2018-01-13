<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Twig\FormExtension;
use Tests\Rcnchris\BaseTestCase;

class FormExtensionTest extends BaseTestCase {

    /**
     * @var FormExtension
     */
    private $ext;

    public function setUp()
    {
        $this->ext = new FormExtension();
    }

    public function testInstance()
    {
        $this->ekoTitre('Twig - Form');
        $this->assertInstanceOf(FormExtension::class, $this->ext);
        $this->assertEmpty($this->ext->getFilters());
        $this->assertNotEmpty($this->ext->getFunctions());
    }

    /**
     * Obtenir un input
     */
    public function testField()
    {
        $html = $this->ext->field([], 'name', 'demo', 'Titre');
        $this->assertSimilar("
            <div class=\"form-group\">
                <label for=\"name\">Titre</label>
                <input class=\"form-control\" name=\"name\" id=\"name\" type=\"text\" value=\"demo\"/>
            </div>
            ", $html
        );
    }

    /**
     * Obtenir un input avec une erreur de validation
     */
    public function testFieldWithErrorContext()
    {
        $html = $this->ext->field(['errors' => ['name' => 'Erreur']], 'name', 'demo', 'Titre');
        $this->assertSimilar(
            '<div class="form-group has-danger">
                <label for="name">Titre</label>
                <input class="form-control is-invalid" name="name" id="name" type="text" value="demo"/>
                <small class="form-text text-muted">Erreur</small>
            </div>',
            $html
        );
    }

    /**
     * Obtenir un input
     */
    public function testFieldWithDate()
    {
        $date = new \DateTime();
        $html = $this->ext->field([], 'name', $date, 'Titre');
        $this->assertSimilar(
            '<div class="form-group">
                <label for="name">Titre</label>
                <input class="form-control" name="name" id="name" type="text" value="' . $date->format('Y-m-d H:i:s') . '"/>
            </div>',
            $html
        );
    }

    /**
     * Obtenir une zone de texte
     */
    public function testFieldTextarea()
    {
        $html = $this->ext->field([], 'name', 'demo', 'Commentaires', ['type' => 'textarea']);
        $this->assertSimilar(
            '<div class="form-group">
                <label for="name">Commentaires</label>
                <textarea class="form-control" name="name" id="name" type="textarea">demo</textarea>
            </div>',
            $html
        );
    }

    /**
     * Obtenir un input file
     */
    public function testFieldFile()
    {
        $html = $this->ext->field([], 'file', 'demo', 'Fichier', ['type' => 'file']);
        $this->assertSimilar(
            '<div class="form-group">
                <label for="name">Fichier</label><input class="form-control" name="file" id="file" type="file"/>
            </div>',
            $html
        );
    }

    /**
     * Obtenir une case à cocher
     */
    public function testFieldCheckbox()
    {
        $html = $this->ext->field([], 'check', 'demo', 'Vrai ?', ['type' => 'checkbox']);
        $this->assertSimilar(
            '<div class="form-group">
                <label for="name">Vrai ?</label>
                <input type="hidden" name="check" value="0"/>
                <input class="form-control" name="check" id="check" type="checkbox" checked value="1"/>
            </div>',
            $html
        );
    }

    /**
     * Obtenir une liste déroulante
     */
    public function testFieldSelect()
    {
        $html = $this->ext->field([], 'liste', 'demo', 'Liste', ['options' => ['ola', 'ole', 'oli']]);
        $this->assertSimilar(
            '<div class="form-group">
                <label for="name">Liste</label>
                <select class="form-control" name="liste" id="liste" type="text">
                    <option value="0" selected>ola</option>
                    <option value="1">ole</option>
                    <option value="2">oli</option>
                </select>
            </div>',
            $html
        );
    }
}
