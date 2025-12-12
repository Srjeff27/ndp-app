<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Policy extends Model
{
    protected $fillable = [
        'node_id',
        'title',
        'description',
        'category',
        'budget',
        'implementation_date',
        'status',
        'budget_breakdown',
    ];

    protected $casts = [
        'budget_breakdown' => 'array',
        'implementation_date' => 'date',
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(PolicyReview::class);
    }

    public function recommendation(): HasOne
    {
        return $this->hasOne(AiRecommendation::class);
    }

    public function averageScore(): float
    {
        return $this->reviews()->avg('score') ?? 0;
    }

    public function isApproved(): bool
    {
        return $this->averageScore() >= 91;
    }

    public function getStatusColorAttribute(): string
    {
        return $this->isApproved() ? 'green' : 'red';
    }
}
