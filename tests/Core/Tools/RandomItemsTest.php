<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\RandomItems;
use Tests\Rcnchris\BaseTestCase;

class RandomItemsTest extends BaseTestCase {

    public function testInstance()
    {
        $this->ekoTitre("Tools - Random Items");
        $this->assertInstanceOf(
            RandomItems::class
            , RandomItems::getInstance()
            , $this->getMessage("L'instance attendue est incorrecte")
        );
    }

    public function testGetDate()
    {
        $this->assertInternalType(
            'string'
            , RandomItems::dates()
            , $this->getMessage("Une date doit être au format string")
        );

        $items = RandomItems::dates(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetSentence()
    {
        $this->assertInternalType('string', RandomItems::sentences());

        $items = RandomItems::sentences(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetWords()
    {
        $this->assertInternalType('string', RandomItems::words());

        $items = RandomItems::words(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetUsers()
    {
        $this->assertInternalType('array', RandomItems::users());

        $items = RandomItems::users(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'array'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetPosts()
    {
        $this->assertInternalType('array', RandomItems::posts());

        $items = RandomItems::posts(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'array'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetAddress()
    {
        $this->assertInternalType('array', RandomItems::address());

        $items = RandomItems::address(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'array'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetCountries()
    {
        $this->assertInternalType('string', RandomItems::countries());

        $items = RandomItems::countries(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetCountriesCodes()
    {
        $this->assertInternalType('string', RandomItems::countriesCode());

        $items = RandomItems::countriesCode(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetUserAgents()
    {
        $this->assertInternalType('string', RandomItems::userAgents());

        $items = RandomItems::userAgents(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetUserAgent()
    {
        $this->assertInternalType('string',
            RandomItems::userAgent('opera')
            , $this->getMessage("Le type attendu d'un item est incorrect")
    );
    }

    public function testGetCompanies()
    {
        $this->assertInternalType('string', RandomItems::companies());

        $items = RandomItems::companies(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetJuridicsStatus()
    {
        $this->assertInternalType('string', RandomItems::juridicStatus());

        $items = RandomItems::juridicStatus(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetBankAccount()
    {
        $this->assertInternalType('array', RandomItems::bankAccount());

        $items = RandomItems::bankAccount(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'array'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetCreditCardTypes()
    {
        $this->assertInternalType('string', RandomItems::creditCardType());

        $items = RandomItems::creditCardType(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetCreditCardNumbers()
    {
        $this->assertInternalType('string', RandomItems::creditCardNumber());

        $items = RandomItems::creditCardNumber(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetCreditCardDetails()
    {
        $this->assertInternalType('array', RandomItems::creditCardDetails());

        $items = RandomItems::creditCardDetails(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'array'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetCurrency()
    {
        $this->assertInternalType('string', RandomItems::currency());

        $items = RandomItems::currency(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetEan()
    {
        $this->assertInternalType('string', RandomItems::ean());

        $items = RandomItems::ean(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetColors()
    {
        $this->assertInternalType('string', RandomItems::colors());

        $items = RandomItems::colors(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetFiles()
    {
        $this->assertInternalType('string', RandomItems::files(__DIR__));

        $items = RandomItems::files(__DIR__, 3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois dates dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetExtensionsFiles()
    {
        $this->assertInternalType('string', RandomItems::fileExtensions());

        $items = RandomItems::fileExtensions(3);
        $this->assertCount(
            3
            , $items
            , $this->getMessage("Il devrait y avoir trois extensions dans le tableau")
        );
        $this->assertInternalType(
            'string'
            , current($items)
            , $this->getMessage("Le type attendu d'un item est incorrect")
        );
    }

    public function testGetInvoices()
    {
        $invoice = RandomItems::invoices();
        $this->assertInternalType('array', $invoice);
        $this->assertArrayHasKey('numero', $invoice);
    }

    public function testGetImagesCategories()
    {
        $this->assertContains('sports', RandomItems::getImgagesCategories());
    }

    public function testGetImage()
    {
        $this->assertInternalType('string', RandomItems::image());
    }
}
