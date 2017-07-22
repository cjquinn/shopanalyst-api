<?php

namespace App\Model\Behavior;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;

class DeletableBehavior extends Behavior
{
    /**
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->where([$this->_table->aliasField('is_deleted') => false]);
    }

    /**
     * @return void
     */
    public function softDelete(Entity $entity)
    {
        $entity->set('is_deleted', true);

        $this->_table->save($entity);
    }
}
