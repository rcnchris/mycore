<?php
/**
 * Fichier Psr7PdfTrait.php du 15/02/2018
 * Description : Fichier de la classe Psr7PdfTrait
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Rcnchris\Core\PDF
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF;

use Psr\Http\Message\ResponseInterface;

trait Psr7PdfTrait
{

    /**
     * Voir le document dans le navigateur
     *
     * ### Exemple
     * - `$pdf->toView($response, 'ola');`
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string|null                         $fileName Nom du fichier PDF à générer
     * @param bool|null                           $isUtf8   Le contenu est en utf-8
     *
     * @return string|static
     */
    public function toView(ResponseInterface $response, $fileName = 'doc', $isUtf8 = true)
    {
        $fileName = trim($fileName) . '.pdf';
        $body = $response->getBody();
        $body->write((string)$this);
        $newResponse = $response
            ->withStatus(200)
            ->withHeader('Content-type', 'application/pdf')
            ->withAddedHeader('Content-Disposition', 'inline; filename="' . $fileName . '"')
            ->withAddedHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->withAddedHeader('Pragma', 'public')
            ->withBody($body);
        return $newResponse;
    }

    /**
     * Télécharger le document PDF par le navigateur
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string                              $fileName
     *
     * @return static
     */
    public function toDownload(ResponseInterface $response, $fileName = 'doc')
    {
        $fileName = trim($fileName) . '.pdf';
        $body = $response->getBody();
        $body->write((string)$this);
        $newResponse = $response
            ->withStatus(200)
            ->withHeader('Content-type', 'application/x-download')
            ->withAddedHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->withAddedHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->withAddedHeader('Pragma', 'public')
            ->withBody($body);
        return $newResponse;
    }
}
