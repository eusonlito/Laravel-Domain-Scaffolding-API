<?php declare(strict_types=1);

namespace App\Domains\User\Mail;

use App\Domains\Shared\Mail\MailAbstract;

class PasswordResetFail extends MailAbstract
{
    /**
     * @var string
     */
    public $view = 'domains.user.mail.password-reset-fail';

    /**
     * @param array $data
     *
     * @return self
     */
    public function __construct(public array $data)
    {
        $this->to($data['email']);

        $this->subject = __('user-password-reset-fail-mail.subject');
    }
}
