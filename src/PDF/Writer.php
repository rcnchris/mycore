<?php
/**
 * Fichier Writer.php du 16/04/2018
 * Description : Fichier de la classe Writer
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

use Rcnchris\Core\Tools\Collection;

/**
 * Class Writer
 * <ul>
 * <li>Facilite l'écriture de contenu dans un document PDF.</li>
 * </ul>
 *
 * @category PDF
 *
 * @package  Rcnchris\Core\PDF
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class Writer
{

    /**
     * Document PDF
     *
     * @var \Rcnchris\Core\PDF\AbstractPDF
     */
    private $pdf;

    /**
     * Options par défaut pour l'écriture
     *
     * @var array
     */
    private $defaultOptions = [
        'heightline' => 10,
        'width' => 0,
        'lnAfter' => 0,
        'border' => 0,
        'align' => 'L',
        'fill' => false,
        'link' => '',
        'keys' => []
    ];

    /**
     * Options d'écritures dans le document
     *
     * @var \Rcnchris\Core\Tools\Collection
     */
    private $writingOptions;

    /**
     * Constructeur
     *
     * @param \Rcnchris\Core\PDF\AbstractPDF|null $pdf     Document PDF
     * @param array|null                          $options Options d'écritures dans le document
     */
    public function __construct(AbstractPDF $pdf = null, array $options = [])
    {
        $this->pdf = is_null($pdf) ? new AbstractPDF() : $pdf;
        $this->writingOptions = new Collection(
            array_merge($this->defaultOptions, $options),
            "Options d'écriture du Writer"
        );
    }

    /**
     * Ecrit du contenu dans le document PDF de l'instance ou celui passé en paramètre
     *
     * ### Exemple
     * - `$writer->write('Ola les gens');`
     *
     * @param mixed                          $content Contenu à écrire dans le document
     * @param array|null                     $options
     * @param \Rcnchris\Core\PDF\AbstractPDF $pdf     Document PDF cible si différent de celui de l'instance
     *
     * @return \Rcnchris\Core\PDF\AbstractPDF
     * @throws \Exception
     */
    public function write($content, array $options = [], AbstractPDF $pdf = null)
    {
        if (is_null($pdf)) {
            $pdf = $this->pdf;
        }

        $this->writingOptions->merge($options, true);

        if ($pdf->getTotalPages() === 0) {
            $pdf->AddPage();
        }

        if (is_string($content)) {

            $this->pdf->Write(
                $this->writingOptions->get('heightline'),
                utf8_decode($content),
                $this->writingOptions->get('link')
            );

        } elseif (is_array($content) && !empty($content)) {

            foreach ($content as $k => $item) {
                $this->pdf->Cell(
                    $this->writingOptions->get('width'),
                    $this->writingOptions->get('heightline'),
                    utf8_decode($item),
                    $this->writingOptions->get('border'),
                    $this->writingOptions->get('lnAfter'),
                    $this->writingOptions->get('align'),
                    $this->writingOptions->get('fill'),
                    $this->writingOptions->get('link')
                );
            }

        } elseif (is_object($content)) {

            foreach ($this->writingOptions['keys'] as $key) {
                $this->pdf->Cell(
                    $this->writingOptions->get('width'),
                    $this->writingOptions->get('heightline'),
                    utf8_decode($key . ' : ' . $content->$key),
                    $this->writingOptions->get('border'),
                    $this->writingOptions->get('lnAfter'),
                    $this->writingOptions->get('align'),
                    $this->writingOptions->get('fill'),
                    $this->writingOptions->get('link')
                );
            }
        }

        return $pdf;
    }
}
