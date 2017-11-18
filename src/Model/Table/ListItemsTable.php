<?php

namespace App\Model\Table;

use App\Model\Entity\ListItem;

use ArrayObject;

use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ListItemsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addAssociations([
            'belongsTo' => [
                'Items',
                'Lists'
            ]
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * @return void
     */
    public function patchEntityUpdateQuantity(ListItem $listItem, array $data)
    {
        $this->patchEntity($listItem, $data, [
            'fieldList' => ['quantity'],
            'validate' => 'updateQuantity'
        ]);

        $listItem->setDirty('modified', true);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('item', function ($context) {
                return !isset($context['data']['item_id']);
            })
            ->notEmpty('item')
            ->addNested('item', $this->Items->validator());

        $validator
            ->requirePresence('item_id', function ($context) {
                return !isset($context['data']['item']);
            })
            ->notEmpty('item_id')
            ->integer('item_id');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationUpdateQuantity(Validator $validator)
    {
        $validator
            ->requirePresence('quantity')
            ->notEmpty('quantity')
            ->naturalNumber('quantity');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        $rules->add($rules->existsIn(['list_id'], 'Lists'));
        $rules->add($rules->isUnique(['item_id', 'list_id']));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->order([
            $this->aliasField('completed') => 'ASC',
            $this->aliasField('modified') => 'DESC'
        ]);
    }

    /**
     * @return void
     */
    public function toggleCompleted(ListItem $listItem)
    {
        $listItem->set(
            'completed',
            $listItem->completed
                ? null
                : Time::now()
        );

        $this->save($listItem);
    }

    /**
     * @return bool
     */
    public function isOwnedBy($id, $listId)
    {
        return $this->exists([
            'id' => $id,
            'list_id' => $listId
        ]);
    }
}
