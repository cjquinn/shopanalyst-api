<?php
namespace App\Model\Table;

use App\Model\Entity\ListEntity;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

class ListsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setEntityClass('ListEntity');

        $this->addAssociations([
            'belongsTo' => ['Users'],
            'hasMany' => ['ListItems']
        ]);

        $this->addBehavior('Deletable');
        $this->addBehavior('Timestamp');
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

        return $rules;
    }

    /**
     * @return \App\Model\Entity\ListEntity
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function duplicate($id)
    {
        $list = $this->get(1, [
            'finder' => 'populated'
        ]);

        $duplicateList = $this->newEntity($list->toArray(), [
            'associated' => [
                'ListItems' => [
                    'fieldList' => ['item_id']
                ]
            ],
            'fieldList' => ['user_id', 'name', 'list_items'],
            'validate' => false
        ]);

        return $this->save($duplicateList);
    }

    /**
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->orderDesc($this->aliasField('created'));
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findPopulated(Query $query, array $options)
    {
        $query->contain(['ListItems.Items']);

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
