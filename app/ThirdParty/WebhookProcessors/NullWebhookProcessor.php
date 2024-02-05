<?php

namespace App\ThirdParty\WebhookProcessors;

use App\Exceptions\WebhookException;
use App\Models\Webhook;
use Illuminate\Http\Request;

class NullWebhookProcessor extends AbstractWebhookProcessor
{

    /**
     * @inheritDoc
     */
    public function getSignature(Request $request): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getEventName(array $payload): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getData(array $payload): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function validate(Request $request): void
    {
        throw new WebhookException('Could not validate webhook.');
    }

    /**
     * @inheritDoc
     */
    public function process(Webhook $webhook): void
    {
        throw new WebhookException(message: 'Null driver can not process webhooks');
    }
}
