<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabPost extends Model
{
    use HasFactory;

    protected $fillable = ['lab_id', 'user_id', 'content'];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
