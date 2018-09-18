<?php
namespace Tests\Rcnchris\Core\Twig;

use Rcnchris\Core\Tools\Collection;
use Rcnchris\Core\Tools\Items;
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
        $this->assertNotEmpty($this->ext->getFilters());
        $this->assertNotEmpty($this->ext->getFunctions());
    }

    /**
     * Obtenir une alerte
     */
    public function testGetAlert()
    {
        $this->assertEquals(
            '<div class="alert alert-info" role="alert">ola</div>'
            , $this->ext->alert('ola')
        );

        $this->assertEquals(
            '<div class="alert alert-dark" role="alert">ola</div>'
            , $this->ext->alert('ola', 'dark')
        );

        $this->assertSimilar(
            '<div class="alert alert-dark alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>ola
            </div>'
            , $this->ext->alert('ola', 'dark', ['dismissible' => true])
        );

        $this->assertEquals(
            '<div class="alert alert-dark" role="alert"><i class="fa fa-info"></i> ola</div>'
            , $this->ext->alert('ola', 'dark', ['icon' => '<i class="fa fa-info"></i>'])
        );

        $this->assertNull($this->ext->alert('ola', 'fake'));
    }

    /**
     * Obtenir un badge
     */
    public function testGetBadgeWithoutContext()
    {
        $this->assertEquals(
            '<span class="badge badge-secondary">ola</span>'
            , $this->ext->badge('ola')
        );
    }

    public function testGetBadgeWithObject()
    {
        $o = new Items('ola,ole,oli');
        $this->assertEquals(
            '<span class="badge badge-secondary">' . serialize(['ola', 'ole', 'oli']) . '</span>'
            , $this->ext->badge($o)
        );
    }

    public function testGetBadgeWithContext()
    {
        $this->assertEquals(
            '<span class="badge badge-dark">ola</span>'
            , $this->ext->badge('ola', 'dark')
        );
    }

    public function testGetBadgePill()
    {
        $this->assertEquals(
            '<span class="badge badge-pill badge-dark">ola</span>'
            , $this->ext->badge('ola', 'dark', ['pill' => true])
        );
    }

    public function testGetBadgeWithLink()
    {
        $this->assertSimilar(
            '<a class="badge badge-dark" href="http://link.com">ola</a>'
            , $this->ext->badge('ola', 'dark', ['link' => 'http://link.com'])
        );
    }

    public function testGetBadgeBool()
    {
        $this->assertEquals(
            '<span class="badge badge-success">TRUE</span>'
            , $this->ext->badgeBool(true)
        );

        $this->assertEquals(
            '<span class="badge badge-danger">FALSE</span>'
            , $this->ext->badgeBool(false)
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
        $this->assertSimilar('
            <label class="custom-control custom-checkbox">
                <input name="choix" type="hidden" value="0">
                <input checked class="custom-control-input" name="choix" type="checkbox" value="1">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">Choix</span>
            </label>',
            $this->ext->checkbox('choix', 1, 'Choix')
        );
    }

    public function testProgressWithOnlyValue()
    {
        $this->assertSimilar(
            '<div class="progress">
                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="65" class="progress-bar bg-info" role="progressbar" style="width: 65%" title="65%">65</div>
            </div>',
            $this->ext->progress(65)
        );
    }

    public function testGetAlertResult()
    {
        $this->assertEquals(
            '<div class="alert alert-secondary"><samp>ola</samp></div>',
            $this->ext->alertResult('ola')
        );
    }
}
