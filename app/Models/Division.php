<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasUuids;

    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
