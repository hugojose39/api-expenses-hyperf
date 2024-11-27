<?php

namespace App\Interfaces;


interface EmailServiceInterface
{
    public function sendEmail(string $to, string $subject, string $body): void;
}