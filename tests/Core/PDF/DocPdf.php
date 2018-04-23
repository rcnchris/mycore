<?php
/**
 * Fichier AbstractPdf.php du 21/04/2018
 * Description : Fichier de la classe AbstractPdf
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Tests\Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\Behaviors\BookmarksPdfTrait;
use Rcnchris\Core\PDF\Behaviors\ComponentsPdfTrait;

/**
 * Class AbstractPdf
 *
 * @category PDF
 *
 * @package  Tests\Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class DocPdf extends AbstractPDF
{
    use ComponentsPdfTrait, BookmarksPdfTrait;

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

    /**
     * Génère un document PDF contenant la documentation d'un template PDF
     *
     * @param string|object|null $className Nom de la classe de la démonstration
     *
     * @return $this
     */
    public function demo($className = null)
    {
        if (is_null($className)) {
            $className = get_class($this);
        }

        $this
            ->printInfoClass($className, true, true, true)
            ->printPublicProperties()
            ->printNativeMethods();

        $this->SetLink($this->AddLink());

        return $this;
    }

    /**
     * Propriétés publiques propres à AbstractPDF
     *
     * @return $this
     */
    private function printPublicProperties()
    {
        $this->AddPage();
        $fullName = get_parent_class(get_class($this));
        $shortName = explode('\\', $fullName);
        $shortName = array_pop($shortName);
        $title = "Propriétés publiques propres à $shortName";
        $this->addBookmark($title, 1, -1)->title($title);

        // Options
        $this->addBookmark('options', 2, -1);
        $this->title('options', 1);
        $this->SetFont(null, null, null, ['heightline' => 6]);
        $desc = "Collection qui contient les propriétés d'écritures.";
        $this->MultiCell(0, 6, utf8_decode($desc), 0, 'J');

        $this->title('Exemple', 2);
        $this->codeBloc("\$pdf->options->get('fontFamily')");
        $this->title('Retourne', 2);
        $this->codeBloc($this->options->get('fontFamily'));

        $this->title('Liste des clés', 2);
        $this->codeBloc($this->options->keys()->join(', '));

        return $this;
    }


    /**
     * Méthodes natives non surchargées
     *
     * @return $this
     */
    private function printNativeMethods()
    {
        $this->AddPage();
        $title = 'Méthodes natives à FPDF non surchargées';
        $this->addBookmark($title, 1)->title($title);

        $this->Ln();
        $this->addBookmark('AcceptPageBreak', 2);
        $this->title('AcceptPageBreak', 1);
        $desc = "Lorsqu'une condition de saut de page est remplie, la méthode est appelée, et en fonction de la valeur de retour, le saut est effectué ou non. \n" .
            "L'implémentation par défaut renvoie une valeur selon le mode sélectionné par SetAutoPageBreak(). " .
            " Cette méthode est appelée automatiquement et ne devrait donc pas être appelée directement par l'application.";
        $this->MultiCell(0, 6, utf8_decode($desc), 0, 'J');
        $this->printLink('http://www.fpdf.org/fr/doc/acceptpagebreak.htm', 'Voir la documentation sur le site');
        $this->title('Retourne', 2);
        $this->codeBloc("boolean");
        $this->title('Exemple', 2);
        $this->codeBloc("\$pdf->AcceptPageBreak();");
        $this->title('Résultat', 2);
        $this->codeBloc(serialize($this->AcceptPageBreak()));

        $this->Ln();
        $this->addBookmark('AddFont', 2)->title('AddFont', 1);
        $desc = "Importe une police TrueType, OpenType ou Type1 et la rend disponible. \n" .
            "Il faut au préalable avoir généré un fichier de définition de police avec l'utilitaire MakeFont. " .
            " Le fichier de définition (ainsi que le fichier de police en cas d'incorporation) doit être présent dans le répertoire des polices. " .
            " S'il n'est pas trouvé, l'erreur \"Could not include font definition file\" est renvoyée.";
        $this->MultiCell(0, 6, utf8_decode($desc), 0, 'J');
        $this->printLink('http://www.fpdf.org/fr/doc/addfont.htm', 'Voir la documentation sur le site');
        $this->title('Retourne', 2);
        $this->codeBloc("void");
        $this->title('Exemple', 2);
        $this->codeBloc("\$pdf->AddFont('Comic','I')");

        $this->Ln();
        $this->addBookmark('SetMargins', 2)->title('SetMargins', 1);
        $desc = "Fixe les marges gauche, haute et droite. Par défaut, elles valent 1 cm. Appelez cette méthode si vous désirez les changer.";
        $this->MultiCell(0, 6, utf8_decode($desc), 0, 'J');
        $this->printLink('http://www.fpdf.org/fr/doc/setmargins.htm', 'Voir la documentation sur le site');
        $this->title('Retourne', 2);
        $this->codeBloc("void");
        $this->title('Exemple', 2);
        $this->codeBloc("\$pdf->SetMargins(15, 20)");

        $this->Ln();
        $this->addBookmark('SetLeftMargin', 2)->title('SetLeftMargin', 1);
        $desc = "Fixe la marge gauche. La méthode peut être appelée avant de créer la première page.\nSi l'abscisse courante se retrouve hors page, elle est ramenée à la marge.";
        $this->MultiCell(0, 6, utf8_decode($desc), 0, 'J');
        $this->printLink('http://www.fpdf.org/fr/doc/setleftmargin.htm', 'Voir la documentation sur le site');
        $this->title('Retourne', 2);
        $this->codeBloc("void");
        $this->title('Exemple', 2);
        $this->codeBloc("\$pdf->SetLeftMargin(15)");

        return $this;
    }
}
