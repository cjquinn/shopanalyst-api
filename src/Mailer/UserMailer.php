<?php

namespace App\Mailer;

use App\Model\Entity\User;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    /**
     * @return void
     */
    public function resetPassword(User $user)
    {
        $this
            ->to($user->email)
            ->subject('Reset Password')
            ->from(Configure::read('Shopanalyst.EmailAddresses.support'))
            ->set([
                'url' => [
                    'controller' => 'Users',
                    'action' => 'resetPassword',
                    '?' => ['token' => $user->token],
                    '_full' => true,
                    'prefix' => false
                ]
            ])
            ->template('resetPassword')
            ->emailFormat('both');
    }
}
