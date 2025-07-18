<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use App\Models\VehicleImage;

class Vehicle extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    const STATUS_AVAILABLE = 'AVAILABLE';
    const STATUS_UNAVAILABLE = 'UNAVAILABLE';
    const STATUS_RENTED = 'RENTED';
    const STATUS_DAMAGED = 'DAMAGED';
    const STATUS_UNDER_MAINTENANCE = 'UNDER_MAINTENANCE';

    const TRANSMISSION_MANUAL = 'MANUAL';
    const TRANSMISSION_AUTOMATIC = 'AUTOMATIC';

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

    public function image(){
        return $this->hasMany(VehicleImage::class);
    }
}
