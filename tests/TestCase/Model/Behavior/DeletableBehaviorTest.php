<?php

namespace App\Test\TestCase\Model\Behavior;

use App\Model\Behavior\DeletableBehavior;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class DeletableBehaviorTest extends TestCase
{

    /**
     * @var \App\Model\Behavior\DeletableBehavior
     */
    public $Deletable;

    /**
     * @var array
     */
    public $fixtures = ['app.lists'];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Deletable = new DeletableBehavior(TableRegistry::get('Lists'));
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Deletable);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testBeforeFind()
    {
        $this->assertFalse($this->Deletable->getTable()->find()->isEmpty());

        $this->Deletable->getTable()->updateAll(['is_deleted' => true], ['is_deleted' => false]);

        $this->assertTrue($this->Deletable->getTable()->find()->isEmpty());
    }

    /**
     * @return void
     */
    public function testSoftDelete()
    {
        $deletableEntity = $this->Deletable->getTable()->get(1);

        $this->Deletable->softDelete($deletableEntity);

        $this->assertTrue($deletableEntity->is_deleted);
    }
}
