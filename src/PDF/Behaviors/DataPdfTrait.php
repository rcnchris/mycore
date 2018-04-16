<?php
/**
 * Fichier DataPdfTrait.php du 15/02/2018
 * Description : Fichier de la classe DataPdfTrait
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
 * Trait DataPdfTrait
 * <ul>
 * <li>Ajout de données dans un document PDF</li>
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
trait DataPdfTrait
{

    /**
     * Données du document
     *
     * @var mixed
     */
    private $data;


    /**
     * Définir les données du document
     *
     * @param mixed $data Données du document
     *
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Obtenir les données du document ou l'une d'entre elle
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getData($key = null)
    {
        if (is_null($key)) {
            return $this->data;
        }
        if (is_array($this->data) && array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        if (is_object($this->data) && property_exists($this->data, $key)) {
            return $this->data->$key;
        }
        return false;
    }

    /**
     * Vérifie la présence d'une clé
     *
     * @param string|int $key Nom ou indice de la clé cherchée
     *
     * @return bool
     */
    public function hasKey($key)
    {
        $exists = false;
        if ($this->isArray()) {
            $exists = array_key_exists($key, $this->data);
        } elseif ($this->isObject()) {
            $exists = property_exists($this->data, $key);
        }
        return $exists;
    }

    /**
     * Vérifier la présence d'une valeur dans les données
     *
     * @param mixed $value Valeur à chercher
     *
     * @return bool
     */
    public function hasValue($value)
    {
        $exists = false;
        if ($this->isArray()) {
            $exists = in_array($value, $this->data);
        } elseif ($this->isObject()) {
            $exists = in_array($value, get_object_vars($this->data));
        }
        return $exists;
    }

    /**
     * Vérifie si les données sont stockées dans un objet
     *
     * @return bool
     */
    private function isObject()
    {
        return is_object($this->data);
    }


    /**
     * Vérifie si les données sont stockées dans un tableau
     *
     * @return bool
     */
    private function isArray()
    {
        return is_array($this->data);
    }

    /**
     * Imprime les informations du trait
     */
    public function infosDataPdfTrait()
    {
        $this->setData(['name' => 'Mathis', 'year' => 2007]);
        $this->AddPage();
        $this->title('Données', 1);
        $this->alert("Permet de disposer de données au sein du document.");
        $this->printInfoClass(DataPdfTrait::class);

        $this->title('setData', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode('Définir les données du document'));
        $this->codeBloc("\$pdf->setData(['name' => 'Mathis', 'year' => 2007]);");
        $this->Ln();

        $this->title('getData', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode('Obtenir la liste de toutes les données'));
        $this->codeBloc("\$pdf->getData();");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->SetFont();
        $this->codeBloc(serialize($this->getData()));

        $this->MultiCell(0, 10, utf8_decode('Obtenir la valeur d\'une clé'));
        $this->codeBloc("\$pdf->getData('name');");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->SetFont();
        $this->codeBloc(serialize($this->getData('name')));
        $this->Ln();

        $this->title('hasKey', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Vérifier la présence d'une clé"));
        $this->codeBloc("\$pdf->hasKey('name');");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->SetFont();
        $this->codeBloc(serialize($this->hasKey('name')));
        $this->Ln();

        $this->title('hasValue', 2);
        $this->addLine();
        $this->MultiCell(0, 10, utf8_decode("Vérifier la présence d'une valeur"));
        $this->codeBloc("\$pdf->hasValue(2007);");
        $this->SetFont(null, 'BI');
        $this->MultiCell(0, 10, "Retourne :");
        $this->SetFont();
        $this->codeBloc(serialize($this->hasValue(2007)));
        $this->Ln();
    }
}
