<?php
namespace App\Event;


class ExpenseCreated
{
    public function __construct(public $users)
    {
    }
}
