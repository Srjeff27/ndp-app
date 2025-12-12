<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Node extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'institution', 'country', 'lat', 'lng', 'user_id'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function atlasEntries()
    {
        return $this->hasMany(AtlasEntry::class);
    }

    public function labs()
    {
        return $this->hasMany(Lab::class);
    }

    public function simulations()
    {
        return $this->hasMany(SimulationResult::class);
    }
}
