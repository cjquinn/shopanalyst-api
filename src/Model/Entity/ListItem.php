<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $item_id
 * @property int $list_id
 * @property int $quantity
 * @property bool $is_completed
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class ListItem extends Entity
{

    /**
     * @var array
     */
    protected $_accessible = ['*' => false];
}
