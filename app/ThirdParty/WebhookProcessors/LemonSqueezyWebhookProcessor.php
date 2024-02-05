<?php

namespace App\ThirdParty\WebhookProcessors;

use App\Enums\WebhookOrigin;
use App\Exceptions\WebhookException;
use App\Models\Webhook;
use Illuminate\Http\Request;

class LemonSqueezyWebhookProcessor extends AbstractWebhookProcessor
{
    protected WebhookOrigin $origin = WebhookOrigin::LEMONSQUEEZY;

    /**
     * @inheritDoc
     */
    public function getSignature(Request $request): ?string
    {
        return $request->header(key: 'HTTP_X_SIGNATURE');
    }

    /**
     * @inheritDoc
     */
    public function getEventName(array $payload): ?string
    {
        return $payload['meta']['event_name'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getData(array $payload): array
    {
        return $payload['data'] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function validate(Request $request): void
    {
        $computedSignature = hash_hmac(
            algo: 'sha256',
            data: $request->getContent(),
            key: config('services.lemonsqueezy.webhook.secret_key', '')
        );

        throw_if(
            $this->getSignature($request) !== $computedSignature,
            new WebhookException(message: 'Webhook signature validation failed')
        );

        $webhookEvent = $this->getEventName(payload: $request->input());

        throw_if(
            ! in_array(needle: $webhookEvent, haystack: $this->getSubscribedEvents()),
            new WebhookException(message: "Webhook event: $webhookEvent is not recognised")
        );
    }

    /**
     * @inheritDoc
     */
    public function process(Webhook $webhook): void
    {
        match ($webhook->event_name) {
            // TODO implement webhook processing using each of the events listened to by this origin
            default => throw new WebhookException(message: "An unrecognised webhook event name of: {$webhook->event_name}" ),
        };
    }
}
