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
                'url' => sprintf(
                    'https://myshopanalyst.com/reset-password?token=%s',
                    $user->token
                )
            ])
            ->template('resetPassword')
            ->emailFormat('both');
    }
}
