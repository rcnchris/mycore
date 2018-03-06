<?php
/**
 * Fichier LayoutsPdfTrait.php du 25/02/2018
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
 * Class LayoutsPdfTrait
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
trait LayoutsPdfTrait
{

    /**
     * Imprime un titre selon les règles souhaitées
     *
     * @param string    $label        Titre
     * @param int|null  $level        Niveau du titre
     * @param bool|null $withBookmark Ajoute un bookmark pour le titre
     */
    public function title($label, $level = 0, $withBookmark = true)
    {
        $colorLevel = [
            ['color' => 'brown', 'size' => 12, 'style' => 'B', 'align' => 'L', 'fill' => false, 'heightLine' => 7],
            ['color' => 'black', 'size' => 10, 'style' => 'BI', 'align' => 'L', 'fill' => false, 'heightLine' => 7],
            ['color' => 'aloha', 'size' => 10, 'style' => 'B', 'align' => 'L', 'fill' => false, 'heightLine' => 7],
            ['color' => 'blueamalficoast', 'size' => 10, 'style' => 'B', 'align' => 'L', 'fill' => false, 'heightLine' => 7]
        ];
        $withBookmark ? $this->addBookmark($label, $level) : null;
        $this->SetFont(
            null,
            $colorLevel[$level]['style'],
            $colorLevel[$level]['size'],
            ['color' => $colorLevel[$level]['color']]
        );
        parent::MultiCell(
            0,
            $colorLevel[$level]['heightLine'],
            utf8_decode($label),
            $colorLevel[$level]['style'],
            $colorLevel[$level]['align'],
            $colorLevel[$level]['fill']
        );
        $this->SetFont();
    }

    /**
     * Imprime un bloc de code
     *
     * @param string|array $code Ligne(s) de code à imprimer
     */
    public function codeBloc($code)
    {
        $initFont = $this->FontFamily;
        $this->setToolColor('#CCCCCC', 'fill');
        $this->SetFont('courier');
        if (is_string($code)) {
            $code = [$code];
        }
        foreach ($code as $line) {
            parent::MultiCell(0, 10, $line, 0, 'L', true);
        }
        $this->SetFont($initFont);
    }

    /**
     * Imprime une alerte avec du texte
     *
     * @param string $info texte de l'alerte
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
        parent::MultiCell(0, 10, utf8_decode($info), 0, 'L', true);
        $this->setToolColor(0, 'fill');
        $this->Ln();
    }
}
