<?php
/**
 * Fichier RandomItems.php du 27/01/2018
 * Description : Fichier de la classe RandomItems
 *
 * PHP version 5
 *
 * @category Données aléatoires
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Tools;

use Faker\Factory;

/**
 * Class RandomItems
 * <ul>
 * <li>Classe statique qui fournit des données aléatoires</li>
 * </ul>
 *
 * @category Données aléatoires
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since  Release: <0.1.0>
 */
class RandomItems
{
    /**
     * Instance
     *
     * @var self
     */
    private static $instance;

    /**
     * Données à retourner
     *
     * @var mixed
     */
    private static $datas = [];

    /**
     * Obtenir des dates
     *
     * ### Exemple
     * - `RandomItems::dates(3, 'Y-m-d');`
     *
     * @param int|null    $number Nombre d'items à retourner
     * @param string|null $format Format de la date à retourner
     *
     * @return array|\DateTime
     */
    public static function dates($number = 1, $format = 'd-m-Y')
    {
        $faker = self::getFaker();
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->dateTime->format($format);
        }
        return self::getResults();
    }

    /**
     * Obtenir des mots
     *
     * ### Exemple
     * - `RandomItems::words(3);`
     *
     * @param int|null $number Nombre d'items à retourner
     *
     * @return array|string
     */
    public static function words($number = 1)
    {
        $faker = self::getFaker();
        self::$datas = $faker->words($number);
        return self::getResults();
    }

    /**
     * Obtenir des phrases
     *
     * ### Exemple
     * - `RandomItems::sentences(3);`
     *
     * @param int|null $number Nombre d'items à retourner
     *
     * @return array|string
     */
    public static function sentences($number = 1)
    {
        $faker = self::getFaker();
        self::$datas = $faker->sentences($number);
        return self::getResults();
    }

    /**
     * Obtenir une instance (Singleton)
     *
     * ### Exemple
     * - `RandomItems::getInstance();`
     *
     * @return self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtenir des utilisateurs
     *
     * ### Exemple
     * - `RandomItems::users(3, 'fr_FR');`
     *
     * @param int|null    $number Nombre ditems à retourner
     * @param string|null $locale
     *
     * @return array
     */
    public static function users($number = 1, $locale = null)
    {
        $faker = self::getFaker($locale);
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = [
                'id' => $faker->uuid,
                'username' => $faker->userName,
                'email' => $faker->email,
                'name' => [
                    'firstName' => $faker->firstName,
                    'lastName' => $faker->lastName
                ],
                'birthday' => $faker->date(),
                'phone' => $faker->phoneNumber,
                'mobile' => $faker->phoneNumber,
                'created' => $faker->dateTime,
                'modified' => $faker->dateTime
            ];
        }
        return self::getResults();
    }

    /**
     * Obtenir des articles
     *
     * ### Exemple
     * - `RandomItems::posts(3, 'fr_FR');`
     *
     * @param int|null    $number Nombre ditems à retourner
     * @param string|null $locale
     *
     * @return array|mixed
     */
    public static function posts($number = 1, $locale = null)
    {
        $faker = self::getFaker($locale);
        for ($i = 0; $i < $number; $i++) {
            $date = date('Y-m-d H:i:s', $faker->unixTime('now'));
            self::$datas[] = [
                'id' => $faker->randomDigit,
                'title' => $faker->catchPhrase,
                'slug' => $faker->slug,
                'content' => $faker->text(300),
                'created_at' => $date,
                'updated_at' => $date,
                'published' => 1
            ];
        }
        return self::getResults();
    }

    /**
     * Obtenir des adresses
     *
     * ### Exemple
     * - `RandomItems::address(3, 'fr_FR');`
     *
     * @param int|null    $number Nombre ditems à retourner
     * @param string|null $locale
     *
     * @return array
     */
    public static function address($number = 1, $locale = null)
    {
        $faker = self::getFaker($locale);
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = [
                'street' => $faker->streetAddress,
                'code' => $faker->postcode,
                'city' => $faker->city,
                'country' => $faker->country
            ];
        }
        return self::getResults();
    }

    /**
     * Obtenir des pays
     *
     * ### Exemple
     * - `RandomItems::countries(3, 'fr_FR');`
     *
     * @param int|null    $number Nombre d'items à retourner
     * @param string|null $locale
     *
     * @return array|string
     */
    public static function countries($number = 1, $locale = null)
    {
        $faker = self::getFaker($locale);
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->country;
        }
        return self::getResults();
    }

    /**
     * Obtenir des codes de pays
     *
     * ### Exemple
     * - `RandomItems::countriesCode(3);`
     * - `RandomItems::countriesCode(3, false);`
     *
     * @param int|null  $number Nombre d'items à retourner
     * @param bool|null $iso
     *
     * @return array|string
     */
    public static function countriesCode($number = 1, $iso = true)
    {
        $faker = self::getFaker();
        $propertyName = 'countryCode';
        if ($iso) {
            $propertyName = 'countryISOAlpha3';
        }
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->$propertyName;
        }
        return self::getResults();
    }

    /**
     * Obtenir des navigateurs
     *
     * ### Exemple
     * - `RandomItems::userAgents();`
     *
     * @param int|null $number Nombre ditems à retourner
     *
     * @return array|string
     */
    public static function userAgents($number = 1)
    {
        $faker = self::getFaker();
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->userAgent;
        }
        return self::getResults();
    }

    /**
     * Obtenir le nom complet d'un navigateur particulier
     *
     * ### Exemple
     * - `RandomItems::userAgent('opera');`
     *
     * @param string $name Nom court d'un navigateur
     *
     * @return null|string
     */
    public static function userAgent($name)
    {
        $faker = self::getFaker();
        $agent = $faker->$name;
        return $agent ? $agent : null;
    }

    /**
     * Obtenir des noms de sociétés
     *
     * ### Exemple
     * - `RandomItems::companies(3, 'fr_FR');`
     *
     * @param int|null    $number Nombre ditems à retourner
     * @param string|null $locale
     *
     * @return array|string
     */
    public static function companies($number = 1, $locale = null)
    {
        $faker = self::getFaker($locale);
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->company;
        }
        return self::getResults();
    }

    /**
     * Obtenir des stauts juridiques
     *
     * ### Exemple
     * - `RandomItems::juridicsStatus(3, 'fr_FR');`
     *
     * @param int|null    $number Nombre ditems à retourner
     * @param string|null $locale
     *
     * @return array|string
     */
    public static function juridicStatus($number = 1, $locale = null)
    {
        $faker = self::getFaker($locale);
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->companySuffix;
        }
        return self::getResults();
    }

    /**
     * Obtenir des comptes bancaires
     *
     * ### Exemple
     * - `RandomItems::bankAccount(3, 'fr_FR');`
     *
     * @param int|null    $number Nombre ditems à retourner
     * @param string|null $locale
     *
     * @return array
     */
    public static function bankAccount($number = 1, $locale = null)
    {
        $faker = self::getFaker($locale);
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = [
                'bankAccount' => $faker->bankAccountNumber,
                'iban' => $faker->iban($locale),
                'bic' => $faker->swiftBicNumber,
                'domiciliation' => $faker->city
            ];
        }
        return self::getResults();
    }

    /**
     * Obtenir des types de cartes bancaires
     *
     * ### Exemple
     * - `RandomItems::creditCardType(3);`
     *
     * @param int|null $number Nombre d'items à retourner
     *
     * @return array|string
     */
    public static function creditCardType($number = 1)
    {
        $faker = self::getFaker();
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->creditCardType;
        }
        return self::getResults();
    }

    /**
     * Obtenir des numéros de cartes bancaires
     *
     * ### Exemple
     * - `RandomItems::creditCardNumber(3);`
     *
     * @param int|null $number Nombre d'items à retourner
     *
     * @return array|string
     */
    public static function creditCardNumber($number = 1)
    {
        $faker = self::getFaker();
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->creditCardNumber;
        }
        return self::getResults();
    }

    /**
     * Obtenir des cartes bancaires détaillées
     *
     * ### Exemple
     * - `RandomItems::creditCardDetails(3, 'fr_FR');`
     *
     * @param int|null    $number Nombre d'items à retourner
     * @param string|null $locale
     *
     * @return array
     */
    public static function creditCardDetails($number = 1, $locale = null)
    {
        $faker = self::getFaker($locale);
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->creditCardDetails;
        }
        return self::getResults();
    }

    /**
     * Obtenir des codes de devises
     *
     * ### Exemple
     * - `RandomItems::currency(3);`
     *
     * @param int|null $number Nombre d'items à retourner
     *
     * @return array|string
     */
    public static function currency($number = 1)
    {
        $faker = self::getFaker();
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->currencyCode;
        }
        return self::getResults();
    }

    /**
     * Obtenir des codes à barres
     *
     * ### Exemple
     * - `RandomItems::ean(3);`
     * - `RandomItems::ean(3, 8);`
     *
     * @param int|null    $number Nombre d'items à retourner
     * @param string|null $format
     *
     * @return array|string
     */
    public static function ean($number = 1, $format = null)
    {
        $faker = self::getFaker();
        $formats = [8, 13];
        $format = !is_null($format) && in_array(intval($format), $formats) ? (string)$format : '13';
        $propertyName = 'ean' . $format;
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->$propertyName;
        }
        return self::getResults();
    }

    /**
     * Obtenir des noms de couleurs
     *
     * ### Exemple
     * - `RandomItems::colors(3);`
     *
     * @param int|null $number Nombre ditems à retourner
     *
     * @return array|string
     */
    public static function colors($number = 1)
    {
        $faker = self::getFaker();
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->colorName;
        }
        return self::getResults();
    }

    /**
     * Obtenir des fichiers d'un répertoire
     *
     * ### Exemple
     * - `RandomItems::files('path/to/files', 3);`
     *
     * @param string   $path   Chemin du répertoire
     * @param int|null $number Nombre ditems à retourner
     *
     * @return array|string
     */
    public static function files($path, $number = 1)
    {
        $faker = self::getFaker();
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->file($path);
        }
        return self::getResults();
    }

    /**
     * Obtenir des extensions de fichiers
     *
     * ### Exemple
     * - `RandomItems::fileExtensions(3);`
     *
     * @param int|null $number Nombre d'items à retourner
     *
     * @return array|string
     */
    public static function fileExtensions($number = 1)
    {
        $faker = self::getFaker();
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = $faker->fileExtension;
        }
        return self::getResults();
    }

    /**
     * Obtenir des factures
     *
     * ### Exemple
     * - `RandomItems::invoices(3, 'fr_FR');`
     *
     * @param int         $number Nombre d'items à retourner
     * @param string|null $locale
     *
     * @return array|mixed
     */
    public static function invoices($number = 1, $locale = null)
    {
        $faker = self::getFaker($locale);
        for ($i = 0; $i < $number; $i++) {
            self::$datas[] = [
                'nature' => 'Facture',
                'numero' => rand(1, 9999),
                'datePiece' => $faker->date('d-m-Y'),
                'tiersPrincipal' => self::companies(),
                'tiersFacture' => self::companies(),
                'tiersLivre' => self::companies(),
                'tiersPayeur' => self::companies(),
                'totalHtEur' => mt_rand(1, 9999),
                'totalTvaEur' => mt_rand(1, 9999),
                'totalTtcEur' => mt_rand(1, 9999),
                'devise' => 'USD',
                'totalHtDev' => mt_rand(1, 9999),
                'totalTvaDev' => mt_rand(1, 9999),
                'totalTtcDev' => mt_rand(1, 9999),
                'adresses' => [],
                'lignes' => [],
            ];
        }
        return self::getResults();
    }

    /**
     * Obtenir le générateur de données aléatoires
     *
     * ### Exemple
     * - `self::getFaker('fr_FR');`
     *
     * @param string|null $locale
     *
     * @return \Faker\Generator
     */
    private static function getFaker($locale = null)
    {
        if (is_null($locale)) {
            $locale = locale_get_default();
        }
        return Factory::create($locale);
    }

    /**
     * Retourne l'item si le résultat ne contient qu'un seul item
     *
     * ### Exemple
     * - `self::getResults();`
     * - `self::getResults($datas);`
     *
     * @param array|null $datas Données à retourner
     *
     * @return array|mixed
     */
    private static function getResults($datas = null)
    {
        if (is_null($datas)) {
            $datas = self::$datas;
            self::$datas = null;
        }
        return count($datas) === 1 ? $datas[0] : $datas;
    }
}
