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

use Rcnchris\Core\PDF\Behaviors\ComponentsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\DesignerPdfTrait;
use Rcnchris\Core\PDF\Behaviors\Psr7PdfTrait;
use Rcnchris\Core\PDF\Behaviors\RessourcesPdfTrait;
use Rcnchris\Core\PDF\PdfDoc;

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
class DocPdf extends PdfDoc
{
    public function Header()
    {
        parent::SetCreator('My Core');
        parent::SetAuthor('rcn');
        parent::SetTitle('Tests unitaires du ' . (new \DateTime())->format('d-m-Y à H:i:s'));
        parent::SetSubject('Tests unitaires ' . get_class($this));
        $this->SetFont($this->getFontProperty('family'), 'B', 14, ['color' => '#000000']);
        $this->Cell(0, 10, utf8_decode($this->getMetadata('Title')), 0, 1, 'C', false);
        $this->draw('line');
        $this->SetFont();
    }

    public function Footer()
    {
        $this->SetY($this->getMargin('b') * -1);
        $this->draw('line');
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
            ->printInfoClass($className, false, true, true)
            ->printPublicProperties()
            ->printPublicMethods()
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
        $this->addBookmark('options', 2)->title('options', 1);
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
     * Méthodes publiques
     *
     * @return $this
     */
    private function printPublicMethods()
    {
        $this->AddPage();
        $title = "Méthodes publiques";
        $this->addBookmark($title, 1)->title($title, 0);

        /**
         * __construct
         */
        $methodName = '__construct';
        $desc = "Il s'agit du constructeur de la classe. Il permet de fixer le format des pages, leur orientation par défaut ainsi que l'unité de mesure utilisée dans toutes les méthodes (sauf pour les tailles de police).";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this
            ->printLink('http://www.fpdf.org/fr/doc/__construct.htm', 'Voir la documentation sur le site')
            ->Ln();

        $this
            ->title('Paramètre', 2)
            ->codeBloc([
                "array|null \$options Options par défaut de construction du document"
            ])->Ln();

        $this
            ->title('Retourne', 2)
            ->codeBloc(PdfDoc::class)
            ->Ln();

        $desc = "Utilisation de préférences par défaut pour créer le document (police, hauteur de ligne...). Elles sont stockées dans la propriété 'options' qui est une Collection.";
        $this
            ->title('Surcharge', 2)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this
            ->title('Exemple', 2)
            ->codeBloc([
                "\$pdf = new DocPdf(['orientation' => 'L']);"
            ]);

        /**
         * __toString
         */
        $this->AddPage();
        $methodName = '__toString';
        $desc = "Obtenir le document au format string.";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this
            ->title('Retourne', 2)
            ->codeBloc('string')
            ->Ln();

        $this
            ->title('Exemple', 2)
            ->codeBloc([
                "\$pdf->__toString();"
            ]);

        /**
         * AddPage
         */
        $this->AddPage();
        $methodName = 'AddPage';
        $desc = "Ajoute une nouvelle page au document. Si une page était en cours, la méthode Footer() est appelée pour traiter le pied de page. "
        . "Puis la page est ajoutée, la position courante mise en haut à gauche en fonction des marges gauche et haute, et Header() est appelée pour afficher l'en-tête.\n"
            . "La police qui était en cours au moment de l'appel est automatiquement restaurée. Il n'est donc pas nécessaire d'appeler à nouveau SetFont() si vous souhaitez continuer avec la même police. Même chose pour les couleurs et l'épaisseur du trait."
            . "L'origine du système de coordonnées est en haut à gauche et les ordonnées croissantes vont vers le bas.";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this
            ->printLink('http://www.fpdf.org/fr/doc/addpage.htm', 'Voir la documentation sur le site')
            ->Ln();

        $desc = "Application des préférences par défaut, crée l'alias du nombre de page à la première page ajoutée.";
        $this
            ->title('Surcharge', 2)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');

        /**
         * getTotalPages
         */
        $this->AddPage();
        $methodName = 'getTotalPages';
        $desc = "Obtenir le nombre total de pages au moment de l'appel.";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this
            ->title('Retourne', 2)
            ->codeBloc('int')
            ->Ln();

        $this
            ->title('Exemple', 2)
            ->codeBloc([
                "\$pdf->getTotalPages();"
            ])->Ln();

        $this
            ->title('Résultat', 2)
            ->codeBloc(serialize($this->getTotalPages()));

        /**
         * getBodySize
         */
        $this->AddPage();
        $methodName = 'getBodySize';
        $desc = "Obtenir la taille du corps.";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this
            ->title('Paramètre', 2)
            ->codeBloc([
                "string|null \$type (width ou height)"
            ])->Ln();

        $this
            ->title('Retourne', 2)
            ->codeBloc('array|double')
            ->Ln();

        $this
            ->title('Exemple', 2)
            ->codeBloc([
                "\$pdf->getBodySize('width');"
            ])->Ln();

        $this
            ->title('Résultat', 2)
            ->codeBloc(serialize($this->getBodySize('width')));

        /**
         * getOrientation
         */
        $this->AddPage();
        $methodName = 'getOrientation';
        $desc = "Obtenir l'orientation courante.";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this
            ->title('Retourne', 2)
            ->codeBloc('string')
            ->Ln();

        $this
            ->title('Exemple', 2)
            ->codeBloc([
                "\$pdf->getOrientation();"
            ])->Ln();

        $this
            ->title('Résultat', 2)
            ->codeBloc(serialize($this->getOrientation()));

        /**
         * getMargin
         */
        $this->AddPage();
        $methodName = 'getMargin';
        $desc = "Obtenir toutes les marges ou l'une d'entre elle.";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this
            ->title('Paramètre', 2)
            ->codeBloc([
                "string|null \$type (r, l, t, b)"
            ])->Ln();

        $this
            ->title('Retourne', 2)
            ->codeBloc('array|double')
            ->Ln();

        $this
            ->title('Exemple', 2)
            ->codeBloc([
                "\$pdf->getMargin('b');"
            ])->Ln();

        $this
            ->title('Résultat', 2)
            ->codeBloc(serialize($this->getMargin('b')));

        /**
         * setMargin
         */
        $this->AddPage();
        $methodName = 'setMargin';
        $desc = "Définir la valeur d'une marge.";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this
            ->title('Paramètres', 2)
            ->codeBloc([
                "string \$type  Type de marge (top, bottom, left, right)",
                "double \$value Valeur de la marge"
            ])->Ln();

        $this
            ->title('Retourne', 2)
            ->codeBloc(PdfDoc::class)
            ->Ln();

        $this->title('Exemple', 2)
            ->codeBloc([
                "\$pdf->setMargin('left', 15);"
            ]);

        /**
         * getCursor
         */
        $this->AddPage();
        $methodName = 'getCursor';
        $desc = "Obtenir toutes les marges ou l'une d'entre elle.";

        $this->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this->title('Paramètre', 2)
            ->codeBloc([
                "string|null \$type (r, l, t, b)"
            ])
            ->Ln();

        $this->title('Retourne', 2)
            ->codeBloc("array|double|boolean")
            ->Ln();

        $this->title('Obtenir la position de x et y', 2)
            ->codeBloc([
                "\$pdf->getCursor();"
            ])
            ->Ln();

        $this->title('Résultat', 2)
            ->codeBloc(serialize($this->getCursor()));

        /**
         * getMetadata
         */
        $this->AddPage();
        $methodName = 'getMetadata';
        $desc = "Obtenir les meta données ou la valeur de l'une d'entre elle.";

        $this->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this->title('Paramètre', 2)
            ->codeBloc([
                "string|null \$name Nom de la clé à retourner"
            ])
            ->Ln();

        $this->title('Retourne', 2)
            ->codeBloc("array|bool")
            ->Ln();

        $this->title('Obtenir toutes les meta-données', 2)
            ->codeBloc([
                "\$pdf->getMetadata();"
            ])
            ->Ln();

        $this->title('Résultat', 2)
            ->codeBloc(serialize($this->getMetadata()))
            ->Ln();

        $this->title('Obtenir l\'auteur du document', 2)
            ->codeBloc([
                "\$pdf->getMetadata('Author');"
            ])
            ->Ln();

        $this->title('Résultat', 2)
            ->codeBloc(serialize($this->getMetadata('Author')));

        /**
         * setMetadata
         */
        $this->AddPage();
        $methodName = 'setMetadata';
        $desc = "Définir une meta-donnée.";

        $this->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this->title('Paramètre', 2)
            ->codeBloc([
                "string     \$key   Nom de la clé ou tableaud",
                "mixed|null \$value Valeur de la clé"
            ])->Ln();

        $this->title('Retourne', 2)
            ->codeBloc("void")
            ->Ln();

        $this->title('Définir une meta-donnée', 2)
            ->codeBloc([
                "\$pdf->setMetadata('Test', 'Ola le test');"
            ])->Ln();

        $this->title('Définir plusieurs meta-avec un tableau', 2)
            ->codeBloc([
                "\$pdf->->setMetadata([",
                "\t'Ola' => 'le test', ",
                "\t'Ole' => 'On ferme'",
                "]);",
            ]);

        /**
         * setCursor
         */
        $this->AddPage();
        $methodName = 'setCursor';
        $desc = "Se positionner dans le document.";

        $this->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this->title('Paramètre', 2)
            ->codeBloc([
                "int      \$x",
                "int|null \$y"
            ])
            ->Ln();

        $this->title('Retourne', 2)
            ->codeBloc(PdfDoc::class)
            ->Ln();

        $this->title('Se positionner à la colonne 20 et à la ligne 55.', 2)
            ->codeBloc([
                "\$pdf->setCursor(20, 55);"
            ]);

        /**
         * hasFont
         */
        $this->AddPage();
        $methodName = 'hasFont';
        $desc = "Vérifier la présence d'une police par son nom.";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this
            ->title('Retourne', 2)
            ->codeBloc('boolean')
            ->Ln();

        $this
            ->title("La police 'helvetica' est-elle disponible ?", 2)
            ->codeBloc([
                "\$pdf->hasFont('helvetica');"
            ])->Ln();

        $this
            ->title('Résultat', 2)
            ->codeBloc(serialize($this->hasFont('helvetica')));

        /**
         * getFonts
         */
        $this->AddPage();
        $methodName = 'getFonts';
        $desc = "Obtenir la liste des polices disponibles.";
        $this
            ->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this
            ->title('Retourne', 2)
            ->codeBloc('array')
            ->Ln();

        $this
            ->title('Obtenir la liste des polices', 2)
            ->codeBloc([
                "\$pdf->getFonts();"
            ])->Ln();

        $this
            ->title('Résultat', 2)
            ->codeBloc(serialize($this->getFonts()));

        /**
         * getToolColor
         */
        $this->AddPage();
        $methodName = 'getToolColor';
        $desc = "Obtenir la commande de la couleur courante d'un outil.";

        $this->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this->title('Paramètre', 2)
            ->codeBloc([
                "string|null \$tool text, fill ou draw"
            ])
            ->Ln();

        $this->title('Retourne', 2)
            ->codeBloc("array|string|bool")
            ->Ln();

        $this->title('Obtenir toutes les commandes', 2)
            ->codeBloc([
                "\$pdf->getToolColor();"
            ])
            ->Ln();

        $this
            ->title('Résultat', 2)
            ->codeBloc(serialize($this->getToolColor()))
            ->Ln();

        $this->title('Obtenir la commande de l\'outil de remplissage', 2)
            ->codeBloc([
                "\$pdf->getToolColor('fill');"
            ])
            ->Ln();

        $this
            ->title('Résultat', 2)
            ->codeBloc(serialize($this->getToolColor('fill')));

        /**
         * toFile
         */
        $this->AddPage();
        $methodName = 'toFile';
        $desc = "Enregistrer le document PDF sur le serveur.";

        $this->addBookmark($methodName, 2)
            ->title($methodName, 1)
            ->MultiCell(0, 5, utf8_decode($desc), 0, 'J');
        $this->Ln();

        $this->title('Paramètre', 2)
            ->codeBloc([
                "string|null \$fileName Chemin et nom du fichier PDF (sans l'extension)"
            ])->Ln();

        $this->title('Retourne', 2)
            ->codeBloc("string")
            ->Ln();

        $this->title('Exemple', 2)
            ->codeBloc([
                "\$pdf->toFile('path/to/file/filename');"
            ])->Ln();

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
        $this->addBookmark('AcceptPageBreak', 2)->title('AcceptPageBreak', 1);
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
