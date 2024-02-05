<?php

namespace App\ThirdParty\WebhookProcessors\Contracts;

use App\Models\Webhook;
use Illuminate\Http\Request;

interface WebhookProcessorInterface
{
    /**
     * Returns the name of the webhook processor
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the provided verification signature from the payload body or header.
     *
     * @param Request $request
     *
     * @return string|null
     */
    public function getSignature(Request $request): ?string;

    /**
     * Returns the webhook event name from the payload if present.
     *
     * @param array $payload
     *
     * @return string|null
     */
    public function getEventName(array $payload): ?string;

    /**
     * Returns an array of events that is subscribed to from this webhook origin.
     *
     * @return array
     */
    public function getSubscribedEvents(): array;

    /**
     * Returns the actual event data contained in the webhook.
     *
     * @param array $payload
     *
     * @return array
     */
    public function getData(array $payload): array;

    /**
     * Validates the request and throws an exception if validation fails.
     *
     * @param Request $request
     *
     * @throws \Throwable
     */
    public function validate(Request $request): void;

    /**
     * Process the webhook.
     *
     * @param Webhook $webhook
     *
     * @throws \Throwable
     */
    public function process(Webhook $webhook): void;

    /**
     * Checks whether the webhook origin requires a response.
     *
     * @return bool
     */
    public function originExpectsAResponse(): bool;

    /**
     * This method should generate the response data if the processor expects a response
     *
     * @param Webhook $webhook
     *
     * @return array
     */
    public function generateResponse(Webhook $webhook): array;
}
