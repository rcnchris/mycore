<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\Behaviors\ComponentsPdfTrait;

/**
 * Class ComponentsPdf
 *
 * @category Tests
 *
 * @package  Tests\Rcnchris\Core\PDF
 */
class ComponentsPdf extends AbstractPDF
{
    use ComponentsPdfTrait;

    public function Header()
    {
        parent::SetCreator('My Core');
        parent::SetAuthor('rcn');
        parent::SetTitle('Tests unitaires du ' . (new \DateTime())->format('d-m-Y à H:i:s'));
        parent::SetSubject('Tests unitaires ' . get_class($this));
        $this->SetFont($this->getFontProperty('family'), 'B', 14, ['color' => '#000000']);
        $this->Cell(0, 10, utf8_decode($this->getMetadata('Title')), 0, 1, 'C', false);
        $this->addLine();
        $this->SetFont();
    }

    public function Footer()
    {
        $this->SetY($this->getMargin('b') * -1);
        $this->addLine();
        $this->SetFont(null, 'I', 8, ['color' => '#000000']);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' sur ' . '{nb}', 0, 0, 'C');
        $this->SetFont();
    }

    public function demo()
    {
        if ($this->getTotalPages() === 0) {
            $this->AddPage();
        }
        $this->Ln(2);
        $fullName = current(class_uses($this));
        $shortName = explode('\\', $fullName);
        $shortName = array_pop($shortName);
        $this->SetFont(null, 'B', 20, ['color' => '#2980b9', 'fillColor' => '#bdc3c7', 'drawColor' => '#2c3e50']);
        $this->Cell(0, 12, utf8_decode("Démonstration du trait $shortName"), 1, 1, 'C', true);
        $this->Ln();
        $this->printInfoClass($fullName);

        // Méthode title
        $this->SetFont(null, 'B', 16, ['color' => '#e74c3c']);
        $this->Cell(0, 10, 'title', 'B', 1);

        $this->SetFont(null, 'I', null, ['color' => '#000000']);
        $desc = "Imprime un titre stylé selon son niveau. Prend deux paramètres qui sont le texte du titre et son niveau.";
        $this->Write(10, utf8_decode($desc));
        $this->Ln();

        $this->SetFont(null, '', 10, ['color' => '#000000']);
        $label = "Exemple :";
        $this->Cell($this->GetStringWidth($label), 5, $label, 'B', 1);
        $this->Ln();
        $this->codeBloc("\$pdf->title('Le premier titre');");
        $this->title('Le premier titre');
        $this->Ln();

        $this->SetFont(null, '', 10, ['color' => '#000000']);
        $label = "Exemple :";
        $this->Cell($this->GetStringWidth($label), 5, $label, 'B', 1);
        $this->Ln();
        $this->codeBloc("\$pdf->title('Le second titre de niveau identique');");
        $this->title('Le second titre de niveau identique');
        $this->Ln();

        $this->SetFont(null, '', 10, ['color' => '#000000']);
        $label = "Exemple :";
        $this->Cell($this->GetStringWidth($label), 5, $label, 'B', 1);
        $this->Ln();
        $this->codeBloc("\$pdf->title('Le sous-titre', 1);");
        $this->title('Le sous-titre', 1);
        $this->Ln();

        $this->SetFont(null, '', 10, ['color' => '#000000']);
        $label = "Exemple :";
        $this->Cell($this->GetStringWidth($label), 5, $label, 'B', 1);
        $this->Ln();
        $title = 'Le troisième...';
        $this->codeBloc("\$pdf->title('".utf8_decode($title)."', 2);");
        $this->title($title, 2);
        $this->Ln();

        // Méthode codeBloc
        $this->SetFont(null, 'B', 16, ['color' => '#e74c3c']);
        $this->Cell(0, 10, 'codeBloc', 'B', 1);

        $this->SetFont(null, 'I', null, ['color' => '#000000']);
        $desc = "Imprime un bloc de code.";
        $this->Write(10, utf8_decode($desc));
        $this->Ln();

        $this->SetFont(null, '', 10, ['color' => '#000000']);
        $label = "Exemple :";
        $this->Cell($this->GetStringWidth($label), 5, $label, 'B', 1);
        $this->Ln();
        $this->codeBloc("\$pdf->getOptions();");
        $this->Ln();

        return $this;

    }
}

class ComponentsTraitTest extends PdfTestCase
{

    /**
     * @var ComponentsPdf
     */
    protected $pdf;

    public function setUp()
    {
        $this->pdf = new ComponentsPdf();
        $this->pdf->setMargin('bottom', 10);
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - Components');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
        $this->assertContains(
            'Rcnchris\Core\PDF\Behaviors\ComponentsPdfTrait'
            , class_uses($this->pdf)
            , $this->getMessage("L'objet ne contient pas le trait attendu")
        );
    }

    public function testMakeInfosTraitDocument()
    {
        $fileDest = __DIR__ . '/res/ComponentsTrait';
        $this->pdf
            ->demo()
            ->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
    }
}
