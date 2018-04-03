<?php
namespace Tests\Rcnchris\Core\PDF;

use Rcnchris\Core\PDF\AbstractPDF;
use Rcnchris\Core\PDF\Behaviors\BookmarkPdfTrait;
use Rcnchris\Core\PDF\Behaviors\ColorsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\DataPdfTrait;
use Rcnchris\Core\PDF\Behaviors\Ean13PdfTrait;
use Rcnchris\Core\PDF\Behaviors\EllipsePdfTrait;
use Rcnchris\Core\PDF\Behaviors\GridPdfTrait;
use Rcnchris\Core\PDF\Behaviors\IconsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\JoinedFilePdfTrait;
use Rcnchris\Core\PDF\Behaviors\LayoutsPdfTrait;
use Rcnchris\Core\PDF\Behaviors\Psr7PdfTrait;
use Rcnchris\Core\PDF\Behaviors\RotatePdfTrait;
use Rcnchris\Core\PDF\Behaviors\RoundedRectPdfTrait;
use Rcnchris\Core\PDF\Behaviors\RowPdfTrait;
use Tests\Rcnchris\BaseTestCase;

class AllBehaviorsPdfDoc extends AbstractPDF
{
    use BookmarkPdfTrait,
        ColorsPdfTrait,
        DataPdfTrait,
        IconsPdfTrait,
        Psr7PdfTrait,
        RowPdfTrait,
        RotatePdfTrait,
        Ean13PdfTrait,
        EllipsePdfTrait,
        RoundedRectPdfTrait,
        GridPdfTrait,
        LayoutsPdfTrait;
        //,        JoinedFilePdfTrait;

    public function Header()
    {
        parent::SetCreator(get_class($this));
        parent::SetAuthor('rcn');
        parent::SetTitle('Tests unitaires ' . (new \DateTime())->format('d-m-Y H:i:s'));
        parent::SetSubject('Tests de tous les behaviors dans un seul document');

        $this->SetFont(
            $this->defaultOptions['font']['family'],
            'B', 14,
            [
                'color' => '#000000',
                'fillColor' => 'graylight',
                'drawColor' => '#000000'
            ]
        );

        // Mode grille ?
        if ($this->grid) {
            $this->drawGrid();
        }

        $this->Cell(0, 10, $this->getMetadata('Title'), 0, 1, 'C', true);
        $this->addLine();
        $this->SetFont();
    }

    public function Footer()
    {
        parent::SetY($this->getMargin('b') * -1);
        parent::SetTextColor(0);
        parent::SetDrawColor(0);
        $this->addLine();
        $this->SetFont($this->defaultOptions['font']['family'], 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' sur ' . '{nb}', 0, 0, 'C');
    }

    /**
     * Imprime les propriétés relatives à la classe utilisée
     */
    public function printDebugClass()
    {
        $this->title("Classe utilisée", 0);

        parent::MultiCell(0, 7, "Nom complet : " . get_class($this), 'B');
        parent::MultiCell(0, 7, utf8_decode("Parent : ") . get_parent_class(get_class($this)), 'B');

        $label = "Traits du parent : ";
        parent::Cell(parent::GetStringWidth($label), 7, $label);
        parent::MultiCell(0, 7, implode(', ', class_uses(get_parent_class(get_class($this)))));
        $this->addLine();

        parent::MultiCell(0, 7,
            utf8_decode("Le parent hérite de FPDF : ") . is_subclass_of(get_parent_class(get_class($this)),
                \FPDF::class), 'B');
        parent::MultiCell(0, 7, utf8_decode("Classes implémentées : ") . implode(', ', class_implements($this)), 'B');

        $label = utf8_decode("Traits utilisés : ");
        parent::Cell(parent::GetStringWidth($label), 7, $label);
        parent::MultiCell(0, 7, implode(', ', class_uses($this)));
        $this->addLine();

        $label = utf8_decode("Méthodes : ");
        parent::Cell(parent::GetStringWidth($label), 7, $label);
        parent::MultiCell(0, 7, implode(', ', get_class_methods($this)));
        $this->addLine();
    }

