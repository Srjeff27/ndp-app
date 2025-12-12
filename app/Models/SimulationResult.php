<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SimulationResult extends Model
{
    use HasFactory;

    protected $fillable = ['node_id', 'user_id', 'indicator', 'old_score', 'new_score', 'change_percent'];

    protected $casts = [
        'old_score' => 'float',
        'new_score' => 'float',
        'change_percent' => 'float',
    ];

    public function node()
    {
        return $this->belongsTo(Node::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
