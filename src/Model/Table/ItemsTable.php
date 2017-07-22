<?php
namespace App\Model\Table;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ItemsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->belongsTo('Users');
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('name')
            ->notEmpty('name');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->isUnique(['user_id', 'name']));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->orderAsc($this->aliasField('name'));
    }

    /**
     * @return void
     */
    public function findFiltered(Query $query, array $options)
    {
        if (isset($options['search'])) {
            $query->where([
                'name LIKE' => '%' . $options['search'] . '%'
            ]);
        }

        return $query;
    }

    /**
     * @return bool
     */
    public function isOwnedBy($id, $userId)
    {
        return $this->exists([
            'id' => $id,
            'user_id' => $userId
        ]);
    }
}
