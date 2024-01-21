<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
