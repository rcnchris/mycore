<?php
/**
 * Fichier DateAppBehavior.php du 10/07/2018
 * Description : Fichier de la classe DateAppBehavior
 *
 * PHP version 5
 *
 * @category Comportement
 *
 * @package  Rcnchris\Core\ORM\Cake\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM\Cake\Behaviors;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;

/**
 * Class DateAppBehavior
 *
 * @category Comportement
 *
 * @package  Rcnchris\Core\ORM\Cake\Behaviors
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class DateAppBehavior extends Behavior
{
    // phpcs:disable
    /**
     * Configuration par défaut des dates d'application
     *
     * @var array
     */
    protected $_defaultConfig = [
        'start' => 'start_at',
        'end' => 'end_at',
        'year' => 10,
    ];
    // phpcs:enable

    /**
     * Renseigne les champs de dates d'application
     *
     * @param \Cake\Datasource\EntityInterface $entity
     */
    public function dateApp(EntityInterface $entity)
    {
        $config = $this->getConfig();
        $start = $entity->get($config['start']);
        if (is_null($start)) {
            $start = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        } elseif (is_string($start)) {
            $start = \DateTime::createFromFormat('d-m-Y H:i:s', $start);
        }
        $interval = 'P' . $config['year'] . 'Y';
        $end = clone($start);
        $end = $end->add(new \DateInterval($interval));
        $entity->set($config['start'], $start->format('Y-m-d H:i:s'));
        $entity->set($config['end'], $end->format('Y-m-d H:i:s'));
    }

    /**
     * Lance le traitement des dates d'application avant la sauvegarde en base de données
     *
     * @param \Cake\Event\Event                $event
     * @param \Cake\Datasource\EntityInterface $entity
     */
    public function beforeSave(Event $event, EntityInterface $entity)
    {
        $this->dateApp($entity);
    }
}
