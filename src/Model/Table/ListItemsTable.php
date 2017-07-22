<?php

namespace App\Model\Table;

use App\Model\Entity\ListItem;

use ArrayObject;

use Cake\Event\Event;
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
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('item', function ($context) {
                return !isset($context['data']['item_id']);
            })
            ->addNested('item', $this->Items->validator());

        $validator
            ->requirePresence('item_id', function ($context) {
                return !isset($context['data']['item']);
            })
            ->integer('item_id');

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
        $query->orderAsc($this->aliasField('created'));
    }

    /**
     * @return void
     */
    public function toggleCompleted(ListItem $listItem)
    {
        $listItem->set('is_completed', !$listItem->is_completed);

        $this->save($listItem);
    }

    /**
     * @return void
     */
    public function updateQuantity(ListItem $listItem, $difference)
    {
        $listItem->set(
            'quantity',
            max($listItem->quantity + $difference, 1)
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
