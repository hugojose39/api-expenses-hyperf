<?php

namespace App\Listener;

use App\Event\ExpenseCreated;
use Hyperf\Event\Contract\ListenerInterface;

class ExpenseCreatedListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            ExpenseCreated::class,
        ];
    }

    public function process(object $event): void
    {
        var_dump($event);
    }
}
