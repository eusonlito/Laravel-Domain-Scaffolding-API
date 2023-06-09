<?php declare(strict_types=1);

namespace App\Domains\User\Mail;

use App\Domains\User\Model\User as Model;
use App\Domains\UserCode\Model\UserCode as UserCodeModel;

class PasswordResetStart extends MailAbstract
{
    /**
     * @var string
     */
    public $view = 'domains.user.mail.password-reset-start';

    /**
     * @param \App\Domains\User\Model\User $row
     * @param \App\Domains\UserCode\Model\UserCode $userCode
     *
     * @return self
     */
    public function __construct(public Model $row, public UserCodeModel $userCode)
    {
        $this->locale($row->language->iso);
        $this->to($row->email);

        $this->subject = __('user-password-reset-start-mail.subject');
    }
}
