<?php

namespace App\Model\Table;

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
                return !isset($context['data']['list_id']);
            })
            ->notEmpty('item');

        $validator
            ->requirePresence('item_id', function ($context) {
                return !isset($context['data']['list']);
            })
            ->integer('item_id')
            ->notEmpty('item_id');

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
}
