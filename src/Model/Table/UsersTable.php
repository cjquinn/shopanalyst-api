<?php

namespace App\Model\Table;

use App\Model\Entity\User;

use ArrayObject;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

class UsersTable extends Table
{
    use MailerAwareTrait;

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['token']));

        return $rules;
    }

    /**
     * @return void
     */
    public function patchEntityAdd(User $user, array $data)
    {
        $this->patchEntity($user, $data, ['validate' => 'add']);
    }

    /**
     * @return void
     */
    public function patchEntityClearToken(User $user)
    {
        $user->set([
            'token' => null,
            'token_sent' => null
        ], ['guard' => false]);
    }

    /**
     * @return void
     */
    public function patchEntityEdit(User $user, array $data)
    {
        $user->accessible(['current_password', 'new_password'], true);

        $this->patchEntity($user, $data, ['validate' => 'edit']);

        if ($user->current_password &&
            $user->new_password
        ) {
            if (!(new DefaultPasswordHasher)->check($user->current_password, $user->password)) {
                $user->unsetProperty(['current_password', 'new_password']);

                $user->errors('current_password', [
                    'match' => 'The password you entered was incorrect'
                ]);

                return false;
            }

            $user->set('password', $user->new_password);
        }
    }

    /**
     * @return void
     */
    public function patchEntitySetToken(User $user)
    {
        $user->set([
            'token' => str_replace('-', '', Text::uuid()),
            'token_sent' => Time::now()
        ], ['guard' => false]);
    }

    /**
     * @return void
     */
    public function patchEntityResetPassword(User $user, array $data)
    {
        $this->patchEntity($user, $data, ['validate' => 'resetPassword']);

        if (!$user->errors()) {
            $this->patchEntityClearToken($user);
        }
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
        $validator
            ->requirePresence('name')
            ->notEmpty('name');

        $validator
            ->requirePresence('email')
            ->notEmpty('email')
            ->email('email');

        $validator
            ->requirePresence('password')
            ->notEmpty('password');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationEdit(Validator $validator)
    {
        $validator
            ->requirePresence('name')
            ->notEmpty('name');

        $validator
            ->requirePresence('email')
            ->notEmpty('email')
            ->email('email');

        $validator
            ->requirePresence('current_password')
            ->notEmpty('current_password', 'You must enter your current password', function ($context) {
                return !empty($context['data']['new_password']);
            });

        $validator
            ->requirePresence('new_password')
            ->notEmpty('new_password', 'You must enter a new password', function ($context) {
                return !empty($context['data']['current_password']);
            });

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationResetPassword(Validator $validator)
    {
        $validator
            ->requirePresence('password')
            ->notEmpty('password');

        return $validator;
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, User $user, ArrayObject $options)
    {
        if ($user->token &&
            $user->dirty('token')
        ) {
            $this->getMailer('User')->send('resetPassword', [$user]);
        }
    }

    /**
     * @param string|null $token
     * @return bool|\App\Model\Entity\User
     */
    public function getByToken($token)
    {
        $user = $this
            ->findByToken($token)
            ->first();

        return $user;
    }
}
