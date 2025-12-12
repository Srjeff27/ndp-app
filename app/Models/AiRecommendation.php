<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRecommendation extends Model
{
    protected $fillable = [
        'policy_id',
        'average_score',
        'total_reviews',
        'recommendation',
        'ai_analysis',
        'sentiment_breakdown',
        'common_criticisms',
        'analyzed_at',
    ];

    protected $casts = [
        'sentiment_breakdown' => 'array',
        'common_criticisms' => 'array',
        'analyzed_at' => 'datetime',
        'average_score' => 'decimal:2',
    ];

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
