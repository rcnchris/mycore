<?php
/**
 * Fichier SluggableBehavior.php du 10/07/2018
 * Description : Fichier de la classe SluggableBehavior
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
use Cake\ORM\Query;
use Cake\Utility\Text;

/**
 * Class SluggableBehavior
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
class SluggableBehavior extends Behavior
{
    // phpcs:disable
    /**
     * Options par défaut du behavior
     *
     * @var array
     */
    protected $_defaultConfig = [
        'field' => 'title',
        'slug' => 'slug',
        'replacement' => '-'
    ];
    // phpcs:enable

    /**
     * Génère le slug à partir de la configuration
     *
     * @param \Cake\Datasource\EntityInterface|\Cake\ORM\Entity $entity
     */
    public function slug(EntityInterface $entity)
    {
        // Préfixe la valeur par le slug du parent si le behavior Tree est détecté
        $value = $this->getParentSlug($entity);
        $entity->set(
            $this->getConfig('slug'),
            strtolower(Text::slug($value, $this->getConfig('replacement')))
        );
    }

    /**
     * Appelle la méthode de génération du slug avant la sauvegarde en base
     *
     * @param \Cake\Event\Event                                 $event
     * @param \Cake\Datasource\EntityInterface|\Cake\ORM\Entity $entity
     */
    public function beforeSave(Event $event, EntityInterface $entity)
    {
        $this->slug($entity);
    }

    /**
     * Obtenir les items d'un slug
     *
     * @param \Cake\ORM\Query $query
     * @param array           $options
     *
     * @return $this
     */
    public function findSlug(Query $query, array $options)
    {
        return $query->where(['slug' => $options['slug']]);
    }

    /**
     * Préfixe la valeur par le slug du parent si le behavior `Tree` est détecté
     *
     * @param \Cake\Datasource\EntityInterface $entity
     *
     * @return string
     */
    private function getParentSlug(EntityInterface $entity)
    {
        $value = $entity->get($this->getConfig('field'));
        if ($this->getTable()->hasBehavior('Tree')) {
            $parentProperty = $this->getTable()->getBehavior('Tree')->getConfig('parent');
            if ($entity->$parentProperty != null && $entity->$parentProperty != 1) {
                $value = $this
                        ->getTable()
                        ->find()
                        ->select(['slug'])
                        ->where([
                            'id' => $entity->get('parent_id'),
                            'parent_id' > 1
                        ])
                        ->first()
                        ->slug
                    . $this->getConfig('replacement')
                    . $value;
            }
        }
        return $value;
    }
}
