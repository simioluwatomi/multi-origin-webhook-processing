<?php

namespace App\Http\Controllers;

use App\Exceptions\WebhookException;
use App\Jobs\ProcessWebhookJob;
use App\Models\Webhook;
use App\Support\Managers\WebhookManager;
use App\ThirdParty\WebhookProcessors\Contracts\WebhookProcessorInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function store(Request $request, string $origin, WebhookManager $manager): JsonResponse
    {
        /** @var WebhookProcessorInterface|null $webhookProcessor */
        $webhookProcessor = $manager->driver(Str::lower($origin));

        throw_if(
            $webhookProcessor === null,
            new WebhookException(message: "Could not find a webhook processor for the origin: {$origin}")
        );

        $webhookProcessor->validate($request);

        $webhook = Webhook::create([
            'origin' => $webhookProcessor->getName(),
            'event_name' => $webhookProcessor->getEventName($request->input()),
            'payload' => $request->input(),
        ]);

        Queue::push(new ProcessWebhookJob($webhook));

        if ($webhookProcessor->originExpectsAResponse()) {
            return response()->json(data: $webhookProcessor->generateResponse($webhook));
        }

        return response()->json();
    }
}
