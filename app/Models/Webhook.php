<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Webhook
 *
 * @property int $id
 * @property string $origin
 * @property string|null $event_name
 * @property array $payload
 * @property int $tries
 * @property string|null $exception
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $processed_at
 * @property \Illuminate\Support\Carbon|null $failed_at
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook query()
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereEventName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereFailedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereTries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Webhook whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Webhook extends Model
{
    use HasFactory;

    /**
     * @inheritdoc
     */
    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'origin',
        'event_name',
        'payload',
        'tries',
        'exception',
        'processed_at',
        'failed_at',
    ];
}
