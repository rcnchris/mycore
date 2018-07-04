<?php
/**
 * Fichier TableFactory.php du 28/06/2018
 * Description : Fichier de la classe TableFactory
 *
 * PHP version 5
 *
 * @category ORM
 *
 * @package  App\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\Phinx\PhinxTable;
use Cake\ORM\TableRegistry;
use Phinx\Db\Adapter\AdapterFactory;

/**
 * Class TableFactory
 *
 * @category ORM
 *
 * @package  App\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class TableFactory
{
    /**
     * ORM gérés par cette classe
     *
     * @var array
     */
    private static $orms = ['cake', 'phinx'];

    /**
     * Options par défaut de création d'une table
     *
     * @var array
     */
    private static $defaultOptions = [
        'orm' => 'cake'
    ];

    /**
     * @param       $name
     * @param array $options
     *
     * @return \Cake\ORM\Table|\Rcnchris\Core\ORM\Phinx\PhinxTable
     */
    public static function get($name, array $options = [])
    {
        r($options);
        $options = self::parseOptions($options);
        if (self::hasOrm($options['orm'])) {
            $orm = $options['orm'];
            unset($options['orm']);
            if ($orm === 'cake') {
                return TableRegistry::getTableLocator()->get($name, $options);
            } elseif ($orm === 'phinx') {
                return new PhinxTable($name, $options, AdapterFactory::instance()->getAdapter('mysql', []));
            }
            die("Le nom de l'ORM $orm n'est pas gérée par cette classe !");
        }
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private static function parseOptions(array $options)
    {
        $options = array_merge(self::$defaultOptions, $options);
        return $options;
    }

    /**
     * Vérifie la présence d'un ORM dans la liste
     *
     * @param string $name Nom d'un ORM
     *
     * @return bool
     */
    private static function hasOrm($name)
    {
        return in_array($name, self::$orms);
    }
}
