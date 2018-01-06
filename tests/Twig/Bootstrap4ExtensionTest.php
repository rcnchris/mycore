<?php
namespace Tests\Core\Twig;

use Rcnchris\Core\Tools\Collection;
use Rcnchris\Core\Twig\Bootstrap4Extension;
use Tests\Rcnchris\BaseTestCase;

class Bootstrap4ExtensionTest extends BaseTestCase
{

    /**
     * @var Bootstrap4Extension
     */
    private $ext;

    /**
     * Instancie l'extension Twig
     */
    public function setUp()
    {
        $this->ext = new Bootstrap4Extension();
    }

    /**
     * Obtenir l'instance
     */
    public function testInstance()
    {
        $this->ekoTitre('Twig - Bootstrap4');
        $this->assertInstanceOf(Bootstrap4Extension::class, $this->ext);
    }

    /**
     * Obtenir la liste des filtres de l'extension
     */
    public function testGetFilters()
    {
        $filters = $this->ext->getFilters();
        $this->assertNotEmpty($filters);
    }

    /**
     * Obtenir la liste des fonctions de l'extension
     */
    public function testGetFunctions()
    {
        $functions = $this->ext->getFunctions();
        $this->assertNotEmpty($functions);
    }

    /**
     * Obtenir une alerte
     */
    public function testGetAlert()
    {
        $this->assertEquals(
            '<div class="alert alert-info" role="alert">ola</div>',
            $this->ext->alert('ola')
        );

        $this->assertEquals(
            '<div class="alert alert-dark" role="alert">ola</div>',
            $this->ext->alert('ola', 'dark')
        );

        $this->assertSimilar(
            '<div class="alert alert-dark alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>ola
            </div>',
            $this->ext->alert('ola', 'dark', ['dismissible' => true])
        );

        $this->assertEquals(
            '<div class="alert alert-dark" role="alert"><i class="fa fa-info"></i> ola</div>',
            $this->ext->alert('ola', 'dark', ['icon' => '<i class="fa fa-info"></i>'])
        );

        $this->assertNull($this->ext->alert('ola', 'fake'));
    }

    /**
     * Obtenir un badge
     */
    public function testGetBadgeWithoutContext()
    {
        $this->assertEquals(
            '<span class="badge badge-secondary">ola</span>',
            $this->ext->badge('ola')
        );
    }

    public function testGetBadgeWithObject()
    {
        $o = new Collection('ola,ole,oli');
        $this->assertEquals(
            '<span class="badge badge-secondary">' . json_encode(['ola', 'ole', 'oli']) . '</span>',
            $this->ext->badge($o)
        );
    }

    public function testGetBadgeWithContext()
    {
        $this->assertEquals(
            '<span class="badge badge-dark">ola</span>',
            $this->ext->badge('ola', 'dark')
        );
    }

    public function testGetBadgePill()
    {
        $this->assertEquals(
            '<span class="badge badge-pill badge-dark">ola</span>',
            $this->ext->badge('ola', 'dark', ['pill' => true])
        );
    }

    public function testGetBadgeWithLink()
    {
        $this->assertEquals(
            '<a href="http://link.com" class="badge badge-dark">ola</a>',
            $this->ext->badge('ola', 'dark', ['link' => 'http://link.com'])
        );
    }

    public function testGetBadgeBool()
    {
        $this->assertEquals(
            '<span class="badge badge-success">TRUE</span>',
            $this->ext->badgeBool(true)
        );

        $this->assertEquals(
            '<span class="badge badge-danger">FALSE</span>',
            $this->ext->badgeBool(false)
        );
    }

    public function testButtonButtonType()
    {
        $this->assertEquals(
            '<button class="btn btn-primary" type="submit">ola</button>',
            $this->ext->button('ola')
        );
    }

    public function testButtonWithWrongType()
    {
        $this->assertNull($this->ext->button('ola', 'fake'));
    }

    public function testButtonWithContext()
    {
        $this->assertEquals(
            '<button class="btn btn-success" type="submit">ola</button>',
            $this->ext->button('ola', 'button', ['context' => 'success'])
        );
    }

    public function testButtonWithSize()
    {
        $this->assertEquals(
            '<button class="btn btn-primary btn-sm" type="submit">ola</button>',
            $this->ext->button('ola', 'button', ['size' => 'sm'])
        );
    }

    public function testButtonInputType()
    {
        $this->assertEquals(
            '<input class="btn btn-primary" type="submit" value="ola">',
            $this->ext->button('ola', 'input')
        );
    }

    public function testButtonLinkType()
    {
        $this->assertEquals(
            '<a class="btn btn-primary" href="#" role="button">ola</a>',
            $this->ext->button('ola', 'link')
        );
    }

    public function testCheckbox()
    {
        $this->assertSimilar(
            '<label class="custom-control custom-checkbox"><input type="hidden" name="choix" value="0"><input type="checkbox" class="custom-control-input" name="choix" value="1" checked><span class="custom-control-indicator"></span><span class="custom-control-description">Choix</span></label>',
            $this->ext->checkbox('choix', 1, 'Choix')
        );
    }

    public function testProgressWithOnlyValue()
    {
        $this->assertSimilar(
            '<div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" title="65%">65</div>
            </div>',
            $this->ext->progress(65)
        );
    }
}
