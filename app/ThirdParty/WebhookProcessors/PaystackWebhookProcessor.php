<?php

namespace App\ThirdParty\WebhookProcessors;

use App\Enums\WebhookOrigin;
use App\Exceptions\WebhookException;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class PaystackWebhookProcessor extends AbstractWebhookProcessor
{
    protected WebhookOrigin $origin = WebhookOrigin::PAYSTACK;

    /**
     * @inheritDoc
     */
    public function getSignature(Request $request): ?string
    {
        return $request->header(key: 'X-Paystack-Signature');
    }

    /**
     * @inheritDoc
     */
    public function getEventName(array $payload): ?string
    {
        return $payload['event'] ?? null;
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
            algo: 'sha512',
            data: $request->getContent(),
            key: config(key: 'services.paystack.secret_key', default: '')
        );

        throw_if(
            $this->getSignature($request) !== $computedSignature,
            new WebhookException(message:'Webhook signature validation failed')
        );

        $webhookSourceIps = (array) config(key: "services.paystack.webhook.source_ips", default: []);

        $requestIp = $this->getConnectingIp(request: $request);

        throw_if(
            ! IpUtils::checkIp(requestIp: $requestIp, ips: $webhookSourceIps),
            new WebhookException(message: "Invalid webhook origin ip address: $requestIp")
        );

        $webhookEvent = $this->getEventName(payload: $request->input());

        throw_if(
            ! in_array(needle: $webhookEvent, haystack: $this->getSubscribedEvents()),
            new WebhookException(message: "Webhook event: $webhookEvent is not recognised")
        );
    }

    /**
     * @inheritDoc
     *
     * @throws WebhookException
     */
    public function process(Webhook $webhook): void
    {
        match ($webhook->event_name) {
            // TODO implement webhook processing using each of the events listened to by this origin
            default => throw new WebhookException(message: "An unrecognised webhook event name of: {$webhook->event_name}" ),
        };
    }

    /**
     * Fetches the connecting IP address from the request.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getConnectingIp(Request $request): string
    {
        return $request->header(key: 'cf-connecting-ip')
            ?? $request->header(key: 'x-forwarded-for')
            ?? $request->ip()
            ?? '';
    }
}
