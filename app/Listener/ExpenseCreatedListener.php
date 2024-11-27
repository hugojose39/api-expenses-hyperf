<?php

namespace App\Listener;

use App\Event\ExpenseCreated;
use App\Interfaces\EmailServiceInterface;
use Hyperf\Event\Contract\ListenerInterface;

class ExpenseCreatedListener implements ListenerInterface
{
    public function __construct(private readonly EmailServiceInterface $emailService)
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
