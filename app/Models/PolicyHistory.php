<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyHistory extends Model
{
    protected $table = 'policy_history';

    protected $fillable = [
        'node_id',
        'title',
        'description',
        'year',
        'budget',
        'outcome',
        'impact_summary',
    ];

    protected $casts = [
        //
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }
}
