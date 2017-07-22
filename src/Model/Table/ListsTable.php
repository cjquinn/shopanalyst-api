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
     * @return void
     */
    public function patchEntityAddListItems(ListEntity $list, array $data, $userId)
    {
        $list->setErrors(
            $this->validator('addListItems')->errors($data)
        );

        if (!$list->getErrors()) {
            $data = Hash::insert(
                $data,
                'list_items.{n}[item].item.user_id',
                $userId
            );

            $this->patchEntity($list, $data, [
                'associated' => [
                    'ListItems' => [
                        'associated' => [
                            'Items' => [
                                'fieldList' => ['user_id', 'name']
                            ]
                        ],
                        'fieldList' => ['item', 'item_id']
                    ]
                ],
                'fieldList' => ['list_items'],
                'validate' => false
            ]);
        }
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationAddListItems(Validator $validator)
    {
        $validator
            ->requirePresence('list_items')
            ->addNestedMany(
                'list_items',
                $this->ListItems->validator()
            );

        return $validator;
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
     * @return void|bool
     */
    public function beforeSave(Event $event, ListEntity $list, ArrayObject $options)
    {
        if ($list->isDirty('list_items')) {
            foreach ($list->list_items as $listItem) {
                if ($listItem->item_id &&
                    !$this->ListItems->Items->isOwnedBy(
                        $listItem->item_id,
                        $list->user_id
                    )
                ) {
                    return false;
                }
            }
        }
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
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->orderAsc($this->aliasField('created'));
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
