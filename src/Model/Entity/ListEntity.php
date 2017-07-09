<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class ListEntity extends Entity
{

    /**
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        '*' => false
    ];
}