    private function printDebugPages()
    {
        $this->title("Pages", 1);

        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols("Numéro de la page courante", "page", serialize($this->page));
        $this->rowCols('Orientation par défaut', 'DefOrientation', serialize($this->DefOrientation));
        $this->rowCols('Orientation de la page courante', 'CurOrientation', serialize($this->CurOrientation));
        $this->rowCols('Rotation de la page courante', 'CurRotation', serialize($this->CurOrientation));
        $this->rowCols('Tailles de la page courante', 'CurPageSize', serialize($this->CurPageSize));
        $this->rowCols('Hauteur de la page', 'h', serialize($this->h));
        $this->rowCols('Largeur de la page', 'w', serialize($this->w));
        $this->rowCols('Tailles standard par format', 'StdPageSizes', serialize($this->StdPageSizes));
        $this->rowCols("Tailles par défaut d'une page", 'DefPageSize', serialize($this->DefPageSize));
        $this->rowCols('Page-related data', 'PageInfo', serialize($this->PageInfo));
        $this->rowCols('Longueur de la page courante en points', 'hPt', serialize($this->hPt));
        $this->rowCols('Largeur de la page courante en points', 'wPt', serialize($this->wPt));
        $this->rowCols('Saut de page automatique', 'AutoPageBreak', serialize($this->AutoPageBreak));
        $this->rowCols('Position du saut de page', 'PageBreakTrigger', serialize($this->PageBreakTrigger));
        $this->rowCols('Alias du numéro de page', 'AliasNbPages', serialize($this->AliasNbPages));
    }

