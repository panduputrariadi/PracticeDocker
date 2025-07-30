<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Vehicle;

class Brand extends Model
{
    use HasUuids, SoftDeletes;
    protected $table = 'brands';
    protected $fillable = [
        'brand_name',
        'slug',
        'description'
    ];

    public function VehicleModels()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_brand_id', 'id');
    }
}
