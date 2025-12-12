<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtlasEntry extends Model
{
    use HasFactory;

    protected $fillable = ['node_id', 'indicator', 'score', 'source', 'meta'];

    protected $casts = [
        'meta' => 'array',
        'score' => 'float',
    ];

    public function node()
    {
        return $this->belongsTo(Node::class);
    }
}
