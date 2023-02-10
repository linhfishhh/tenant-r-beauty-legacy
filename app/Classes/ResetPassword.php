<?php
namespace App\Classes;


use App\User;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends \Illuminate\Auth\Notifications\ResetPassword
{
    protected $user;
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Khởi tạo mật khẩu'))
            ->line(__('Bạn nhận được thông điệp này vì đã có yêu khởi tạo mật khẩu đăng nhập từ tài khoản của bạn'))
            ->action(__('Lấy lại mật khẩu'), url(config('app.url').route('frontend.reset_password.index', ['token'=>$this->token, 'email'=>$this->user->email], false)))
            ->line(__('Nếu bạn không có thực hiện yêu này vui lòng bỏ qua email thông báo này.'));
    }

    public function __construct(string $token, User $user)
    {
        parent::__construct($token);
        $this->user = $user;
    }
}