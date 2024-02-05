<?php

namespace App\Enums;

enum WebhookOrigin: string
{
    case PAYSTACK = 'Paystack';

    case LEMONSQUEEZY = 'lemon-squeezy';
}
