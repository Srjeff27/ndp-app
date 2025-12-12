<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lab extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'node_id', 'user_id'];

    public function node()
    {
        return $this->belongsTo(Node::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(LabPost::class);
    }
}
