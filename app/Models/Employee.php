<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasUuids;

    protected $fillable = [
        'image',
        'name',
        'phone',
        'division_id',
        'position',
    ];

    protected $hidden = [
        'division_id',
        'created_at',
        'updated_at',
    ];

    public function getImageAttribute()
    {
        return asset(Storage::url($this->attributes['image']));
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