    private function printDebugMarges()
    {
        $this->title("Marges", 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols("\t- Haut", 'tMargin', serialize($this->tMargin));
        $this->rowCols("\t- Bas", 'bMargin', serialize($this->bMargin));
        $this->rowCols("\t- Gauche", 'lMargin', serialize($this->lMargin));
        $this->rowCols("\t- Bas", 'rMargin', serialize($this->rMargin));
        $this->rowCols("\t- Cellule", 'cMargin', serialize($this->cMargin));
    }

    private function printDebugCursor()
    {
        $this->title("Curseur", 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols('Position X', 'x', serialize($this->x));
        $this->rowCols('Position Y', 'y', serialize($this->y));
    }

    private function printDebugFont()
    {
        $this->title("Police", 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols('Police courante', 'FontFamily', serialize($this->FontFamily));
        $this->rowCols('Style courant', 'FontStyle', serialize($this->FontStyle));
        $this->rowCols('Taille de la police courate en points', 'FontSizePt', serialize($this->FontSizePt));
        $this->rowCols('Taille de la police courate en unités', 'FontSize', serialize($this->FontSize));
        //$this->rowCols('Current font info', 'CurrentFont', serialize($this->CurrentFont));
        $this->rowCols('Drapeau souligné', 'underline', serialize($this->underline));
        $this->rowCols('Echelle de la police (ration points/unités)', 'k', serialize($this->k));
        $this->rowCols('Chemins des polices', 'fontpath', serialize($this->fontpath));
        $this->rowCols('Tableau des polices', 'CoreFonts', serialize($this->CoreFonts));
        //$this->rowCols('Array of used fonts', 'fonts', serialize($this->fonts));
        $this->rowCols('Espacement des mots', 'ws', serialize($this->ws));
    }

    private function printDebugLignes()
    {
        $this->title('Ligne', 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols("Largeur d'une ligne en unité", 'LineWidth', serialize($this->LineWidth));
    }

    private function printDebugCell()
    {
        $this->title('Cellule', 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');

        $this->rowCols('Hauteur de la dernière cellule imprimée', 'lasth', serialize($this->lasth));
    }

    /**
     * Imprime les propriétés relatives à l'outil dessin
     */
    private function printDebugTools()
    {
        $this->title('Tools', 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols('Commandes pour la couleur de dessin', 'DrawColor', serialize($this->DrawColor));
        $this->rowCols('Commandes pour la couleur de remplissage', 'FillColor', serialize($this->FillColor));
        $this->rowCols('Commandes pour la couleur du texte', 'TextColor', serialize($this->TextColor));
    }

    private function printDebugColor()
    {
        $this->title('Couleur', 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols(
            'Indique si les couleurs de remplissage et de texte sont différentes',
            'ColorFlag',
            serialize($this->ColorFlag)
        );
        $this->rowCols(
            'Indique si le canal alpha est utilisé',
            'WithAlpha',
            serialize($this->WithAlpha)
        );
    }

    /**
     * Imprime les propriétés relatives aux couleurs
     */
    public function printDebugPalette()
    {
        parent::AddPage();
        $this->title('Palette de couleurs');
        $this->setColsWidthInPourc(50, 20, 30);
        $this->setColsFill(false, false, true);
        foreach ($this->getColors() as $name => $hexa) {
            $this->setColsFillColors('black', 'red', $name);
            $this->rowCols($name, $hexa, '');
        }
        $this->SetFont();
        $this->setColsFill(false, false, false);
        $this->setColsFillColors('black', 'red', 'black');
    }

    private function printDebugImage()
    {
        $this->title('Image', 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols('Tableau des images utilisées', 'images', serialize($this->images));
    }

    private function printDebugLinks()
    {
        $this->title('Liens', 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols('Tableau des liens par page', 'PageLinks', serialize($this->PageLinks));
        $this->rowCols('Tableau des liens internes', 'links', serialize($this->links));
    }

    private function printDebugHeader()
    {
        $this->title('Header', 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols("Drapeau défini lors du traitement de l'en-tête", 'InHeader', serialize($this->InHeader));
    }

    /**
     * Imprime les propriétés relatives aux Footer
     */
    private function printDebugFooter()
    {
        $this->title('Footer', 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols("Drapeau défini lors du traitement du pied", 'InFooter', serialize($this->InFooter));
    }

    /**
     * Imprime les propriétés diverses
     */
    private function printDebugDivers()
    {
        $this->title('Divers', 1);
        $this->setColsWidthInPourc(30, 20, 50);
        $this->setColsTextColors('black', 'red', 'black');
        $this->rowCols("Numéro d'objet actuel", 'n', serialize($this->n));
        $this->rowCols("Tableau des décalages d'objet", 'offsets', serialize($this->offsets));
        $this->rowCols('Etat du document', 'state', serialize($this->state));
        $this->rowCols('Drapeau de compression', 'compress', serialize($this->compress));
        $this->rowCols("Tableau d'encodages", 'encodings', serialize($this->encodings));
        $this->rowCols('Tableau des CMaps ToUnicode', 'cmaps', serialize($this->cmaps));
        $this->rowCols('Mode du zoom', 'ZoomMode', serialize($this->ZoomMode));
        $this->rowCols('Mode de zoom de mise en page', 'LayoutMode', serialize($this->LayoutMode));
        $this->rowCols('Version FPDF', 'PDFVersion', serialize($this->PDFVersion));
    }

    /**
     * Imprime les tous les caractères d'une police donnée
     *
     * @param string $fontName Nom de la police
     */
    public function printCharsFont($fontName)
    {
        parent::AddPage();
        $this->title('Caractères de la police ' . $fontName);
        $this->SetFont(null, '', 20);
        for ($i = 32; $i <= 255; $i++) {
            $this->SetFont(
                $this->defaultOptions['font']['family']
                , $this->defaultOptions['font']['style']
                , 14
            );
            parent::Cell(12, 5.5, "$i : ");
            $this->SetFont($fontName);
            parent::Cell(0, 5.5, chr($i), 0, 1);
        }
        $this->SetFont(
            $this->defaultOptions['font']['family']
            , $this->defaultOptions['font']['style']
            , $this->defaultOptions['font']['size']
        );
    }

    private function printTraitBookmark()
    {
        parent::AddPage();
        $this->title('Signets', 1);
        $this->alert("Permet d'ajouter des favoris au document.");

        $this->infoClass(BookmarkPdfTrait::class);

        $this->title('addBookmark', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Ajouter un signet."));
        $this->codeBloc("\$pdf->addBookmark('Sous-Titre', 1);");
        $this->Ln();

        $this->title('getBookmarks', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Obtenir tous les signets."));
        $this->codeBloc("\$pdf->getBookmarks();");

        parent::MultiCell(0, 10, utf8_decode("Obtenir un signet."));
        $this->codeBloc("\$pdf->getBookmarks(3);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getBookmarks(3)));

        parent::MultiCell(0, 10, utf8_decode("Obtenir la valeur d'une clé d'un signet."));
        $this->codeBloc("\$pdf->getBookmarks(3, 't');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getBookmarks(3, 't')));
        $this->Ln();
    }

    private function printTraitColors()
    {
        parent::AddPage();

        $this->title('Couleurs', 1);
        $this->alert("Permet de disposer d'une palette de couleurs et de faire référence à des couleurs nommées. Il est possible de définir une tablette personnalisée.");

        $this->infoClass(ColorsPdfTrait::class);

        $this->title('getColors', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Obtenir toutes les couleurs"));
        $this->codeBloc("\$pdf->getColors();");

        $this->title('Couleurs disponibles', 3);
        $ln = 0;
        foreach ($this->getColors() as $name => $hexa) {
            $this->setToolColor($hexa, 'fill');
            parent::Cell(10, 5, '', 0, $ln, '', true);
            if ($this->GetX() + 10 > 190) {
                $ln = 1;
            } else {
                $ln = 0;
            }
        }
        $this->Ln();

        parent::MultiCell(0, 10, utf8_decode("Obtenir le code héxadécimal d'une couleur par son nom"));
        $this->codeBloc("\$pdf->getColors('aloha');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getColors('aloha')));

        parent::MultiCell(0, 10, utf8_decode("Obtenir le nom d'une couleur par son code héxadécimal"));
        $this->codeBloc("\$pdf->getColors('#1ABC9C');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getColors('#1ABC9C')));

        parent::MultiCell(0, 10, utf8_decode("Obtenir les valeurs RGB d'une couleur."));
        $this->codeBloc("\$pdf->getColors('aloha', true);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getColors('aloha', true)));
        $this->Ln();

        $this->title('colorToRgb', 2);
        parent::MultiCell(0, 10, utf8_decode("Obtenir les valeurs RGB d'une couleur au format héxadécimal."));
        $this->codeBloc("\$pdf->colorToRgb('#CCCCCC');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->colorToRgb('#CCCCCC')));
        $this->Ln();

        $this->title('hasColor', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Vérifier la présence d'une couleur. Accepte le nom ou le code héxadécimal."));
        $this->codeBloc("\$pdf->hasColor('aloha');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->hasColor('aloha')));
        $this->Ln();

        $this->title('addColor', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Ajouter une couleur."));
        $this->codeBloc("\$pdf->addColor('pinkjigglypuff', '#ff9ff3');");
        $this->addColor('pinkjigglypuff', '#ff9ff3');
        $this->Ln();

        $this->title('setColors', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir une palette personnalisée."));
        $this->codeBloc("\$pdf->setColors(['pinkjigglypuff' => '#ff9ff3', 'yellowcasandora' => '#feca57');");
        $this->Ln();
    }

    private function printTraitData()
    {
        $this->setData(['name' => 'Mathis', 'year' => 2007]);
        parent::AddPage();
        $this->title('Données', 1);
        $this->alert("Permet de disposer de données au sein du document.");
        $this->infoClass(DataPdfTrait::class);

        $this->title('setData', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Définir les données du document'));
        $this->codeBloc("\$pdf->setData(['name' => 'Mathis', 'year' => 2007]);");
        $this->Ln();

        $this->title('getData', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Obtenir la liste de toutes les données'));
        $this->codeBloc("\$pdf->getData();");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->SetFont();
        $this->codeBloc(serialize($this->getData()));

        parent::MultiCell(0, 10, utf8_decode('Obtenir la valeur d\'une clé'));
        $this->codeBloc("\$pdf->getData('name');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->SetFont();
        $this->codeBloc(serialize($this->getData('name')));
        $this->Ln();

        $this->title('hasKey', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Vérifier la présence d'une clé"));
        $this->codeBloc("\$pdf->hasKey('name');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->SetFont();
        $this->codeBloc(serialize($this->hasKey('name')));
        $this->Ln();

        $this->title('hasValue', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Vérifier la présence d'une valeur"));
        $this->codeBloc("\$pdf->hasValue(2007);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->SetFont();
        $this->codeBloc(serialize($this->hasValue(2007)));
        $this->Ln();
    }

    private function printTraitIcons()
    {
        parent::AddPage();
        $this->title('Icônes', 1);
        $this->alert("Permet d'imprimer des icônes.");
        $this->infoClass(IconsPdfTrait::class);

        $this->title('printIcon', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Obtenir une icône par son nom'));
        $this->codeBloc("\$pdf->printIcon('envelop', x, y, width, style);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Exemple :");
        $this->printIcon(
            'envelop',
            $this->getColWidth(0) + $this->getColWidth(1) + $this->lMargin,
            $this->GetY() - 2,
            20
        );
    }

    private function printTraitPsr7()
    {
        parent::AddPage();
        $this->title('PSR7', 1);
        $this->alert("Permet de visualiser et télécharger le document PDF via le navigateur.");
        $this->infoClass(Psr7PdfTrait::class);

        $this->title('toView', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Voir le document dans le navigateur.'));
        $this->codeBloc("\$pdf->toView(response);");
        $this->Ln();

        $this->title('toDownload', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Voir le document dans le navigateur.'));
        $this->codeBloc("\$pdf->toDownload(response, 'doc');");
    }

    private function printTraitRowsCols()
    {
        parent::AddPage();
        $this->title('Colonnes', 1);
        $this->alert("Permet de faciliter l'écriture d'une ligne au sein d'aun tableau définit.");
        $this->infoClass(RowPdfTrait::class);

        $this->title('setColsWidth', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Définir trois colonnes avec une largeur en unité.'));
        $this->codeBloc("\$pdf->setColsWidth(30, 20, 50);");
        $this->Ln();

        $this->title('setColsWidthInPourc', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Définir trois colonnes avec une largeur en pourcentage du corps.'));
        $this->codeBloc("\$pdf->setColsWidthInPourc(30, 20, 50);");
        $this->Ln();

        $this->title('setColsAlign', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir l'alignement de chaque colonne."));
        $this->codeBloc("\$pdf->setColsAlign('L', 'C', 'R');");
        $this->Ln();

        $this->title('setColsBorder', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir la bordure de chaque colonne."));
        $this->codeBloc("\$pdf->setColsBorder(0, 'B', 'R');");
        $this->Ln();

        $this->title('setColsFill', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir le remplissage de chaque colonne."));
        $this->codeBloc("\$pdf->setColsFill(false, true, false);");
        $this->Ln();

        $this->title('setColsTextColors', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir le remplissage de chaque colonne."));
        $this->codeBloc("\$pdf->setColsTextColors('black', 'red', 'graylight');");
        $this->Ln();

        $this->title('setColsFillColors', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir la couleur de remplissage de chaque colonne."));
        $this->codeBloc("\$pdf->setColsFillColors('black', 'red', 'graylight');");
        $this->Ln();

        $this->title('setColsDrawColors', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir la couleur du trait de chaque colonne."));
        $this->codeBloc("\$pdf->setColsDrawColors('black', 'red', 'graylight');");
        $this->Ln();

        $this->title('setColsFont', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir la police de chaque colonne."));
        $this->codeBloc("\$pdf->setColsFont('helvetica', 'courier', 'helvetica');");
        $this->Ln();

        $this->title('setColsFontSize', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir la taille de la police de chaque colonne."));
        $this->codeBloc("\$pdf->setColsFontSize(10, 8, 6);");
        $this->Ln();

        $this->title('setHeightLine', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir la hauteur de toutes les lignes."));
        $this->codeBloc("\$pdf->setHeightLine(10);");
        $this->Ln();

        $this->title('getNbCols', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Obtenir le nombre de colonnes."));
        $this->codeBloc("\$pdf->getNbCols();");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getNbCols()));
        $this->Ln();

        $this->title('getColWidth', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Obtenir la largeur d'une colonne."));
        $this->codeBloc("\$pdf->getColWidth(1);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getColWidth(1)));
        $this->Ln();

        $this->title('getColsProperties', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Obtenir les propriétés de toutes les colonnes."));
        $this->codeBloc("\$pdf->getColsProperties();");

        parent::MultiCell(0, 10, utf8_decode("Obtenir les propriétés d'une colonne."));
        $this->codeBloc("\$pdf->getColsProperties(1);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Retourne :");
        $this->codeBloc(serialize($this->getColsProperties(1)));
        $this->Ln();

        $this->title('rowCols', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Ajouter une ligne au tableau."));
        $this->codeBloc("\$pdf->rowCols('ola', 'les', 'gens');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Exemple :");
        $this->rowCols('ola', 'les', 'gens');
    }

    private function printTraitRotate()
    {
        parent::AddPage();
        $this->title('Rotation', 1);
        $this->alert("Permet d'appliquer une rotation sur un texte ou une image.");
        $this->infoClass(RotatePdfTrait::class);

        $this->title('rotatedText', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Rotation du texte autour de son origine."));
        $this->codeBloc("\$pdf->rotatedText(100, 60, 'Hello !', 45);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Exemple :");
        $this->rotatedText($this->GetX(), $this->GetY() + 7, 'Hello !', 45);
        $this->Ln();

        $this->title('rotatedImage', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Rotation de l'image autour du coin supérieur gauche."));
        $this->codeBloc("\$pdf->rotatedImage('circle.png', 85, 60, 40, 16, 45);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Exemple :");
        $this->rotatedImage(__DIR__ . '/files/circle.png', $this->GetX(), $this->GetY() + 35, 40, 16, 45);
    }

    private function printTraitEan13()
    {
        parent::AddPage();
        $this->title('Codes à barres', 1);
        $this->alert("Permet d'imprimer des codes à barres selon la norme EAN13 et UPCA.");
        $this->infoClass(Ean13PdfTrait::class);

        $this->title('ean13', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Code à barres EAN13'));
        $this->codeBloc("\$pdf->ean13(100, 60, '123456789012', 5);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Exemple :");
        $this->ean13(
            $this->lMargin,
            $this->GetY(),
            '123456789012',
            5
        );
        $this->Ln();

        $this->title('upca', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Code à barres UPCA.'));
        $this->codeBloc("\$pdf->upca(100, 60, '123456789012', 5);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Exemple :");
        $this->upca(
            $this->lMargin,
            $this->GetY(),
            '123456789012',
            5
        );
    }

    private function printTraitEllipse()
    {
        parent::AddPage();
        $this->title('Ellipse', 1);
        $this->SetFont(null, 'I', 10, ['color' => 'black', 'fillColor' => 'graylight']);
        $this->alert("Permet de tracer cercles et ellipses sans images.");
        $this->infoClass(EllipsePdfTrait::class);

        $this->title('circle', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Dessiner une cercle.'));
        $this->codeBloc("\$pdf->circle(100, 25, 7, 'F');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Exemple :");
        $this->circle($this->lMargin + 7, $this->GetY() + 7, 7, 'F');
        $this->Ln();
        $this->Ln();

        $this->title('ellipse', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Dessiner une ellipse.'));
        $this->codeBloc("\$pdf->ellipse(100, 50, 7, 7, 10);");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Exemple :");
        $this->ellipse($this->lMargin + 7, $this->GetY() + 7, 7, 10);
    }

    private function printTraitRoundedRect()
    {
        parent::AddPage();
        $this->title('Rectangles arrondis', 1);
        $this->SetFont(null, 'I', 10, ['color' => 'black', 'fillColor' => 'graylight']);
        $this->alert("Permet de tracer un rectangle avec les bords arrondis.");
        $this->infoClass(RoundedRectPdfTrait::class);

        $this->title('roundedRect', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode('Dessiner un rectangle arrondi.'));
        $this->codeBloc("\$pdf->roundedRect(100, 25, 7, 'F');");
        $this->SetFont(null, 'BI');
        parent::MultiCell(0, 10, "Exemple :");
        $this->roundedRect(
            $this->GetX(),
            $this->GetY() + 7,
            $this->getColWidth(2),
            46, 5, '13', 'D'
        );

    }

    private function printTraitGrid()
    {
        $this->setGrid(10);
        $this->AddPage();
        $this->title('Grille', 1);
        $this->alert("Ajoute une grille pour aider à positionner les élements lors du développement.");
        $this->infoClass(GridPdfTrait::class);

        $this->title('setGrid', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir la taille de l'échelle de la grille (5mm par défaut)."));
        $this->codeBloc("\$pdf->setGrid(10);");
        $this->Ln();

        $this->title('drawGrid', 2);
        $this->addLine();
        parent::MultiCell(0, 10, utf8_decode("Définir la taille de l'échelle de la grille (5mm par défaut)."));
        $this->codeBloc("\$pdf->drawGrid();");
        $this->setGrid(false);
    }

    private function printTraitJoinedFile()
    {
        $this->setGrid();
        $this->AddPage();
        $this->title('Fichier joint', 1);
        $this->SetFont(null, 'I', 10, ['color' => 'black', 'fillColor' => 'graylight']);
        $this->alert("Permet de joindre des fichiers au PDF.");
        $this->infoClass(JoinedFilePdfTrait::class);
        $this->setColsWidthInPourc(30, 30, 40);
        $this->setColsTextColors('black', 'red', 'black');
        $this->setColsAlign('L', 'L', 'L');
        $this->rowCols(
            'Joindre un fichier',
            'attach(/path/to/file.txt)',
            serialize(null)
        );
        $this->attach(__DIR__ . '/textFile.txt');
    }

    public function printNativesProperties()
    {
        parent::AddPage();
        $this->title('Propriétés natives');
        $this->printDebugPages();
        $this->printDebugMarges();
        $this->printDebugFont();
        $this->printDebugCursor();
        $this->printDebugLignes();
        $this->printDebugCell();
        $this->printDebugTools();
        $this->printDebugColor();
        $this->printDebugImage();
        $this->printDebugLinks();
        $this->printDebugDivers();
    }

    public function printNativesMethods()
    {
        parent::AddPage();
        $this->title('Méthodes natives');
        $this->printDebugHeader();
        $this->printDebugFooter();
        $this->setColsWidthInPourc(30, 30, 40);
        $this->setColsTextColors('black', 'red', 'black');

        $this->title('Pages', 1);
        $this->rowCols(
            'Renvoie la hauteur de la page courante',
            'GetPageHeight',
            serialize($this->GetPageHeight())
        );
        $this->rowCols(
            'Renvoie la largeur de la page courante',
            'GetPageWidth',
            serialize($this->GetPageWidth())
        );

        $this->title('Police', 1);
        $this->rowCols(
            "Renvoie la longueur d'une chaîne en unité utilisateur. Une police doit être sélectionnée. La chaîne testée : 'ola les gens'",
            'GetStringWidth',
            serialize($this->GetStringWidth('ola les gens'))
        );

        $this->title('Curseur', 1);
        $this->rowCols("Renvoie l'abscisse de la position courante.", 'GetX', serialize($this->GetX()));
        $this->rowCols("Renvoie l'ordonnée de la position courante.", 'GetY', serialize($this->GetY()));
        $this->rowCols("Renvoie le numéro de page courant.", 'PageNo', serialize($this->PageNo()));
        $this->Ln();
    }

    public function printFunctionalsMethods()
    {
        parent::AddPage();
        $this->title('Méthodes fonctionnelles', 0);

        $this->title('Tailles', 1);
        $this->rowCols(
            "Obtenir la taille du corps.",
            "getBodySize",
            serialize($this->getBodySize())
        );
        $this->rowCols(
            "",
            "getBodySize('width')",
            serialize($this->getBodySize('width'))
        );

        $this->title('Tools', 1);
        $this->rowCols(
            "Obtenir la liste des outils.",
            "getTools()",
            serialize($this->getTools())
        );
        $this->rowCols(
            "Obtenir les commandes de tous les outils.",
            "getToolColor()",
            serialize($this->getToolColor())
        );
        $this->rowCols(
            "Obtenir les commandes de couleurs d'un outil ('text' par défaut).",
            "getToolColor('fill')",
            serialize($this->getToolColor('fill'))
        );
        $this->rowCols(
            "Vérifie la validité d'un type d'outil",
            "hasTool('text')",
            serialize($this->hasTool('text'))
        );

        $this->title('Unités de mesures', 1);
        $this->rowCols(
            "Vérifier la présence d'une unité de mesure.",
            "hasUnit('in')",
            serialize($this->hasUnit('in'))
        );
        $this->rowCols(
            "Obtenir la liste des unités de mesures.",
            "getUnits()",
            serialize($this->getUnits())
        );

        $this->title('Format', 1);
        $this->rowCols(
            "Vérifier la présence d'un format",
            "hasFormat('letter')",
            serialize($this->hasFormat('letter'))
        );
        $this->rowCols(
            "Obtenir la liste des formats",
            "getFormats()",
            serialize($this->getFormats())
        );

        $this->title('Position du curseur', 1);
        $this->rowCols(
            "Obtenir les coordonées du curseur",
            "getCursor",
            serialize($this->getCursor())
        );
        $this->rowCols(
            "",
            "getCursor('x')",
            serialize($this->getCursor('x'))
        );
        $this->rowCols(
            "Placer le curseur à un endroit",
            "setCursor(25, 50)",
            serialize(null)
        );

        $this->title('Polices', 1);
        $this->rowCols(
            "Obtenir la liste des polices disponibles",
            "getFonts",
            serialize($this->getFonts())
        );
        $this->rowCols(
            "Obtenir les informations sur la police courante",
            "getFontProperty",
            serialize($this->getFontProperty())
        );
        $this->rowCols(
            "",
            "getFontProperty('family')",
            serialize($this->getFontProperty('family'))
        );
        $this->rowCols(
            "Vérifier la présence d'une police",
            "hasFont('helvetica')",
            serialize($this->hasFont('helvetica'))
        );
        $this->rowCols(
            "Vérifie si le style courant est souligné",
            "isUnderline",
            serialize($this->isUnderline())
        );

        $this->title('Marges', 1);
        $this->rowCols(
            "Obtenir toutes les marges ou l'une d'entre elle",
            "getMargin",
            serialize($this->getMargin())
        );
        $this->rowCols(
            "",
            "getMargin('r')",
            serialize($this->getMargin('r'))
        );
        $this->rowCols(
            "Définir une marge",
            "setMargin('left', 10)",
            serialize(null)
        );

        $this->title('Méta-données', 1);
        $this->rowCols(
            "Obtenir toutes les méta-données ou l'une d'entre elle",
            "getMetadata",
            serialize($this->getMetadata())
        );
        $this->rowCols(
            "",
            "getMetadata('Title')",
            serialize($this->getMetadata('Title'))
        );

        $this->title('Pages', 1);
        $this->rowCols(
            "Obtenir le nombre total de pages",
            "getTotalPages",
            serialize($this->getTotalPages())
        );
        $this->rowCols(
            "Obtenir l'orientation de la page courante'",
            "getOrientation",
            serialize($this->getOrientation())
        );
        $this->rowCols(
            "Position du saut de page",
            "getPageBreak",
            serialize($this->getPageBreak())
        );

        $this->title('Couleur', 1);
        $this->rowCols(
            "Obtenir les valeurs RGB d'une couleur au format héxadécimal",
            "hexaToRgb('#CCCCCC')",
            serialize($this->hexaToRgb('#CCCCCC'))
        );

        $this->title('Ligne', 1);
        $this->rowCols(
            "Imprime une ligne sur toute la largeur du corps",
            "addLine",
            serialize(null)
        );
        $this->rowCols(
            "Imprime une ligne sur toute la largeur du corps et saute deux lignes",
            "addLine(2)",
            serialize(null)
        );

        $this->title('Sauvegarder', 1);
        $this->rowCols(
            "Enregistrer le document PDF sur le serveur",
            "toFile('path/to/file/filename')",
            serialize(null)
        );
    }

    public function printBehaviors()
    {
        parent::AddPage();
        $this->title('Comportements');
        $message = "Les 'comportements' (behaviors) permettent à un document PDF, de disposer de fonctionnalités supplémentaires. L'ajout de signets, l'utilisation d'une palette de couleurs... Ils sont représentés par des traits PHP à utiliser en fonction des besoins.";
        $this->alert($message);
        $this->printTraitBookmark();
        $this->printTraitColors();
        $this->printTraitData();
        $this->printTraitIcons();
        $this->printTraitPsr7();
        $this->printTraitRowsCols();
        $this->printTraitRotate();
        $this->printTraitEan13();
        $this->printTraitEllipse();
        $this->printTraitRoundedRect();
        $this->printTraitGrid();
        //$this->printTraitJoinedFile();
    }

    private function infoClass($className)
    {
        $this->SetFont(null, 'I', 10, ['color' => 'black', 'fillColor' => 'graylight']);
        $label = 'Classe : ';
        parent::Cell($this->GetStringWidth(utf8_decode($label)), 5, utf8_decode($label));
        $this->SetFont('courier', 'B', 11, ['color' => 'red']);
        parent::MultiCell(0, 5, $className, 0, 'L');

        $this->SetFont(null, 'I', 10, ['color' => 'black', 'fillColor' => 'graylight']);
        $label = 'Méthode(s) publique(s) : ';
        parent::Cell($this->GetStringWidth(utf8_decode($label)), 5, utf8_decode($label));
        $this->SetFont('courier', 'B', 11, ['color' => 'red']);
        parent::MultiCell(0, 5, implode(', ', get_class_methods($className)), 0, 'L');
        $this->Ln();
    }
}

class AllPdfBehaviorsTest extends BaseTestCase
{
    /**
     * @var AllBehaviorsPdfDoc
     */
    private $pdf;

    public function setup()
    {
        $this->pdf = $this->makePdf();
    }

    /**
     * @return AllBehaviorsPdfDoc
     */
    public function makePdf()
    {
        return new AllBehaviorsPdfDoc();
    }

    public function testInstance()
    {
        $this->ekoTitre('PDF - All Behaviors');
        $this->assertInstanceOf(
            AbstractPDF::class
            , $this->pdf
            , $this->getMessage("L'objet n'appartient pas à l'instance attendue")
        );
    }

    public function testToFile()
    {
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . 'res' . DIRECTORY_SEPARATOR . 'Report_Tests_AllPdfBehaviors';

        $this->pdf->AddPage();
        $this->pdf->printDebugClass();
        $this->pdf->printNativesProperties();
        $this->pdf->printNativesMethods();
        $this->pdf->printFunctionalsMethods();

        // Traits
        $this->pdf->printBehaviors();

        // Caractères de la police
        $this->pdf->printCharsFont('zapfdingbats');

        // Palette de couleurs
        $this->pdf->printDebugPalette();

        $this->pdf->toFile($fileName);
        $this->assertTrue(file_exists($fileName . '.pdf'));
        $this->ekoMsgInfo("\nLe fichier $fileName.pdf a été généré");
    }
}