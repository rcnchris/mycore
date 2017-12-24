<?php
namespace Tests\Rcnchris;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * Obtenir le chemin racine du projet
     *
     * @return string
     */
    protected function rootPath()
    {
        return dirname(__DIR__);
    }

    /**
     * Affiche un titre coloré en début de test
     *
     * @param string $titre Titre
     * @param bool   $isTest
     */
    protected function ekoTitre($titre = '', $isTest = false)
    {
        $methods = get_class_methods(get_class($this));
        $tests = array_map(function ($method) {
            if (substr($method, 0, 4) === 'test') {
                return $method;
            };
        }, $methods);
        $tests = count(array_filter($tests));
        if ($isTest === true) {
            $tests--;
        }
        $parts = explode(' - ', $titre);
        echo "\n\033[0;36m{$parts[0]}\033[m - {$parts[1]} (\033[0;32m$tests\033[m)\n";
        //$this->assertTrue(true);
    }
}