<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;

class Vehicle extends Model
{
    use HasUuids, HasFactory, SoftDeletes;
    protected $table = 'vehicles';
    protected $fillable = [
        'slug',
        'name',
        'description',
        'category_id',
        'status',
        'transmission',
        'plate_number',
        'fuel_type',
        'color',
        'rate_per_day',
        'rate_per_hour',
        'capacity',
        'mileage',
        'model',
        'brand',
        'type',
        'year',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
