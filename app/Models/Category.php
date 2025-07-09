<?php

namespace App\Models;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'categories';
    protected $fillable = [
        'slug',
        'name',
        'description'
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
