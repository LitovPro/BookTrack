<?php

namespace app\services;

/**
 * Interface for SMS sending services
 */
interface SmsSenderInterface
{
    /**
     * Send SMS message
     *
     * @param string $phone Phone number
     * @param string $message Message text
     * @return array Response data
     */
    public function sendSms(string $phone, string $message): array;
}

