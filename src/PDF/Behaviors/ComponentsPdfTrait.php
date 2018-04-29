<?php
/**
 * Fichier ComponentsPdfTrait.php du 25/02/2018
 * Description : Fichier de la classe LayoutsPdfTrait
 *
 * PHP version 5
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF\Behaviors;

/**
 * Trait ComponentsPdfTrait
 * <ul>
 * <li>Permet l'utilisation de composants imprimables</li>
 * </ul>
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF\Behaviors
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
trait ComponentsPdfTrait
{

    /**
     * Templates des titres
     *
     * @var array
     */
    private $titleTemplates = [
        [
            'fontFamily' => 'helvetica',
            'fontStyle' => 'B',
            'fontSize' => 24,
            'heightline' => 16,
            'border' => 'B',
            'align' => 'C',
            'underline' => false,
            'fill' => false,
            'fillColor' => '#CCCCCC',
            'drawColor' => '#CCCCCC',
            'textColor' => '#000000'
        ],
        [
            'fontFamily' => 'helvetica',
            'fontStyle' => 'B',
            'fontSize' => 20,
            'heightline' => 12,
            'border' => 'B',
            'align' => 'L',
            'underline' => false,
            'fill' => false,
            'fillColor' => '#CCCCCC',
            'drawColor' => '#CCCCCC',
            'textColor' => '#000000'
        ],
        [
            'fontFamily' => 'helvetica',
            'fontStyle' => 'I',
            'fontSize' => 16,
            'heightline' => 10,
            'border' => 0,
            'align' => 'L',
            'underline' => false,
            'fill' => false,
            'fillColor' => '#CCCCCC',
            'drawColor' => '#CCCCCC',
            'textColor' => '#000000'
        ],
        [
            'fontFamily' => 'helvetica',
            'fontStyle' => '',
            'fontSize' => 12,
            'heightline' => 8,
            'border' => 0,
            'align' => 'L',
            'underline' => false,
            'fill' => false,
            'fillColor' => '#CCCCCC',
            'drawColor' => '#CCCCCC',
            'textColor' => '#000000'
        ],
    ];

    /**
     * Encours
     * Permet d'utiliser des templates nommés
     *
     * @var array
     */
    private $templates = [
        'default' => [
            'fontFamily' => 'helvetica',
            'fontStyle' => '',
            'fontSize' => 8,
            'heightline' => 8,
            'border' => 0,
            'align' => 'L',
            'underline' => false,
            'fill' => false,
            'fillColor' => '#ecf0f1',
            'drawColor' => '#7f8c8d',
            'textColor' => '#000000'
        ],
        'title' => [
            'h1' => [
                'fontFamily' => 'helvetica',
                'fontStyle' => 'B',
                'fontSize' => 24,
                'heightline' => 16,
                'border' => 'B',
                'align' => 'C',
                'underline' => false,
                'fill' => false,
                'fillColor' => '#CCCCCC',
                'drawColor' => '#CCCCCC',
                'textColor' => '#000000'
            ],
            'h2' => [
                'fontFamily' => 'helvetica',
                'fontStyle' => 'B',
                'fontSize' => 20,
                'heightline' => 12,
                'border' => 'B',
                'align' => 'L',
                'underline' => false,
                'fill' => false,
                'fillColor' => '#CCCCCC',
                'drawColor' => '#CCCCCC',
                'textColor' => '#000000'
            ]
        ],
        'code' => [
            'fontFamily' => 'courier',
            'fontStyle' => '',
            'fontSize' => 8,
            'heightline' => 8,
            'border' => 0,
            'align' => 'L',
            'underline' => false,
            'fill' => true,
            'fillColor' => '#CCCCCC',
            'drawColor' => '#CCCCCC',
            'textColor' => '#000000'
        ],
        'alert' => [],
    ];

    /**
     * Imprime une alerte avec du texte
     *
     * ### Exemple
     * - `$pdf->alert('Méfi !', 'warning');`
     *
     * @param string      $info    texte de l'alerte
     * @param string|null $context Contexte de l'alerte
     *
     * @return $this
     */
    public function alert($info, $context = 'info')
    {
        $contexts = [
            'info' => '#3498db',
            'success' => '#2ecc71',
            'warning' => '#e67e22',
            'error' => '#e74c3c'
        ];
        $this->setToolColor($contexts[$context], 'fill');
        $this->MultiCell(0, $this->options->get('heightline'), utf8_decode($info), 0, 'L', true);
        $this->setToolColor(0, 'fill');
        $this->Ln();
        return $this;
    }

    /**
     * Imprime un titre selon un niveau
     *
     * @param string   $label Texte du titre
     * @param int|null $level Niveau du titre
     *
     * @return $this
     */
    public function title($label, $level = 0)
    {
        $this->SetFont(
            $this->getTitleTemplates($level, 'fontFamily'),
            $this->getTitleTemplates($level, 'fontStyle'),
            $this->getTitleTemplates($level, 'fontSize'),
            [
                'textColor' => $this->getTitleTemplates($level, 'textColor'),
                'fillColor' => $this->getTitleTemplates($level, 'fillColor'),
                'drawColor' => $this->getTitleTemplates($level, 'drawColor'),
                'underline' => $this->getTitleTemplates($level, 'underline')
            ]
        );
        $this->MultiCell(
            0,
            $this->getTitleTemplates($level, 'heightline'),
            utf8_decode($label),
            $this->getTitleTemplates($level, 'border'),
            $this->getTitleTemplates($level, 'align'),
            $this->getTitleTemplates($level, 'fill')
        );
        $this->SetFont();

        return $this;
    }

    /**
     * Imprime un bloc de code
     *
     * @param string|array $code  Ligne(s) de code à imprimer
     * @param int|null     $width Largeur du bloc
     *
     * @return $this
     */
    public function codeBloc($code, $width = 0)
    {
        $this->setToolColor('#CCCCCC', 'fill');
        $this->SetFont('courier');
        if (is_string($code)) {
            $code = [$code];
        }
        foreach ($code as $line) {
            $this->MultiCell($width, $this->options->get('heightline'), $line, 0, 'L', true);
        }
        $this->SetFont();

        return $this;
    }

    /**
     * Imprime un lien en bleu par défaut
     *
     * @param string      $url   URL du lien
     * @param string      $label Nom du lien
     * @param string|null $color Code héxadécimal de la couleur du lien
     *
     * @return $this
     */
    public function printLink($url, $label, $color = null)
    {
        if (is_null($color)) {
            $color = '#2980b9';
        }
        $this->SetFont(null, 'B', 8, ['textColor' => $color]);
        $this->Cell(0, 10, utf8_decode($label), 0, 1, 'L', false, $url);

        return $this;
    }

    /**
     * Imprime une page de description d'une classe
     *
     * @param object|string $object       Instance de l'objet ou le nom de sa classe
     * @param bool|null     $addPage      Ajoute une page
     * @param bool|null     $withTitle    Avec le titre principal
     * @param bool          $withBookmark Ajoute un bookmark pour le titre
     *
     * @return $this
     * @throws \Exception
     */
    public function printInfoClass($object, $addPage = true, $withTitle = true, $withBookmark = false)
    {
        if ($addPage || $this->getTotalPages() === 0) {
            $this->AddPage();
        }

        $className = null;
        if (is_object($object)) {
            $className = get_class($object);
        } elseif (is_string($object)) {
            $className = $object;
        }
        if (!class_exists($className)) {
            throw new \Exception("La classe $className est introuvable !");
        }

        $shortName = explode('\\', $className);
        $shortName = array_pop($shortName);
        $title = "Définition de la classe $shortName";
        if ($withBookmark) {
            $this->addBookmark($title, $this->getBookmarksMaxLevel());
        }
        if ($withTitle) {
            $this->title($title, 0);
        }
        $this->title('Nom de la classe', 2);
        $this->SetFont('courier', 'B', 10, ['textColor' => '#c0392b']);
        $this->MultiCell(0, 6, $className, 0, 'L');

        $this->title('Parent', 2);
        $this->SetFont('courier', 'B', 10, ['textColor' => '#c0392b']);
        $this->MultiCell(0, 6, get_parent_class($className), 0, 'L');

        $implements = class_implements($className);
        $this->title(count($implements) . ' classes implémentées', 2);
        $this->SetFont('courier', 'B', 10, ['textColor' => '#c0392b']);
        $this->MultiCell(0, 6, implode(', ', $implements), 0, 'L');

        $traits = class_uses($className);
        $this->title(count($traits) . ' traits utilisés', 2);
        $this->SetFont('courier', 'B', 10, ['textColor' => '#c0392b']);
        $this->MultiCell(0, 6, implode(', ', $traits), 0, 'L');

        $methods = get_class_methods($className);
        $title = count($methods) . " méthodes publiques";
        $this->title($title, 2);
        $this->SetFont('courier', '', 10, ['textColor' => '#000000']);
        $this->MultiCell(0, 7, implode(', ', $methods));
        $this->addLine();

        return $this;
    }

    /**
     * Imprime les propriétés du document courant
     *
     * @return $this
     */
    public function printDocumentProperties()
    {
        $this->AddPage();
        $title = 'Propriétés de ce document';
        $this->addBookmark($title, 1)->title($title, 0);

        $wCol = $this->getBodySize('width') / 2;
        $h = $this->options->get('heightline');

        $this->Cell($wCol, $h, 'Nombre de pages', 0, 0);
        $this->Cell($wCol, $h, $this->getTotalPages(), 0, 1);
        $this->addLine();

        $this->Cell($wCol, $h, utf8_decode('Saut de page à'), 0, 0);
        $this->Cell($wCol, $h, $this->PageBreakTrigger, 0, 1);
        $this->addLine();

        $this->Cell($wCol, $h, utf8_decode('Largeur du corps'), 0, 0);
        $this->Cell($wCol, $h, $this->getBodySize('width'), 0, 1);
        $this->addLine();

        $this->Cell($wCol, $h, utf8_decode('Longueur du corps'), 0, 0);
        $this->Cell($wCol, $h, $this->getBodySize('height'), 0, 1);
        $this->addLine();

        $this->Cell($wCol, $h, utf8_decode('Marges du haut'), 0, 0);
        $this->Cell($wCol, $h, $this->getMargin('top'), 0, 1);
        $this->addLine();

        $this->Cell($wCol, $h, utf8_decode('Marges de droite'), 0, 0);
        $this->Cell($wCol, $h, $this->getMargin('right'), 0, 1);
        $this->addLine();

        $this->Cell($wCol, $h, utf8_decode('Marges de gauche'), 0, 0);
        $this->Cell($wCol, $h, $this->getMargin('left'), 0, 1);
        $this->addLine();

        $this->Cell($wCol, $h, utf8_decode('Marges du bas'), 0, 0);
        $this->Cell($wCol, $h, $this->getMargin('bottom'), 0, 1);
        $this->addLine();

        $this->Cell($wCol, $h, utf8_decode('Méta-données'), 0, 0);


        $this->MultiCell($wCol, $h, serialize($this->getMetadata()), 0, 'L');
        $this->addLine();

        return $this;
    }

    /**
     * Obtenir le template d'un titre selon son niveau
     * ou la valeur d'une clé pour un niveau
     *
     * ### Exemple
     * - `$pdf->getTitleTemplates(0, 'border');`
     *
     * @param int         $level Niveau du titre
     * @param string|null $key   Clé de la valeur du template
     *
     * @return array|mixed
     */
    public function getTitleTemplates($level, $key = null)
    {
        $level = intval($level);
        $template = null;
        if (array_key_exists($level, $this->titleTemplates)) {
            $template = $this->titleTemplates[$level];
        }
        if (is_null($key)) {
            return is_null($template) ? false : $template;
        } elseif (array_key_exists($key, $this->titleTemplates[$level])) {
            return $this->titleTemplates[$level][$key];
        }
        return false;
    }

    /**
     * Définir de nouveaux templates pour les titres
     *
     * @param array $titleTemplates Nouveaux templates
     *
     * @return $this
     */
    public function setTitleTemplates(array $titleTemplates)
    {
        if (!empty($titleTemplates)) {
            $this->titleTemplates = $titleTemplates;
        }
        return $this;
    }

    /**
     * Génère une MultiCell avec une puce ou plusieurs
     *
     * @param double       $w       Largeur
     * @param double       $h       Hauteur
     * @param string       $blt     Puce
     * @param string|array $content Texte ou tableau de texte
     * @param int|null     $border  Bordure
     * @param string|null  $align   Alignement
     * @param bool|null    $fill    Fill ?
     *
     * @return $this
     */
    public function puce($w, $h, $blt, $content, $border = 0, $align = 'J', $fill = false)
    {
        // Get bullet width including margins
        $blt_width = $this->GetStringWidth($blt) + $this->cMargin * 2;

        // Save x
        $bak_x = $this->x;

        if (is_string($content)) {
            // Output bullet
            $this->Cell($blt_width, $h, $blt, 0, '', $fill);

            // Output text
            $this->MultiCell($w - $blt_width, $h, utf8_decode($content), $border, $align, $fill);
        } elseif (is_array($content)) {
            foreach ($content as $item) {
                //Output bullet
                $this->Cell($blt_width, $h, $blt, 0, '', $fill);

                //Output text
                $this->MultiCell($w - $blt_width, $h, $item, $border, $align, $fill);
            }
        }

        // Restore x
        $this->x = $bak_x;

        return $this;
    }
}
