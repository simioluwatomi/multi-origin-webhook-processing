<?php

namespace App\ThirdParty\WebhookProcessors;

use App\Enums\WebhookOrigin;
use App\Models\Webhook;
use App\ThirdParty\WebhookProcessors\Contracts\WebhookProcessorInterface;
use Illuminate\Support\Str;

abstract class AbstractWebhookProcessor implements WebhookProcessorInterface
{
    protected WebhookOrigin $origin;

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return Str::of($this->origin->value)->squish()->lower()->value();
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return config("services.{$this->getName()}.webhook.events", []);
    }

    /**
     * @inheritDoc
     */
    public function originExpectsAResponse(): bool
    {
        return false;
    }

    public function generateResponse(Webhook $webhook): array
    {
        return [];
    }
}
