<?php

namespace App\Jobs;

use App\Exceptions\WebhookException;
use App\Models\Webhook;
use App\Support\Managers\WebhookManager;
use App\ThirdParty\WebhookProcessors\Contracts\WebhookProcessorInterface;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessWebhookJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 4;

    /**
     * Create a new job instance.
     */
    public function __construct(public Webhook $webhook)
    {
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return (string) $this->webhook->id;
    }

    /**
     * Execute the job.
     */
    public function handle(WebhookManager $manager): void
    {
        try {
            /** @var WebhookProcessorInterface|null $webhookProcessor */
            $webhookProcessor = $manager->driver($this->webhook->origin);

            throw_if(
                $webhookProcessor === null,
                new WebhookException(message: "Could not find a processor for webhook with id: {$this->webhook->id}")
            );

            $webhookProcessor->process($this->webhook);

            $this->webhook->update([
                'processed_at' => Carbon::now(),
                'exception' => null,
                'failed_at' => null,
                'tries' => ++$this->webhook->tries
            ]);
        } catch (\Throwable $exception) {
            $this->webhook->update([
                'failed_at' => Carbon::now(),
                'exception' => (string) $exception,
                'tries' => ++$this->webhook->tries
            ]);

            Log::error('Webhook processing failed', ['exception' => $exception]);

            $this->release(Carbon::now()->addMinutes(15));
        }
    }
}
