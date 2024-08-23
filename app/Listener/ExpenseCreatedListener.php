<?php

namespace App\Listener;

use App\Event\ExpenseCreated;
use Hyperf\Event\Contract\ListenerInterface;
use App\Service\EmailService;

class ExpenseCreatedListener implements ListenerInterface
{
    public function __construct(private EmailService $emailService)
    {
    }

    public function listen(): array
    {
        return [
            ExpenseCreated::class,
        ];
    }

    public function process(object $event): void
    {
        foreach ($event->users as $user) {
            $this->emailService->sendEmail(
                $user->email,
                'Nova despesa criada',
                'Uma nova despesa foi registrada no seu cartao.'
            );
        }
    }
}
