<?php

namespace App\Support\Managers;

use App\ThirdParty\WebhookProcessors\Contracts\WebhookProcessorInterface;
use App\ThirdParty\WebhookProcessors\LemonSqueezyWebhookProcessor;
use App\ThirdParty\WebhookProcessors\NullWebhookProcessor;
use App\ThirdParty\WebhookProcessors\PaystackWebhookProcessor;
use Illuminate\Support\Manager;

class WebhookManager extends Manager
{
    public function createPaystackDriver(): WebhookProcessorInterface
    {
        return new PaystackWebhookProcessor();
    }

    public function createLemonsqueezyDriver(): WebhookProcessorInterface
    {
        return new LemonSqueezyWebhookProcessor();
    }

    public function createNullDriver(): WebhookProcessorInterface
    {
        return new NullWebhookProcessor();
    }

    /**
     * @inheritDoc
     */
    public function getDefaultDriver(): string
    {
        return 'null';
    }
}
