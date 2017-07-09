<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 */
class Item extends Entity
{

    /**
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        '*' => false
    ];
}
