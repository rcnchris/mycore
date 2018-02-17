<?php
/**
 * Fichier DebugPDF.php du 14/02/2018
 * Description : Fichier de la classe DebugPDF
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF;

/**
 * Class DebugPDF
 * <ul>
 * <li>Génère un document PDF de debug et aide d'utilisation de cette classe</li>
 * <li>Illustre la manière dont il faut utiliser la classe <code>MyFPDF</code></li>
 * </ul>
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class DebugPDF extends AbstractPDF
{

    use RowPdfTrait, DataPdfTrait, IconsPdfTrait;

    /**
     * Ajoute le Header à toutes les pages
     *
     * @throws \Exception
     */
    public function Header()
    {
        // Titre
        parent::SetCreator('My Core');
        parent::SetAuthor('rcn');
        parent::SetTitle('Debug du ' . (new \DateTime())->format('d-m-Y H:i:s'));
        parent::SetSubject('Debug Abstract PDF');
        $this->SetFont($this->getFont(), 'B', 14);
        $this->setColor('graylight', 'fill');
        $this->Cell(0, 10, $this->getMetadata('Title'), 0, 1, 'C', true);
        $this->addLine();
        $this->SetFont();
    }

    /**
     * Ajoute le footer à toutes les pages
     */
    public function Footer()
    {
        $this->SetY($this->getMargin('b') * -1);
        //$this->setColor('black');
        $this->setColor('black', 'draw');
        $this->addLine();
        $this->SetFont($this->getFont(), 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' sur ' . '{nb}', 0, 0, 'C');
    }

    /**
     * Ajoute une page de Debug
     *
     * @return $this
     */
    public function addDebug()
    {
        $this->setColsWidth(80, 50, $this->getBodySize('width') - (130));
        $this->setColsTextColors('black', 'redcarminepink');

        $this->printDebugClass();
        $this->printDebugProperties();
        $this->printDebugMethods();
        $this->printDebugColors();

        // Tous les caractères d'une police
        $this->printCharsFont('zapfdingbats');
        //$this->printCharsFont('courier');
        //$this->printCharsFont('symbol');
        //$this->printCharsFont('helvetica');

        return $this;
    }

    private function printDebugMethods()
    {
        parent::AddPage();
        $this->addBookmark('Méthodes', 0);
        $this->SetFont($this->getFont(), 'B', 14, 'bluedayflower');
        parent::MultiCell(0, 10, utf8_decode('Méthodes'), 'B', 'L');

        $this->addBookmark('Méthodes natives', 1);
        $this->SetFont($this->getFont(), 'BI', 12, 'brown');
        parent::MultiCell(0, 5, utf8_decode('Natives'), 'B', 'L', true);
        $this->SetFont();

        $this->rowCols('Renvoie la hauteur de la page courante', 'GetPageHeight', serialize($this->GetPageHeight()));
        $this->rowCols('Renvoie la largeur de la page courante', 'GetPageWidth', serialize($this->GetPageWidth()));
        $this->rowCols(
            "Renvoie la longueur d'une chaîne en unité utilisateur. Une police doit être sélectionnée. La chaîne testée : 'ola les gens'",
            'GetStringWidth',
            serialize($this->GetStringWidth('ola les gens'))
        );
        $this->rowCols("Renvoie l'abscisse de la position courante.", 'GetX', serialize($this->GetX()));
        $this->rowCols("Renvoie l'ordonnée de la position courante.", 'GetY', serialize($this->GetY()));
        $this->rowCols("Renvoie le numéro de page courant.", 'PageNo', serialize($this->PageNo()));

        $this->addBookmark('Méthodes fonctionnelles', 1);
        $this->SetFont($this->getFont(), 'BI', 12, 'brown');
        parent::MultiCell(0, 5, utf8_decode('Fonctionnelles'), 'B', 'L', true);
        $this->SetFont();

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
        $this->rowCols(
            "Obtenir la couleur d'un type d'écriture ('text' par défaut).",
            "getToolColor('fill')",
            serialize($this->getToolColor('fill'))
        );
        $this->rowCols(
            "Obtenir la liste des couleurs ou l'une d'entre elle. La recherche par code hexadécimale est gérée",
            "getColors('aloha')",
            serialize($this->getColors('aloha'))
        );
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
            "Obtenir les données du document",
            "getData",
            serialize($this->getData())
        );
        $this->rowCols(
            "Obtenir les informations sur la police courante",
            "getFont",
            serialize($this->getFont())
        );
        $this->rowCols(
            "",
            "getFont(null, true)",
            serialize($this->getFont(null, true))
        );
        $this->rowCols(
            "Obtenir la liste des polices disponibles",
            "getFonts",
            serialize($this->getFonts())
        );
        $this->rowCols(
            "Imprimer une icône",
            "printIcon('envelop')",
            $this->printIcon('envelop', null, 15)
        );
        $this->rowCols(
            "Obtenir toutes les marges ou l'une d'entre elle",
            "getMargin",
            serialize($this->getMargin())
        );
        $this->rowCols(
            "",
            "getMargin('right')",
            serialize($this->getMargin('r'))
        );
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
        $this->rowCols(
            "Obtenir le nombre total de pages",
            "getTotalPages",
            serialize($this->getTotalPages())
        );
        $this->rowCols(
            "Vérifie la validité d'un type d'outil",
            "hasTool('text')",
            serialize($this->hasTool('text'))
        );
        $this->rowCols(
            "Obtenir les valeurs RGB d'une couleur au format héxadécimal",
            "hexaToRgb('#CCCCCC')",
            serialize($this->hexaToRgb('#CCCCCC'))
        );
    }

    private function printDebugPages()
    {
        $this->addBookmark('Pages', 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Pages', 0, 'L', true);
        $this->SetFont();

        $this->rowCols("Current page number", "page", serialize($this->page));
        $this->rowCols('Default orientation', 'DefOrientation', serialize($this->DefOrientation));
        $this->rowCols('Current orientation', 'CurOrientation', serialize($this->CurOrientation));
        $this->rowCols('Current page rotation', 'CurRotation', serialize($this->CurOrientation));
        $this->rowCols('Current page size', 'CurPageSize', serialize($this->CurPageSize));
        $this->rowCols('Hauteur de la page', 'h', serialize($this->h));
        $this->rowCols('Largeur de la page', 'w', serialize($this->w));
        $this->rowCols('Standard page sizes', 'StdPageSizes', serialize($this->StdPageSizes));
        $this->rowCols('Default page sizes', 'DefPageSize', serialize($this->DefPageSize));
        $this->rowCols('Page-related data', 'PageInfo', serialize($this->PageInfo));
        $this->rowCols('Height of current page in point', 'hPt', serialize($this->hPt));
        $this->rowCols('Width of current page in point', 'wPt', serialize($this->wPt));
        $this->rowCols('Automatic page breaking', 'AutoPageBreak', serialize($this->AutoPageBreak));
        $this->rowCols('Threshold used to trigger page breaks', 'PageBreakTrigger', serialize($this->PageBreakTrigger));
        $this->rowCols('Alias for total number of pages', 'AliasNbPages', serialize($this->AliasNbPages));
    }

    private function printDebugMarges()
    {
        $this->addBookmark('Marges', 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Marges', 0, 'L', true);
        $this->SetFont();

        $this->rowCols("\t- Haut", 'tMargin', serialize($this->tMargin));
        $this->rowCols("\t- Bas", 'bMargin', serialize($this->bMargin));
        $this->rowCols("\t- Gauche", 'lMargin', serialize($this->lMargin));
        $this->rowCols("\t- Bas", 'rMargin', serialize($this->rMargin));
        $this->rowCols("\t- Cellule", 'cMargin', serialize($this->cMargin));
    }

    private function printDebugColors()
    {
        parent::AddPage();
        $this->addBookmark('Couleurs', 0);
        $this->SetFont($this->getFont(), 'B', 14, 'bluedayflower');
        $this->MultiCell(0, 10, utf8_decode('Couleurs disponibles ') . count($this->getColors()), 'B', 'L');
        $this->SetFont();

        $this->setColsFill(false, false, true);
        foreach ($this->getColors() as $name => $hexa) {
            $this->setColsFillColors('black', 'redcarminepink', $name);
            $this->rowCols($name, $hexa, '');
        }
        $this->SetFont();
        $this->setColsFill(false, false, false);
        $this->setColsFillColors('black', 'redcarminepink', 'black');
    }

    private function printDebugFonts()
    {
        $this->addBookmark('Polices', 0);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Polices', 0, 'L', true);
        $this->SetFont();

        $this->rowCols('Current font family', 'FontFamily', serialize($this->FontFamily));
        $this->rowCols('Current font style', 'FontStyle', serialize($this->FontStyle));
        $this->rowCols('Current font size in points', 'FontSizePt', serialize($this->FontSizePt));
        $this->rowCols('Current font size in user unit', 'FontSize', serialize($this->FontSize));
        //$this->rowCols('Current font info', 'CurrentFont', serialize($this->CurrentFont));
        $this->rowCols('Underlining flag', 'underline', serialize($this->underline));
        $this->rowCols('Scale factor  (number of points in user unit)', 'k', serialize($this->k));
        $this->rowCols('Path containing fonts', 'fontpath', serialize($this->fontpath));
        $this->rowCols('Array of core font names', 'CoreFonts', serialize($this->CoreFonts));
        //$this->rowCols('Array of used fonts', 'fonts', serialize($this->fonts));
        $this->rowCols('Word spacing', 'ws', serialize($this->ws));
    }

    private function printCharsFont($fontName)
    {
        parent::AddPage();
        $this->addBookmark("Caractères de la police $fontName", 0);
        $initFont = $this->getFont(null, true);
        $this->SetFont($this->getFont(), 'B', 12, 'bluedayflower');
        parent::MultiCell(0, 10, utf8_decode("Caractères de la police $fontName"), 'B');

        $this->SetFont($this->getFont(), '', 20);
        for ($i = 32; $i <= 255; $i++) {
            $this->SetFont($initFont['family'], $initFont['style'], 14);
            parent::Cell(12, 5.5, "$i : ");
            $this->SetFont($fontName);
            parent::Cell(0, 5.5, chr($i), 0, 1);
        }
        $this->SetFont($initFont['family'], $initFont['style'], $initFont['size']);
    }

    private function printDebugLignes()
    {
        $this->addBookmark("Lignes", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Lignes', 0, 'L', true);
        $this->SetFont();

        $this->rowCols('Line width in user unit', 'LineWidth', serialize($this->LineWidth));
    }

    private function printDebugCursor()
    {
        $this->addBookmark("Curseur", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Curseur', 0, 'L', true);
        $this->SetFont();

        $this->rowCols('Position X', 'x', serialize($this->x));
        $this->rowCols('Position Y', 'y', serialize($this->y));
    }

    private function printDebugCell()
    {
        $this->addBookmark("Cellule", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Cellule', 0, 'L', true);
        $this->SetFont();

        $this->rowCols('Height of last printed cell', 'lasth', serialize($this->lasth));
    }

    private function printDebugDraw()
    {
        $this->addBookmark("Trait (draw)", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Trait (draw)', 0, 'L', true);
        $this->SetFont();

        $this->rowCols('Commands for drawing color', 'DrawColor', serialize($this->DrawColor));
    }

    private function printDebugFill()
    {
        $this->addBookmark("Remplissage (fill)", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Remplissage (fill)', 0, 'L', true);
        $this->SetFont();

        $this->rowCols('Commands for filling color', 'FillColor', serialize($this->FillColor));
    }

    private function printDebugText()
    {
        $this->addBookmark("Texte (text)", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Texte', 0, 'L', true);
        $this->SetFont();

        $this->rowCols('Commands for text color', 'TextColor', serialize($this->TextColor));
    }

    private function printDebugColor()
    {
        $this->addBookmark("Couleur", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Couleurs', 0, 'L', true);
        $this->SetFont();

        $this->rowCols(
            'Indicates whether fill and text colors are different',
            'ColorFlag',
            serialize($this->ColorFlag)
        );
        $this->rowCols('Indicates whether alpha channel is used', 'WithAlpha', serialize($this->WithAlpha));
    }

    private function printDebugImage()
    {
        $this->addBookmark("Images", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Images', 0, 'L', true);
        $this->SetFont();
        $this->rowCols('Array of used images', 'images', serialize($this->images));
    }

    private function printDebugLinks()
    {
        $this->addBookmark("Liens", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Liens', 0, 'L', true);
        $this->SetFont();

        $this->rowCols('Array of links in pages', 'PageLinks', serialize($this->PageLinks));
        $this->rowCols('Array of internal links', 'links', serialize($this->links));
    }

    private function printDebugHeader()
    {
        $this->addBookmark("Header", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Header', 0, 'L', true);
        $this->SetFont();
        $this->rowCols('Flag set when processing header', 'InHeader', serialize($this->InHeader));
    }

    private function printDebugFooter()
    {
        $this->addBookmark("Footer", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Footer', 0, 'L', true);
        $this->SetFont();
        $this->rowCols('Flag set when processing footer', 'InFooter', serialize($this->InFooter));
    }

    private function printDebugDivers()
    {
        $this->addBookmark("Divers", 2);
        $this->SetFont($this->getFont(), 'B');
        parent::MultiCell(0, 5, 'Divers', 0, 'L', true);
        $this->SetFont($this->getFont());

        $this->rowCols('Current object number', 'n', serialize($this->n));
        $this->rowCols('Array of object offsets', 'offsets', serialize($this->offsets));
        $this->rowCols('Current document state', 'state', serialize($this->state));
        $this->rowCols('Compression flag', 'compress', serialize($this->compress));
        $this->rowCols('Array of encodings', 'encodings', serialize($this->encodings));
        $this->rowCols('Array of ToUnicode CMaps', 'cmaps', serialize($this->cmaps));
        $this->rowCols('Zoom display mode', 'ZoomMode', serialize($this->ZoomMode));
        $this->rowCols('Layout display mode', 'LayoutMode', serialize($this->LayoutMode));
        $this->rowCols('PDF version number', 'PDFVersion', serialize($this->PDFVersion));
    }

    private function printDebugProperties()
    {
        parent::AddPage();
        $this->addBookmark('Propriétés', 0);
        $this->SetFont($this->getFont(), 'B', 12, 'bluedayflower', false, 'graylight');
        parent::MultiCell(0, 10, utf8_decode("Propriétés"), 'B');

        $this->addBookmark('Propriétés natives', 1);
        $this->SetFont($this->getFont(), 'BI', 12, 'brown');
        parent::MultiCell(0, 5, utf8_decode('Natives'), 'B', 'L', true);
        $this->SetFont();

        $this->printDebugPages();
        $this->printDebugMarges();
        $this->printDebugCursor();
        $this->printDebugLignes();
        $this->printDebugCell();
        $this->printDebugFonts();
        $this->printDebugText();
        $this->printDebugDraw();
        $this->printDebugFill();
        $this->printDebugColor();
        $this->printDebugImage();
        $this->printDebugLinks();
        $this->printDebugHeader();
        $this->printDebugFooter();
        $this->printDebugDivers();

        $this->addBookmark('Propriétés fonctionnelles', 1);
        $this->SetFont($this->getFont(), 'BI', 12, 'brown');
        parent::MultiCell(0, 5, utf8_decode('Fonctionnelles'), 'B', 'L', true);
        $this->SetFont();

        $this->rowCols('Options par défaut', 'defaultOptions', serialize($this->defaultOptions));
        $this->addBookmark("Options par défaut", 2);
    }

    private function printDebugClass()
    {
        $this->SetFont($this->getFont(), 'B', 12, 'bluedayflower');
        parent::MultiCell(0, 10, utf8_decode("Classe utilisée"), 'B');
        $this->addBookmark('Classe', 0);
        $this->SetFont();

        parent::MultiCell(0, 7, "Nom complet : " . get_class($this), 'B');
        parent::MultiCell(0, 7, utf8_decode("Parent : ") . get_parent_class(get_class($this)), 'B');

        $label = "Traits du parent : ";
        parent::Cell(parent::GetStringWidth($label), 7, $label);
        parent::MultiCell(0, 7, implode(', ', class_uses(get_parent_class(get_class($this)))));
        $this->addLine();

        parent::MultiCell(0, 7, utf8_decode("Le parent hérite de FPDF : ") . is_subclass_of(get_parent_class(get_class($this)), \FPDF::class), 'B');
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
}
