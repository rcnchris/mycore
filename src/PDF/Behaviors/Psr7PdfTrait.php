<?php
/**
 * Fichier Psr7PdfTrait.php du 15/02/2018
 * Description : Fichier de la classe Psr7PdfTrait
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Rcnchris\Core\PDF\Behaviors
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\PDF\Behaviors;

use Psr\Http\Message\ResponseInterface;

/**
 * Trait Psr7PdfTrait
 * <ul>
 * <li>Utilisation de la norme PSR7 pour produire
 * la visualisation dans le navigateur et le téléchargement de document PDF</li>
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

    /**
     * Imprime les informations du trait
     */
    public function infosPsr7PdfTrait()
    {
        $this->AddPage();
        $this->title('PSR7', 1);
        $this->alert(
            "Permet de visualiser et télécharger le document PDF via le navigateur en respectant la norme PSR7."
        );
        $this->printInfoClass(Psr7PdfTrait::class);

        $this->title('toView', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode('Voir le document dans le navigateur.'));
        $this->codeBloc("\$pdf->toView(response);");
        $this->Ln();

        $this->title('toDownload', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode('Voir le document dans le navigateur.'));
        $this->codeBloc("\$pdf->toDownload(response, 'doc');");
    }
}
