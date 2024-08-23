<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use function Hyperf\Support\env;

class EmailService
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->Host = env('MAIL_HOST');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = env('MAIL_USERNAME');
        $this->mailer->Password = env('MAIL_PASSWORD');
        $this->mailer->Port = env('MAIL_PORT');
        $this->mailer->SMTPDebug = 4;
        $this->mailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
        $this->mailer->SMTPSecure = false;
        $this->mailer->SMTPAutoTLS = false;
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        try {
            $this->mailer->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $this->mailer->addAddress($to);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;

            $this->mailer->send();
        } catch (Exception $e) {
            throw new \RuntimeException("Erro ao enviar e-mail: {$this->mailer->ErrorInfo}", 0, $e);
        }
    }
}
