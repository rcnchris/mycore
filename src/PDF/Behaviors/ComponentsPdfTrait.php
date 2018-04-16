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
            'fill' => false,
            'fillColor' => '#CCCCCC',
            'drawColor' => '#CCCCCC',
        ],
        [
            'fontFamily' => 'helvetica',
            'fontStyle' => 'B',
            'fontSize' => 20,
            'heightline' => 12,
            'border' => 'B',
            'align' => 'L',
            'fill' => false,
            'fillColor' => '#CCCCCC',
            'drawColor' => '#CCCCCC',
        ],
        [
            'fontFamily' => 'helvetica',
            'fontStyle' => 'I',
            'fontSize' => 16,
            'heightline' => 10,
            'border' => 0,
            'align' => 'L',
            'fill' => false,
            'fillColor' => '#CCCCCC',
            'drawColor' => '#CCCCCC',
        ],
    ];

    /**
     * Imprime un titre selon un niveau
     *
     * @param string   $label Texte du titre
     * @param int|null $level Niveau du titre
     */
    public function title($label, $level = 0)
    {
        $initFont = $this->getFontProperty();
        $this->SetFont(
            $this->getTitleTemplates($level, 'fontFamily'),
            $this->getTitleTemplates($level, 'fontStyle'),
            $this->getTitleTemplates($level, 'fontSize')
        );
        $this->MultiCell(
            0,
            $this->getTitleTemplates($level, 'heightline'),
            utf8_decode($label),
            $this->getTitleTemplates($level, 'border'),
            $this->getTitleTemplates($level, 'align'),
            $this->getTitleTemplates($level, 'fill')
        );
        $this->SetFont($initFont['family'], $initFont['style'], $initFont['size']);
    }

    /**
     * Imprime un bloc de code
     *
     * @param string|array $code  Ligne(s) de code à imprimer
     * @param int|null     $width Largeur du bloc
     */
    public function codeBloc($code, $width = 0)
    {
        $initFont = $this->getFontProperty();
        $this->setToolColor('#CCCCCC', 'fill');
        $this->SetFont('courier');
        if (is_string($code)) {
            $code = [$code];
        }
        foreach ($code as $line) {
            $this->MultiCell($width, $this->getOptions('write')['heightLine'], $line, 0, 'L', true);
        }
        $this->SetFont($initFont['family'], $initFont['style'], $initFont['size']);
    }

    /**
     * Imprime une alerte avec du texte
     *
     * @param string      $info    texte de l'alerte
     * @param string|null $context Contexte de l'alerte
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
        $this->MultiCell(0, 10, utf8_decode($info), 0, 'L', true);
        $this->setToolColor(0, 'fill');
        $this->Ln();
    }

    /**
     * Imprime le nom de la classe demandée et la liste de ses méthodes publiques
     *
     * @param string $className Nom d'une classe
     */
    public function printInfoClass($className)
    {
        $this->SetFont(null, 'I', 10, ['color' => '#000000', 'fillColor' => '#CCCCCC']);
        $label = 'Nom complet de la classe : ';
        $this->Cell($this->GetStringWidth($label), 5, $label);
        $this->SetFont('courier', 'B', 11, ['color' => '#e74c3c']);
        $this->MultiCell(0, 5, $className, 0, 'L');
        $this->Ln();

        $this->SetFont(null, 'I', 10, ['color' => '#000000', 'fillColor' => '#CCCCCC']);
        $label = 'Méthodes publiques : ';
        $this->Cell($this->GetStringWidth(utf8_decode($label)), 5, utf8_decode($label));
        $this->SetFont('courier', 'B', 11, ['color' => '#e74c3c']);
        $this->MultiCell(0, 5, implode(', ', get_class_methods($className)), 0, 'L');
        $this->Ln();
        $this->SetFont();
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
    protected function getTitleTemplates($level, $key = null)
    {
        $level = intval($level);
        $template = null;
        if (array_key_exists($level, $this->titleTemplates)) {
            $template = $this->titleTemplates[$level];
        }
        if (is_null($key)) {
            return $template;
        } elseif (array_key_exists($key, $this->titleTemplates[$level])) {
            return $this->titleTemplates[$level][$key];
        }
        return false;
    }

    /**
     * Définir un de nouveaux templates pour les titres
     *
     * @param array $titleTemplates Nouveaux templates
     *
     * @return void
     */
    public function setTitleTemplates(array $titleTemplates = [])
    {
        if (!empty($titleTemplates)) {
            $this->titleTemplates = $titleTemplates;
        }
    }
}
